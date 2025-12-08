# Multi-stage build for Laravel + Vite

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --progress=false
COPY resources resources
COPY public public
COPY vite.config.js ./
RUN npm run build

FROM php:8.2-fpm-alpine AS app
WORKDIR /var/www/html

# INSTALL DEPENDENCIES (Perhatikan baris ini berubah)
# Kita install library runtime (tanpa -dev) dan biarkan tetap ada
RUN apk add --no-cache \
    bash git \
    libpng libjpeg-turbo freetype icu-libs libzip \
    icu-dev libzip-dev oniguruma-dev zlib-dev libpng-dev freetype-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql intl zip gd bcmath \
    && apk del icu-dev libzip-dev oniguruma-dev zlib-dev libpng-dev freetype-dev libjpeg-turbo-dev

COPY . .
COPY --from=vendor /app/vendor ./vendor
# Pastikan baris copy composer ini tetap ada (dari perbaikan sebelumnya)
COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY --from=frontend /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
