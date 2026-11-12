
#!/bin/sh

set -e

cd /app

echo "=================================="
echo " Starting Wedding Platform"
echo "=================================="

# Check application key
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is missing!"
    exit 1
fi

echo "APP_KEY found."

# Clear and cache configuration
echo "Preparing Laravel configuration..."

php artisan config:clear
php artisan config:cache

# Run database migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Create storage symbolic link
echo "Creating storage link..."

php artisan storage:link || true

# Fix writable directories
echo "Checking storage permissions..."

chown -R www-data:www-data storage bootstrap/cache

echo "Laravel preparation completed."
echo "Starting FrankenPHP..."

exec "$@"
