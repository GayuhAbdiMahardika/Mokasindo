###############
# Dependencies
###############
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-ansi \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --progress=false
COPY resources resources
COPY public public
COPY vite.config.js ./
RUN npm run build

###############
# Runtime
###############
FROM php:8.2-fpm-alpine AS app
WORKDIR /var/www/html

# Runtime libs + build deps (removed after compile)
RUN apk add --no-cache \
    bash \
    libpng libjpeg-turbo freetype icu-libs libzip oniguruma \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libpng-dev libjpeg-turbo-dev freetype-dev icu-dev libzip-dev oniguruma-dev zlib-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql intl zip gd bcmath \
    && apk del .build-deps

# Copy source and built artifacts
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

# Ensure writable dirs
RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
