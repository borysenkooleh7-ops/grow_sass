#!/bin/bash

#####################################################
# Grow CRM - Status Check Script
# Quickly check the health of your deployment
#####################################################

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}=========================================="
echo "Grow CRM - System Status Check"
echo "==========================================${NC}"
echo ""

# Function to check service status
check_service() {
    if systemctl is-active --quiet $1; then
        echo -e "${GREEN}✓${NC} $1 is running"
    else
        echo -e "${RED}✗${NC} $1 is NOT running"
    fi
}

# Function to check port
check_port() {
    if netstat -tuln 2>/dev/null | grep -q ":$1 "; then
        echo -e "${GREEN}✓${NC} Port $1 is listening"
    else
        echo -e "${RED}✗${NC} Port $1 is NOT listening"
    fi
}

echo -e "${YELLOW}System Services:${NC}"
check_service nginx
check_service php8.2-fpm
check_service mysql
check_service redis-server
check_service supervisor
echo ""

echo -e "${YELLOW}Network Ports:${NC}"
check_port 80
check_port 443
check_port 3306
check_port 6379
echo ""

echo -e "${YELLOW}PHP Version:${NC}"
php -v | head -n 1
echo ""

echo -e "${YELLOW}Database Connection:${NC}"
if mysql -u growcrm_user -p'GrowCRM_Secure_Pass_2024!' -e "SHOW DATABASES;" 2>/dev/null | grep -q "growcrm_landlord"; then
    echo -e "${GREEN}✓${NC} Database connection successful"
    mysql -u growcrm_user -p'GrowCRM_Secure_Pass_2024!' -e "SHOW DATABASES;" 2>/dev/null | grep "growcrm"
else
    echo -e "${RED}✗${NC} Database connection failed"
fi
echo ""

echo -e "${YELLOW}Queue Workers:${NC}"
if [ -f /etc/supervisor/conf.d/growcrm-worker.conf ]; then
    sudo supervisorctl status | grep growcrm
else
    echo -e "${RED}✗${NC} Queue workers not configured"
fi
echo ""

echo -e "${YELLOW}Disk Space:${NC}"
df -h /var/www/growcrm 2>/dev/null | tail -n 1 || echo "Application not deployed yet"
echo ""

echo -e "${YELLOW}Recent Application Logs (last 10 lines):${NC}"
if [ -f /var/www/growcrm/application/storage/logs/laravel.log ]; then
    tail -n 10 /var/www/growcrm/application/storage/logs/laravel.log
else
    echo "No logs found yet"
fi
echo ""

echo -e "${YELLOW}Recent Nginx Errors (last 5 lines):${NC}"
if [ -f /var/log/nginx/growcrm-error.log ]; then
    sudo tail -n 5 /var/log/nginx/growcrm-error.log
else
    echo "No Nginx logs found yet"
fi
echo ""

echo -e "${YELLOW}Application Files:${NC}"
if [ -d /var/www/growcrm ]; then
    echo -e "${GREEN}✓${NC} Application deployed at /var/www/growcrm"
    ls -lh /var/www/growcrm/ | head -n 5
else
    echo -e "${RED}✗${NC} Application not deployed yet"
fi
echo ""

echo -e "${YELLOW}Environment Configuration:${NC}"
if [ -f /var/www/growcrm/application/.env ]; then
    echo -e "${GREEN}✓${NC} .env file exists"
    grep "APP_URL" /var/www/growcrm/application/.env 2>/dev/null
    grep "APP_ENV" /var/www/growcrm/application/.env 2>/dev/null
    grep "APP_DEBUG" /var/www/growcrm/application/.env 2>/dev/null
else
    echo -e "${RED}✗${NC} .env file not found"
fi
echo ""

echo -e "${YELLOW}Storage Permissions:${NC}"
if [ -d /var/www/growcrm/storage ]; then
    ls -ld /var/www/growcrm/storage | awk '{print $1, $3, $4, $9}'
    ls -ld /var/www/growcrm/application/storage | awk '{print $1, $3, $4, $9}'
else
    echo "Storage directory not found"
fi
echo ""

echo -e "${BLUE}=========================================="
echo "Status Check Complete"
echo "==========================================${NC}"
echo ""

SERVER_IP=$(hostname -I | awk '{print $1}')
echo -e "Access your application at:"
echo -e "${GREEN}http://localhost${NC}"
echo -e "${GREEN}http://${SERVER_IP}${NC}"
echo ""
