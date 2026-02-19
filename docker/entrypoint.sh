#!/bin/sh
set -e

echo "🚀 Starting MegaLearning..."

# Wait for MySQL to be ready
echo "⏳ Waiting for database..."
MAX_RETRIES=30
RETRY=0
until php artisan db:monitor --databases=mysql > /dev/null 2>&1 || [ $RETRY -eq $MAX_RETRIES ]; do
    RETRY=$((RETRY + 1))
    echo "  Attempt $RETRY/$MAX_RETRIES..."
    sleep 2
done

if [ $RETRY -eq $MAX_RETRIES ]; then
    echo "⚠️  Database not ready, continuing anyway..."
fi

# Run migrations
echo "📦 Running migrations..."
php artisan migrate --force

# Cache configuration for performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Starting PHP-FPM and Nginx..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
