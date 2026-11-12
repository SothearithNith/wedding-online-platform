
# ==========================================
# Base: PHP 8.4 + FrankenPHP
# ==========================================
FROM dunglas/frankenphp:1-php8.4 AS base

WORKDIR /app

RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    exif \
    opcache

# ==========================================
# Composer dependencies
# ==========================================
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

COPY . .

RUN composer dump-autoload \
    --no-dev \
    --optimize \
    --no-scripts

# ==========================================
# Frontend build
# ==========================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build

# ==========================================
# Production
# ==========================================
FROM base AS production

WORKDIR /app

COPY --from=vendor /app /app

COPY --from=frontend /app/public/build /app/public/build

COPY Caddyfile /etc/caddy/Caddyfile

COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
