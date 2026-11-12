
#!/bin/sh

set -e

cd /app

echo "========== LARAVEL STARTUP =========="

echo "Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

echo "APP_KEY exists."

echo "Clearing Laravel configuration..."
php artisan config:clear

echo "Caching Laravel configuration..."
php artisan config:cache

echo "Running migrations..."
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

echo "Creating storage link..."
php artisan storage:link || true

echo "Checking Laravel application..."
php artisan about

echo "Starting FrankenPHP now..."

exec "$@"
