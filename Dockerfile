FROM php:8.1.0-fpm

RUN apt-get update && apt-get install -y git unzip zip zlib1g-dev libicu-dev libpng-dev libzip-dev
RUN docker-php-ext-install gd zip intl pdo pdo_mysql bcmath opcache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

