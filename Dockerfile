FROM php:8.4-alpine AS php

RUN apk update && apk add openssl zip unzip git gmp-dev pkgconf $PHPIZE_DEPS
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install mongodb \
    && docker-php-ext-install pdo gmp pcntl \
    && docker-php-ext-enable mongodb

WORKDIR /app

FROM php AS laravel

COPY . /app
RUN composer install

FROM node:22-alpine AS js

WORKDIR /app
COPY . /app

RUN npm ci && npm run build

FROM laravel

COPY --from=js /app/public /app/public

COPY --from=laravel /app/.env.example /app/.env

ENTRYPOINT [ "sh", "./docker/app.sh" ]
