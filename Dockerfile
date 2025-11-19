# Multi-stage build for Grow CRM on Railway
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    nodejs \
    npm \
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

# Copy application folder to root
RUN cp -r application/* . || true

# Install PHP dependencies
RUN cd /var/www/growcrm && \
    composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN cd /var/www/growcrm && \
    npm install && \
    npm run production

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
