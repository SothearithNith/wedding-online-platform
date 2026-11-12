
#!/bin/sh

set -e

cd /app

echo "Checking Laravel environment..."

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

echo "Clearing old Laravel configuration..."

php artisan config:clear

echo "Caching Laravel configuration..."

php artisan config:cache

echo "Running database migrations..."

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

echo "Creating storage link..."

php artisan storage:link || true

echo "Starting FrankenPHP..."

exec "$@"
