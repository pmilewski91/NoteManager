FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql 

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www/html


EXPOSE 80