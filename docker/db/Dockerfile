FROM debian:buster


#RUN apt-get update \
#  && apt-get -y -qq --no-install-recommends install make \
#  libpq-dev \
#  postgresql-server-dev-all

RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ buster-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
  && curl -sSL https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - \
  && apt-get update \
  && apt-get -y -qq --no-install-recommends install make \
    libpq-dev \
    postgresql-server-dev-all