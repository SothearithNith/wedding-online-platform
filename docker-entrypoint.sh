
#!/bin/sh

set -e

cd /app

echo "======================================"
echo "Starting Wedding Online Platform"
echo "======================================"

# Check application key
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

# Clear and rebuild configuration cache
php artisan config:clear
php artisan config:cache

# Clear and rebuild route and view caches
php artisan route:clear
php artisan view:clear
php artisan view:cache

# Check Vite manifest
if [ ! -s public/build/manifest.json ]; then
    echo "ERROR: Vite manifest is missing!"
    exit 1
fi

echo "Vite production assets found."

# Run migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Storage symlink
php artisan storage:link || true

# Permissions
chown -R www-data:www-data storage bootstrap/cache

echo "Application is ready!"

exec "$@"
