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
    c-client \
    openssl-dev \
    imap-dev \
    imagemagick-dev \
    imagemagick \
    autoconf \
    g++ \
    make \
    icu-dev \
    icu-libs \
    icu-data-full

# Configure PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure imap --with-imap --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip \
    soap \
    intl \
    imap

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
