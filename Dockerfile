# Node stage for frontend build
FROM node:22-alpine AS frontend-builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY . .
RUN npm run build


# Composer stage
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock* ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts


# PHP-FPM runtime
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    && docker-php-ext-install opcache

RUN echo 'opcache.enable=1' > /usr/local/etc/php/conf.d/opcache.ini && \
    echo 'opcache.revalidate_freq=0' >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo 'opcache.memory_consumption=256' >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

COPY . .

COPY --from=composer-builder /app/vendor ./vendor

COPY --from=frontend-builder /app/public/build ./public/build

# Laravel writable directories
RUN mkdir -p \
    storage/logs \
    storage/app \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    bootstrap/cache \
    /tmp \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 1777 /tmp

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
