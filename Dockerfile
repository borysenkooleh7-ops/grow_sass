# Multi-stage build for Grow CRM on Railway
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
    krb5-dev \
    python3 \
    py3-pip \
    linux-headers \
    libc6-compat

# Install Node.js 16 (compatible with node-sass 4.14.1)
RUN apk add --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/v3.16/main/ nodejs=16.20.2-r0 npm=8.10.0-r0 || \
    (curl -fsSL https://unofficial-builds.nodejs.org/download/release/v16.20.2/node-v16.20.2-linux-x64-musl.tar.gz | tar -xz -C /usr/local --strip-components=1)

# Setup Python for node-gyp (required by node-sass)
RUN ln -sf /usr/bin/python3 /usr/bin/python

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

# Copy application folder to root and setup
RUN cp -r application/* . || true

# Create a temporary .env file for composer scripts
RUN touch .env && \
    echo "APP_KEY=base64:temporary_key_for_build_only_replace_me=" >> .env

# Create required directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

# Set permissions before composer install
RUN chmod -R 775 storage bootstrap/cache

# Install PHP dependencies (skip scripts that need database)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Run autoload dump without scripts (package:discover runs at startup)
RUN composer dump-autoload --optimize --no-scripts

# Install Node dependencies and build assets
RUN npm install && npm run production

# Remove temporary .env (will be provided by Railway environment variables)
RUN rm -f .env

# Set permissions
RUN chown -R www-data:www-data /var/www/growcrm \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

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
