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

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && printf "output_buffering=4096\ndisplay_errors=Off\nlog_errors=On\nerror_log=/dev/stderr\n" > $PHP_INI_DIR/conf.d/zz-app.ini

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

# Copy source files for Tailwind scanning
COPY . .

# Print Node and Vite versions
RUN node --version && npm --version
RUN npm list vite tailwindcss @tailwindcss/vite laravel-vite-plugin

# Build frontend
RUN npm run build

# Inspect generated files
RUN echo "===== VITE BUILD OUTPUT =====" \
    && find public/build -maxdepth 3 -type f -print \
    && echo "===== MANIFEST CHECK =====" \
    && test -s public/build/manifest.json

 
FROM base AS production

WORKDIR /app

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

COPY . .

COPY --from=composer-dependencies /app/vendor ./vendor

# Copy compiled CSS and JS
COPY --from=frontend /app/public/build ./public/build

COPY Caddyfile /etc/caddy/Caddyfile

COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint"]

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
