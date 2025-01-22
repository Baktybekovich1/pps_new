FROM postgres:13

# Install dependencies
RUN apt-get update && apt-get -y -qq --no-install-recommends install \
    curl \
    make \
    libpq-dev && \
    echo "deb http://apt.postgresql.org/pub/repos/apt bullseye-pgdg main" > /etc/apt/sources.list.d/pgdg.list && \
    curl -sL https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg && \
    apt-get update && apt-get -y -qq --no-install-recommends install postgresql-server-dev-13

#RUN apt-get update \
#&& apt-get -y -qq --no-install-recommends install make \
#    libpq-dev \
#    postgresql-server-dev-12
