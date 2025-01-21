FROM postgres:14

# Обновление списка пакетов и установка необходимых зависимостей
RUN apt-get update \
    && apt-get -y -qq --no-install-recommends install make \
    libpq-dev \
    postgresql-server-dev-14