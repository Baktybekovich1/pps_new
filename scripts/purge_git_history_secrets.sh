#!/usr/bin/env bash
set -euo pipefail

echo "=== ВНИМАНИЕ: Данный скрипт переписывает историю Git! ==="
echo "Все хэши коммитов изменятся. Потребуется git push --force и свежий git clone у всех разработчиков."
echo "Запускайте только после согласования с владельцем репозитория."
echo ""
read -p "Вы уверены, что хотите продолжить? (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Отменено пользователем."
    exit 1
fi

if ! command -v git-filter-repo &> /dev/null; then
    echo "Ошибка: git-filter-repo не найден. Установите: pip install git-filter-repo или sudo apt install git-filter-repo"
    exit 1
fi

# Удаление скомпрометированных файлов из всей истории репозитория
git filter-repo \
  --path config/jwt/private.pem \
  --path config/jwt/public.pem \
  --path docker/certbot \
  --invert-paths

echo "=== История git успешно очищена от файлов секретов ==="
echo "Для отправки в удалённый репозиторий выполните:"
echo "  git push origin --force --all"
echo "  git push origin --force --tags"
