FROM postgres:15



RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       make \
       libpq-dev \
       postgresql-server-dev-15 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
