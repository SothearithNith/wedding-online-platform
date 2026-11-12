
#!/bin/sh

set -e

cd /app

echo "======================================"
echo "Starting Wedding Online Platform"
echo "======================================"

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

# Clear and rebuild Laravel configuration cache
php artisan config:clear
php artisan config:cache

# Cache routes and views
php artisan route:clear
php artisan view:clear
php artisan view:cache

# Run database migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Recreate storage symlink
php artisan storage:link || true

# Set permissions
chown -R www-data:www-data storage bootstrap/cache

echo "======================================"
echo "Application is ready"
echo "======================================"

exec "$@"
