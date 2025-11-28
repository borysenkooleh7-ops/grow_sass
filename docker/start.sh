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

# Use PHP to check and import SQL (avoids MariaDB client compatibility issues with MySQL 8)
RAILWAY_DOMAIN="${RAILWAY_PUBLIC_DOMAIN:-growsass-production.up.railway.app}"

php -r "
\$host = getenv('DB_HOST');
\$port = getenv('DB_PORT');
\$user = getenv('DB_USERNAME');
\$pass = getenv('DB_PASSWORD');
\$db = getenv('LANDLORD_DB_DATABASE');
\$domain = '$RAILWAY_DOMAIN';

try {
    \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if settings table exists and has data
    \$result = \$pdo->query(\"SELECT COUNT(*) FROM settings WHERE settings_id='default'\");
    \$count = \$result->fetchColumn();

    if (\$count > 0) {
        echo \"Database already initialized (settings found)\n\";
        exit(0);
    }
} catch (PDOException \$e) {
    // Table doesn't exist, need to import
    echo \"Settings table not found, will import SQL\n\";
}

// Import SQL file
\$sqlFile = '/var/www/growcrm/growcrm_landlord.sql';
if (!file_exists(\$sqlFile)) {
    echo \"Warning: growcrm_landlord.sql not found, skipping import\n\";
    exit(0);
}

echo \"Initializing database with landlord SQL...\n\";

try {
    \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Disable strict mode to allow '0000-00-00 00:00:00' datetime values
    \$pdo->exec(\"SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'\");

    // Read and execute SQL file
    \$sql = file_get_contents(\$sqlFile);
    \$pdo->exec(\$sql);
    echo \"SQL imported successfully!\n\";

    // Update settings for Railway domain
    echo \"Updating settings for domain: \$domain\n\";
    \$stmt = \$pdo->prepare(\"UPDATE settings SET
        settings_base_domain = ?,
        settings_frontend_domain = ?,
        settings_email_domain = ?,
        settings_company_name = 'Grow CRM',
        settings_purchase_code = 'railway-deployment'
        WHERE settings_id = 'default'\");
    \$stmt->execute([\$domain, \$domain, \$domain]);

    // Update admin user with known password (password: password)
    \$hashedPassword = '\\\$2y\\\$10\\\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    \$stmt = \$pdo->prepare(\"UPDATE users SET
        email = 'admin@example.com',
        password = ?,
        first_name = 'Admin',
        last_name = 'User',
        primary_admin = 'yes',
        type = 'admin'
        WHERE id = 1\");
    \$stmt->execute([\$hashedPassword]);

    echo \"Admin user configured: admin@example.com / password\n\";

} catch (PDOException \$e) {
    echo \"Database error: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Skip migrations - SQL import already creates all tables
# php artisan migrate --database=landlord --path=database/migrations/landlord --force || echo "Migration skipped or failed"
echo "Skipping migrations (SQL import already created all tables)"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || echo "Storage link already exists"

# DO NOT cache configuration - env() calls need to work at runtime for SETUP_STATUS check
# php artisan config:cache || echo "Config cache failed"
echo "Skipping config cache (env() calls need to work at runtime)"

# Clear any existing config cache to ensure env() works
php artisan config:clear || true

# Skip route cache - causes serialization errors with install routes
# php artisan route:cache || echo "Route cache failed"

# Cache views only
php artisan view:cache || echo "View cache failed"

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "Starting services with Supervisor..."
/usr/bin/supervisord -c /etc/supervisord.conf
