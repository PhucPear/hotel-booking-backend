FROM php:8.2-fpm

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache

# opcache config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www