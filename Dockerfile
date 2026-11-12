
# PHP 8.4 with FrankenPHP
FROM dunglas/frankenphp:1-php8.4 AS base

WORKDIR /app

# Install Laravel PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    exif \
    opcache

# -------------------------
# Composer dependencies
# -------------------------
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

# -------------------------
# Build frontend assets
# -------------------------
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build

# -------------------------
# Production image
# -------------------------
FROM base AS production

WORKDIR /app

COPY --from=vendor /app /app

COPY --from=frontend /app/public/build /app/public/build

COPY Caddyfile /etc/caddy/Caddyfile

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN chmod +x /usr/local/bin/docker-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint"]

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
