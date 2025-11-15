#!/bin/bash
set -e

echo "Starting Grow CRM on Railway..."

# Wait for database to be ready
echo "Waiting for database..."
until php artisan db:show 2>/dev/null || [ $? -eq 0 ]; do
    echo "Database is unavailable - sleeping"
    sleep 2
done

echo "Database is ready!"

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration skipped or failed"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || echo "Storage link already exists"

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/growcrm
chmod -R 775 /var/www/growcrm/storage
chmod -R 775 /var/www/growcrm/bootstrap/cache

echo "Starting services with Supervisor..."
/usr/bin/supervisord -c /etc/supervisord.conf
