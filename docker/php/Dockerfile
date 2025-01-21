FROM php:8.2-fpm
RUN apt-get update

# postgres extension
#RUN apt-get update && \
#    apt-get install -y libpq-dev && \
#    docker-php-ext-install pdo pdo_pgsql pgsql && \
#        apt-get install -y git

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure zip --with-libzip=/usr/include \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /app


RUN useradd -u 1000 -m user

RUN chown user:user /app

USER user



WORKDIR /app