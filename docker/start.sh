#!/usr/bin/env bash
set -e

echo "Starting Grow CRM on Railway..."

# Create required directories FIRST (before any artisan commands)
echo "Creating required directories..."
mkdir -p /var/www/growcrm/updates
mkdir -p /var/www/growcrm/storage/avatars
mkdir -p /var/www/growcrm/storage/logos/clients
mkdir -p /var/www/growcrm/storage/logos/app
mkdir -p /var/www/growcrm/storage/files
mkdir -p /var/www/growcrm/storage/temp
mkdir -p /var/www/growcrm/storage/logs
mkdir -p /var/www/growcrm/application/storage/app/public
mkdir -p /var/www/growcrm/application/storage/app/purifier/HTML
mkdir -p /var/www/growcrm/application/storage/cache/data
mkdir -p /var/www/growcrm/application/storage/debugbar
mkdir -p /var/www/growcrm/application/storage/framework/cache/data
mkdir -p /var/www/growcrm/application/storage/framework/sessions
mkdir -p /var/www/growcrm/application/storage/framework/testing
mkdir -p /var/www/growcrm/application/storage/framework/views
mkdir -p /var/www/growcrm/application/storage/logs

# Fix permissions early
echo "Setting permissions..."
chown -R www-data:www-data /var/www/growcrm
chmod -R 775 /var/www/growcrm/storage || true
chmod -R 775 /var/www/growcrm/application/bootstrap/cache
chmod -R 775 /var/www/growcrm/updates
chmod -R 775 /var/www/growcrm/application/storage

# Change to application directory where artisan is located
cd /var/www/growcrm/application

# Run package discovery (needs environment variables)
echo "Running package discovery..."
php artisan package:discover --ansi || echo "Package discovery completed with warnings"

# Clear any cached config from build
echo "Clearing build cache..."
php artisan config:clear || true
php artisan cache:clear || true

# Wait for database to be ready (with timeout)
echo "Waiting for database..."
MAX_TRIES=30
TRIES=0
until php artisan db:show 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo "Warning: Database connection timeout - continuing anyway"
        break
    fi
    echo "Database is unavailable - sleeping (attempt $TRIES/$MAX_TRIES)"
    sleep 2
done

echo "Database check complete!"

# Import landlord SQL if settings table is empty (first run setup)
echo "Checking if database needs initialization..."

# MySQL connection options - disable SSL for Railway internal network
MYSQL_OPTS="--ssl-mode=DISABLED -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD"

SETTINGS_COUNT=$(mysql $MYSQL_OPTS "$LANDLORD_DB_DATABASE" -N -e "SELECT COUNT(*) FROM settings WHERE settings_id='default'" 2>/dev/null || echo "0")

if [ "$SETTINGS_COUNT" = "0" ] || [ "$SETTINGS_COUNT" = "" ]; then
    echo "Initializing database with landlord SQL..."

    # Import the SQL file
    if [ -f "/var/www/growcrm/growcrm_landlord.sql" ]; then
        mysql $MYSQL_OPTS "$LANDLORD_DB_DATABASE" < /var/www/growcrm/growcrm_landlord.sql
        echo "SQL imported successfully!"

        # Update settings for Railway domain
        RAILWAY_DOMAIN="${RAILWAY_PUBLIC_DOMAIN:-growsass-production.up.railway.app}"
        echo "Updating settings for domain: $RAILWAY_DOMAIN"

        mysql $MYSQL_OPTS "$LANDLORD_DB_DATABASE" -e "
            UPDATE settings SET
                settings_base_domain = '$RAILWAY_DOMAIN',
                settings_frontend_domain = '$RAILWAY_DOMAIN',
                settings_email_domain = '$RAILWAY_DOMAIN',
                settings_company_name = 'Grow CRM',
                settings_purchase_code = 'railway-deployment'
            WHERE settings_id = 'default';
        "

        # Update admin user with known password (password: password)
        mysql $MYSQL_OPTS "$LANDLORD_DB_DATABASE" -e "
            UPDATE users SET
                email = 'admin@example.com',
                password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                first_name = 'Admin',
                last_name = 'User',
                primary_admin = 'yes',
                type = 'admin'
            WHERE id = 1;
        "
        echo "Admin user configured: admin@example.com / password"
    else
        echo "Warning: growcrm_landlord.sql not found, skipping import"
    fi
else
    echo "Database already initialized (settings found)"
fi

# Run database migrations on landlord database
echo "Running database migrations..."
php artisan migrate --database=landlord --path=database/migrations/landlord --force || echo "Migration skipped or failed"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || echo "Storage link already exists"

# Cache configuration (skip route cache due to serialization issues)
echo "Caching configuration..."
php artisan config:cache || echo "Config cache failed"
# Skip route cache - causes serialization errors with install routes
# php artisan route:cache || echo "Route cache failed"
php artisan view:cache || echo "View cache failed"

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "Starting services with Supervisor..."
/usr/bin/supervisord -c /etc/supervisord.conf
