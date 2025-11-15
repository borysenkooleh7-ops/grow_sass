#!/bin/bash

#####################################################
# Railway Database Initialization Script
# Run this ONCE after creating MySQL service on Railway
#####################################################

echo "==========================================="
echo "Grow CRM - Railway Database Setup"
echo "==========================================="
echo ""

# Get database credentials from Railway environment
DB_HOST="${MYSQLHOST}"
DB_PORT="${MYSQLPORT}"
DB_USER="${MYSQLUSER}"
DB_PASS="${MYSQLPASSWORD}"
DB_NAME="${MYSQLDATABASE}"

echo "Database Host: ${DB_HOST}"
echo "Database Port: ${DB_PORT}"
echo "Database User: ${DB_USER}"
echo "Database Name: ${DB_NAME}"
echo ""

# Check if landlord database exists, create if not
echo "Creating landlord database..."
mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME}_landlord CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Check if tenant database exists, create if not
echo "Creating tenant database..."
mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME}_tenant1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import landlord schema
echo "Importing landlord schema..."
mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}_landlord" < growcrm_landlord.sql

# Import tenant schema
echo "Importing tenant schema..."
mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}_tenant1" < growsaas-tenant.sql

echo ""
echo "==========================================="
echo "Database setup complete!"
echo "==========================================="
echo ""
echo "Landlord Database: ${DB_NAME}_landlord"
echo "Tenant Database: ${DB_NAME}_tenant1"
echo ""
