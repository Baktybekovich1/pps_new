#!/usr/bin/env bash
#
# Деплой prod-стека PPS.
#
# Запуск с сервера, из корня репозитория:
#   ./bin/deploy-prod.sh
#
# Либо копией, лежащей вне репозитория, с путём к нему аргументом:
#   /root/deploy-prod.sh /root/backend/test/pps_new
#
# Второй способ нужен для первого деплоя: если положить скрипт внутрь
# репозитория до обновления кода, git откажется делать merge, потому что
# этот же файл приходит из origin/master и перезатёрся бы.
#
# Что делает, по порядку, останавливаясь на первой же ошибке:
#   1. снимает дамп БД и бэкап JWT-ключей и docker/.env ВНЕ репозитория;
#   2. обновляет код из origin/master;
#   3. возвращает JWT-ключи, если git их удалил (они больше не в индексе);
#   4. пересобирает и пересоздаёт контейнеры, перезагружает прокси,
#      пересобирает кэш Symfony;
#   5. применяет миграции Doctrine;
#   6. печатает проверку состояния.
#
# Повторный запуск безопасен: применённые миграции Doctrine пропускает.
#
# ВНИМАНИЕ: среди миграций есть Version20260813120000 — она удаляет таблицы
# "user" и user_offence (авторизация переведена на Teacher). Данные этих таблиц
# восстанавливаются только из дампа, который снимается на шаге 1.

set -euo pipefail

if [ -n "${1:-}" ]; then
  REPO_ROOT="$(cd "$1" 2>/dev/null && pwd)" \
    || { printf '\n\033[1;31m!! Путь "%s" не существует.\033[0m\n' "$1" >&2; exit 1; }
elif git -C . rev-parse --show-toplevel >/dev/null 2>&1; then
  REPO_ROOT="$(git -C . rev-parse --show-toplevel)"
else
  REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
fi
cd "$REPO_ROOT"

BRANCH="${DEPLOY_BRANCH:-master}"
BACKUP_DIR="${DEPLOY_BACKUP_DIR:-/root/pps-backup-$(date +%F-%H%M%S)}"
MIN_DUMP_BYTES=10240
# Сколько последних папок /root/pps-backup-* хранить (старые удаляются после
# успешного деплоя). Ежедневные дампы лежат отдельно, в /root/pps-db-backups.
KEEP_DEPLOY_BACKUPS="${DEPLOY_KEEP_BACKUPS:-10}"
# Общий обратный прокси на хосте (отдельный контейнер, не из этого compose).
# Он резолвит апстрим один раз при старте, поэтому после пересоздания
# контейнера nginx его нужно перезагрузить, иначе снаружи будет 502.
PROXY_CONTAINER="${DEPLOY_PROXY_CONTAINER:-proxy}"
APP_HOST="${DEPLOY_APP_HOST:-api.pps.makalabox.com}"
HEALTH_PATH="${DEPLOY_HEALTH_PATH:-/api}"

log()  { printf '\n\033[1;34m==> %s\033[0m\n' "$*"; }
ok()   { printf '    \033[1;32m✓\033[0m %s\n' "$*"; }
die()  { printf '\n\033[1;31m!! %s\033[0m\n' "$*" >&2; exit 1; }

# --- 0. окружение ------------------------------------------------------------

[ -f docker/docker-compose.prod.yml ] || die "В '$REPO_ROOT' нет docker/docker-compose.prod.yml.
Запустите скрипт из корня репозитория или передайте путь к нему аргументом:
    $0 /root/backend/test/pps_new"
[ -f docker/.env ] || die "docker/.env не найден. Без него compose не знает имён контейнеров и доступов к БД."

set -a
# shellcheck disable=SC1091
. docker/.env
set +a

for var in PHP_CONTAINER_NAME NGINX_CONTAINER_NAME DB_CONTAINER_NAME DB_USER DB_NAME; do
  [ -n "${!var:-}" ] || die "В docker/.env не задана переменная $var."
done

COMPOSE=(docker compose -f docker-compose.prod.yml)

log "Деплой ветки '$BRANCH' в $REPO_ROOT"
ok "контейнеры: $PHP_CONTAINER_NAME / $NGINX_CONTAINER_NAME / $DB_CONTAINER_NAME"
ok "бэкап в: $BACKUP_DIR"

# --- 1. бэкап ----------------------------------------------------------------

log "1/6 Дамп БД и бэкап секретов"
mkdir -p "$BACKUP_DIR"

docker inspect -f '{{.State.Running}}' "$DB_CONTAINER_NAME" >/dev/null 2>&1 \
  || die "Контейнер БД '$DB_CONTAINER_NAME' не найден. Поднимите стек, прежде чем деплоить."

if [ "$(docker inspect -f '{{.State.Running}}' "$DB_CONTAINER_NAME")" != "true" ]; then
  log "БД не запущена — поднимаю только её, чтобы снять дамп"
  (cd docker && "${COMPOSE[@]}" up -d db)
  for _ in $(seq 1 30); do
    docker exec "$DB_CONTAINER_NAME" pg_isready -U "$DB_USER" -d "$DB_NAME" >/dev/null 2>&1 && break
    sleep 2
  done
fi

docker exec "$DB_CONTAINER_NAME" pg_isready -U "$DB_USER" -d "$DB_NAME" >/dev/null 2>&1 \
  || die "БД не отвечает на pg_isready. Дамп не снят, деплой прерван."

# Без -t: псевдотерминал превращает \n в \r\n, и такой дамп портит данные
# при восстановлении через psql.
docker exec "$DB_CONTAINER_NAME" pg_dump -U "$DB_USER" -d "$DB_NAME" > "$BACKUP_DIR/pps.sql" \
  || die "pg_dump упал. Деплой прерван, ничего не изменено."

DUMP_BYTES=$(wc -c < "$BACKUP_DIR/pps.sql")
[ "$DUMP_BYTES" -ge "$MIN_DUMP_BYTES" ] \
  || die "Дамп подозрительно мал ($DUMP_BYTES байт, порог $MIN_DUMP_BYTES). Деплой прерван, ничего не изменено."
tail -n 5 "$BACKUP_DIR/pps.sql" | grep -q 'PostgreSQL database dump complete' \
  || die "Дамп оборван: нет завершающей строки pg_dump. Деплой прерван, ничего не изменено."
! grep -q $'\r' "$BACKUP_DIR/pps.sql" \
  || die "В дампе есть символы \\r — он испорчен. Деплой прерван, ничего не изменено."
ok "дамп снят и цел: $DUMP_BYTES байт"

[ -d config/jwt ] && cp -a config/jwt "$BACKUP_DIR/jwt" && ok "JWT-ключи сохранены"
cp -a docker/.env "$BACKUP_DIR/docker.env" && ok "docker/.env сохранён"

# --- 2. код ------------------------------------------------------------------

log "2/6 Обновление кода из origin/$BRANCH"

if ! git diff-index --quiet HEAD -- 2>/dev/null; then
  git status --short
  die "В рабочем дереве есть незакоммиченные изменения (список выше). Разберитесь с ними, затем запустите скрипт снова."
fi

git fetch origin "$BRANCH" || die "git fetch не прошёл. Если причина в доступе по SSH, переключите remote на HTTPS:
    git remote set-url origin https://github.com/Baktybekovich1/pps_new.git"

git checkout "$BRANCH"
git merge --ff-only "origin/$BRANCH" || die "Не получился fast-forward: локальная ветка разошлась с origin/$BRANCH."
ok "код на $(git rev-parse --short HEAD)"

# --- 3. вернуть ключи --------------------------------------------------------

log "3/6 Проверка JWT-ключей"
if ! compgen -G 'config/jwt/*.pem' >/dev/null; then
  [ -d "$BACKUP_DIR/jwt" ] || die "JWT-ключей нет ни в репозитории, ни в бэкапе. Авторизация не заработает.
Сгенерировать новую пару (это разлогинит всех):
    docker exec -it $PHP_CONTAINER_NAME php bin/console lexik:jwt:generate-keypair"
  mkdir -p config/jwt
  cp -a "$BACKUP_DIR/jwt/." config/jwt/
  ok "ключи восстановлены из бэкапа (git удалил их: теперь они в .gitignore)"
else
  ok "ключи на месте"
fi

# --- 4. контейнеры -----------------------------------------------------------

log "4/6 Пересборка и пересоздание контейнеров"
(cd docker && "${COMPOSE[@]}" up -d --build)
ok "стек поднят"

if docker inspect "$PROXY_CONTAINER" >/dev/null 2>&1; then
  docker exec "$PROXY_CONTAINER" nginx -t >/dev/null 2>&1 \
    || die "Конфиг прокси '$PROXY_CONTAINER' не проходит nginx -t; прокси не перезагружен, снаружи возможен 502.
Проверьте: docker exec $PROXY_CONTAINER nginx -t"
  docker exec "$PROXY_CONTAINER" nginx -s reload >/dev/null 2>&1 \
    || die "Не удалось перезагрузить прокси '$PROXY_CONTAINER'."
  ok "прокси '$PROXY_CONTAINER' перезагружен (подхватил новый адрес nginx)"
else
  printf '    \033[1;33m!\033[0m контейнер прокси %s не найден — перезагрузку пропускаю\n' "$PROXY_CONTAINER"
fi

# Код смонтирован в контейнер с хоста (/app), поэтому var/cache/prod переживает
# пересборку. В prod Symfony не проверяет свежесть кэша: без очистки изменения
# в config/ (маршруты, сервисы, Doctrine) не применяются. Чистим от www-data,
# чтобы php-fpm мог писать в кэш.
docker exec -u www-data "$PHP_CONTAINER_NAME" php bin/console cache:clear --env=prod --no-debug \
  || die "cache:clear упал — приложение может работать со старой конфигурацией."
ok "кэш Symfony пересобран"

# --- 5. миграции -------------------------------------------------------------

log "5/6 Миграции Doctrine"
printf '    Дамп для откатa: %s/pps.sql\n' "$BACKUP_DIR"
docker exec "$PHP_CONTAINER_NAME" php bin/console doctrine:migrations:migrate --no-interaction
ok "миграции применены"

# --- 6. проверка -------------------------------------------------------------

log "6/6 Проверка"
(cd docker && "${COMPOSE[@]}" ps)

printf '\n    restart-политики и привязка портов:\n'
docker inspect -f '      {{.Name}} restart={{.HostConfig.RestartPolicy.Name}} ports={{.HostConfig.PortBindings}}' \
  "$PHP_CONTAINER_NAME" "$NGINX_CONTAINER_NAME" "$DB_CONTAINER_NAME"

# Запрос идёт с хоста через прокси на 443 — тот же путь, что у пользователей.
printf '\n    ответ https://%s%s через прокси:\n' "$APP_HOST" "$HEALTH_PATH"
CODE=000
for _ in $(seq 1 15); do
  CODE=$(curl -sk -o /dev/null -w '%{http_code}' --max-time 5 \
    --resolve "$APP_HOST:443:127.0.0.1" "https://$APP_HOST$HEALTH_PATH" || true)
  case "$CODE" in 2??|3??) break ;; esac
  sleep 2
done
case "$CODE" in
  2??|3??) ok "HTTP $CODE" ;;
  *) die "Приложение отвечает HTTP $CODE на https://$APP_HOST$HEALTH_PATH.
Логи: docker logs $PROXY_CONTAINER --tail 20; docker logs $NGINX_CONTAINER_NAME --tail 20
Дамп для отката: $BACKUP_DIR/pps.sql" ;;
esac

# Ротация: только папки с именем по умолчанию, текущую не трогаем.
if [ -z "${DEPLOY_BACKUP_DIR:-}" ]; then
  mapfile -t OLD_BACKUPS < <(ls -1dt /root/pps-backup-????-??-??-?????? 2>/dev/null | tail -n +"$((KEEP_DEPLOY_BACKUPS + 1))")
  for d in "${OLD_BACKUPS[@]}"; do
    [ "$d" != "$BACKUP_DIR" ] && rm -rf -- "$d"
  done
  [ "${#OLD_BACKUPS[@]}" -eq 0 ] || ok "удалено старых бэкапов деплоя: ${#OLD_BACKUPS[@]} (храним $KEEP_DEPLOY_BACKUPS)"
fi

log "Готово"
cat <<EOF
    Бэкап: $BACKUP_DIR
      pps.sql      — дамп БД до миграций
      jwt/         — ключи подписи JWT
      docker.env   — docker/.env

    Откат схемы БД, если что-то пошло не так:
      docker exec -i $DB_CONTAINER_NAME psql -U $DB_USER -d $DB_NAME < $BACKUP_DIR/pps.sql

    Снаружи проверяется так:
      curl -I https://api.pps.makalabox.com/
EOF
