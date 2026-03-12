FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install zip pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app