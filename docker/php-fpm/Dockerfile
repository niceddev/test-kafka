FROM composer:2.5.4 AS composer
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev libfreetype6-dev libjpeg62-turbo-dev \
    libzip-dev libxml2-dev libpq-dev libpng-dev libicu-dev \
    git zip curl librdkafka-dev \
    && pecl install rdkafka \
    && docker-php-ext-install pgsql pdo_pgsql zip soap gd intl bcmath \
    && docker-php-ext-enable rdkafka.so

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG PROJECT_NAME
WORKDIR /var/www/${PROJECT_NAME}

RUN useradd -m ${PROJECT_NAME} \
    && usermod -aG root ${PROJECT_NAME} \
    && chown -R ${PROJECT_NAME}:www-data ./ \
    && find ./ \( -path ./vendor -o -path ./node_modules \) -exec chmod 775 {} \;

