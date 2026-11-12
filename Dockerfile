FROM dunglas/frankenphp:1-php8.4 AS base

RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    exif \
    opcache

WORKDIR /app

FROM base AS composer-dependencies

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build

# Verify Vite generated its production manifest
RUN echo "Checking production assets..." \
    && ls -lah public/build \
    && test -s public/build/manifest.json

FROM base AS production

WORKDIR /app

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

COPY . .
 
COPY --from=composer-dependencies /app/vendor ./vendor

# Copy compiled Vite CSS and JS
COPY --from=frontend /app/public/build ./public/build

# Copy Caddy configuration
COPY Caddyfile /etc/caddy/Caddyfile

# Copy startup script
COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint

# Prepare Laravel directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint"]

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
