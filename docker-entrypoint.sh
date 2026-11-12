
#!/bin/sh

set -e

cd /app

echo "Starting Wedding Online Platform..."

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

php artisan config:clear
php artisan config:cache

php artisan route:clear
php artisan view:clear
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link || true

chown -R www-data:www-data storage bootstrap/cache

echo "Application is ready!"

exec "$@"
