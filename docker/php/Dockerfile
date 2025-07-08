FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN mkdir -p writable/logs writable/cache writable/session
RUN mkdir -p writable/logs/production
RUN mkdir -p writable/logs/development
RUN mkdir -p writable/logs/testing
RUN chmod -R 777 writable/
