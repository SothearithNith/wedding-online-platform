
#!/bin/sh

set -e

cd /app

echo "========== LARAVEL STARTUP =========="

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

echo "APP_KEY exists."

php artisan config:clear
php artisan config:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link || true

echo "Starting FrankenPHP..."

exec "$@"
