# Multi-stage build for Grow CRM on Railway
# Build timestamp: 2024-11-19-v2 - Force rebuild
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    curl \
    git \
    unzip \
    bash \
    openssl-dev \
    imagemagick-dev \
    imagemagick \
    autoconf \
    g++ \
    make \
    icu-dev \
    icu-libs \
    icu-data-full \
    imap-dev \
    krb5-dev

# Note: Node.js not needed - assets are pre-compiled

# Configure PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Configure IMAP with Kerberos and SSL support
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl

# Install PHP extensions one by one for better error handling
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) exif
RUN docker-php-ext-install -j$(nproc) pcntl
RUN docker-php-ext-install -j$(nproc) bcmath
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) xml
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) soap
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) imap

# Install Redis extension
RUN pecl install redis imagick \
    && docker-php-ext-enable redis imagick

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/growcrm

# Copy application files
COPY . .

# Create a temporary .env file for composer scripts in application folder
RUN touch application/.env && \
    echo "APP_KEY=base64:temporary_key_for_build_only_replace_me=" >> application/.env

# Create required directories
RUN mkdir -p application/storage/logs application/storage/framework/cache application/storage/framework/sessions application/storage/framework/views application/bootstrap/cache

# Set permissions before composer install
RUN chmod -R 775 application/storage application/bootstrap/cache

# Install PHP dependencies in application folder (skip scripts that need database)
WORKDIR /var/www/growcrm/application
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Run autoload dump without scripts (package:discover runs at startup)
RUN composer dump-autoload --optimize --no-scripts

# Go back to root directory
WORKDIR /var/www/growcrm

# Note: Assets are pre-compiled in /public directory
# Skip npm build - no webpack.mix.js configuration exists
# If future builds are needed, create webpack.mix.js first

# Remove temporary .env (will be provided by Railway environment variables)
RUN rm -f application/.env

# Set permissions
RUN chown -R www-data:www-data /var/www/growcrm \
    && chmod -R 775 application/storage \
    && chmod -R 775 application/bootstrap/cache

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/site.conf /etc/nginx/http.d/default.conf

# Configure PHP-FPM
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

# Create startup script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Expose port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s \
    CMD curl -f http://localhost:8080/ || exit 1

# Start services
CMD ["/start.sh"]
