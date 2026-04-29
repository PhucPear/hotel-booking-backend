FROM php:8.2-fpm

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    autoconf gcc make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get remove -y autoconf gcc make \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# opcache config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www