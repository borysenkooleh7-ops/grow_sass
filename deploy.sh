#!/bin/bash

#####################################################
# Grow CRM - Ubuntu Deployment Script
# This script automates the deployment process
#####################################################

set -e  # Exit on any error

echo "=========================================="
echo "Grow CRM Deployment Script"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/home/snake/my project/grow_sass"
WEB_DIR="/var/www/growcrm"
DB_USER="growcrm_user"
DB_PASS="GrowCRM_Secure_Pass_2024!"
LANDLORD_DB="growcrm_landlord"
TENANT_DB="growcrm_tenant1"

echo -e "${YELLOW}Step 1: Updating system packages...${NC}"
sudo apt update
sudo apt upgrade -y

echo -e "${YELLOW}Step 2: Adding PHP repository...${NC}"
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

echo -e "${YELLOW}Step 3: Installing PHP 8.2 and extensions...${NC}"
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml \
  php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
  php8.2-intl php8.2-soap php8.2-imap php8.2-imagick php8.2-redis \
  php8.2-dom php8.2-fileinfo

echo -e "${YELLOW}Step 4: Installing MySQL server...${NC}"
sudo apt install -y mysql-server

echo -e "${YELLOW}Step 5: Installing Nginx...${NC}"
sudo apt install -y nginx

echo -e "${YELLOW}Step 6: Installing Composer...${NC}"
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
else
    echo "Composer already installed"
fi

echo -e "${YELLOW}Step 7: Installing Redis...${NC}"
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server

echo -e "${YELLOW}Step 8: Installing Supervisor...${NC}"
sudo apt install -y supervisor

echo -e "${YELLOW}Step 9: Creating MySQL databases...${NC}"
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${LANDLORD_DB} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${TENANT_DB} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${LANDLORD_DB}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${TENANT_DB}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "GRANT ALL PRIVILEGES ON \`growcrm_%\`.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

echo -e "${YELLOW}Step 10: Importing database dumps...${NC}"
sudo mysql ${LANDLORD_DB} < "${PROJECT_DIR}/growcrm_landlord.sql"
sudo mysql ${TENANT_DB} < "${PROJECT_DIR}/growsaas-tenant.sql"

echo -e "${YELLOW}Step 11: Creating web directory...${NC}"
sudo mkdir -p ${WEB_DIR}
sudo cp -r "${PROJECT_DIR}"/* ${WEB_DIR}/
sudo chown -R www-data:www-data ${WEB_DIR}

echo -e "${YELLOW}Step 12: Creating .env file...${NC}"
cd ${WEB_DIR}/application
if [ ! -f .env ]; then
    cat > .env.tmp << 'ENVFILE'
APP_NAME="Grow CRM"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=tenant
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=growcrm_user
DB_PASSWORD=GrowCRM_Secure_Pass_2024!

LANDLORD_DB_DATABASE=growcrm_landlord

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@localhost
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_KEY=
STRIPE_SECRET=

TENANCY_DETECTION=domain
ENVFILE
    sudo mv .env.tmp .env
    sudo chown www-data:www-data .env
fi

echo -e "${YELLOW}Step 13: Installing Composer dependencies...${NC}"
cd ${WEB_DIR}/application
sudo -u www-data composer install --no-dev --optimize-autoloader

echo -e "${YELLOW}Step 14: Generating application key...${NC}"
sudo -u www-data php artisan key:generate --force

echo -e "${YELLOW}Step 15: Installing NPM dependencies and building assets...${NC}"
cd ${WEB_DIR}/application
sudo -u www-data npm install
sudo -u www-data npm run production

echo -e "${YELLOW}Step 16: Setting permissions...${NC}"
sudo chown -R www-data:www-data ${WEB_DIR}
sudo find ${WEB_DIR} -type d -exec chmod 755 {} \;
sudo find ${WEB_DIR} -type f -exec chmod 644 {} \;
sudo chmod -R 775 ${WEB_DIR}/storage
sudo chmod -R 775 ${WEB_DIR}/application/storage
sudo chmod -R 775 ${WEB_DIR}/application/bootstrap/cache

echo -e "${YELLOW}Step 17: Creating storage symlink...${NC}"
cd ${WEB_DIR}/application
sudo -u www-data php artisan storage:link

echo -e "${YELLOW}Step 18: Configuring Nginx...${NC}"
sudo tee /etc/nginx/sites-available/growcrm > /dev/null << 'NGINXCONF'
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    server_name _;

    root /var/www/growcrm;
    index index.php index.html;

    access_log /var/log/nginx/growcrm-access.log;
    error_log /var/log/nginx/growcrm-error.log;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINXCONF

sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -sf /etc/nginx/sites-available/growcrm /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

echo -e "${YELLOW}Step 19: Configuring queue workers...${NC}"
sudo tee /etc/supervisor/conf.d/growcrm-worker.conf > /dev/null << 'SUPERVISORCONF'
[program:growcrm-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/growcrm/application/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/growcrm/storage/logs/worker.log
stopwaitsecs=3600
SUPERVISORCONF

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start growcrm-worker:*

echo -e "${YELLOW}Step 20: Setting up Laravel scheduler...${NC}"
(sudo crontab -u www-data -l 2>/dev/null; echo "* * * * * /usr/bin/php /var/www/growcrm/application/artisan schedule:run >> /dev/null 2>&1") | sudo crontab -u www-data -

echo -e "${YELLOW}Step 21: Optimizing application...${NC}"
cd ${WEB_DIR}/application
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

echo -e "${YELLOW}Step 22: Configuring firewall...${NC}"
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
echo "y" | sudo ufw enable || true

echo ""
echo -e "${GREEN}=========================================="
echo "Deployment Complete!"
echo "==========================================${NC}"
echo ""
echo -e "Application URL: ${GREEN}http://localhost${NC}"
echo -e "Or: ${GREEN}http://$(hostname -I | awk '{print $1}')${NC}"
echo ""
echo "Database Credentials:"
echo "  Landlord DB: ${LANDLORD_DB}"
echo "  Tenant DB: ${TENANT_DB}"
echo "  Username: ${DB_USER}"
echo "  Password: ${DB_PASS}"
echo ""
echo "Important Next Steps:"
echo "1. Configure your domain name in .env (APP_URL)"
echo "2. Update Nginx configuration for your domain"
echo "3. Setup SSL certificate with Let's Encrypt"
echo "4. Configure email settings in .env"
echo "5. Configure payment gateways (Stripe, etc.)"
echo ""
echo -e "${YELLOW}Check logs:${NC}"
echo "  Application: tail -f /var/www/growcrm/application/storage/logs/laravel.log"
echo "  Nginx: tail -f /var/log/nginx/growcrm-error.log"
echo "  Workers: tail -f /var/www/growcrm/storage/logs/worker.log"
echo ""
