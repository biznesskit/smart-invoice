# Use official PHP 8.3 FPM image
FROM php:8.3-fpm AS base

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel application
COPY . .

# Install PHP dependencies with optimized autoloading
RUN composer install --optimize-autoloader --no-dev

# Set up environment
COPY .env.example .env

# Set permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


RUN php artisan config:clear && php artisan cache:clear && php artisan route:cache && php artisan view:cache
# Symlink storage (ignore error if it already exists)
RUN php artisan storage:link || true

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy PHP configuration
COPY docker/php.ini /usr/local/etc/php/php.ini


# Copy Supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 8080 (Cloud Run default)
EXPOSE 8080


ENV LOG_CHANNEL=stderr
ENV APP_LOG=errorlog
ENV APP_DEBUG=true
# Start Supervisor to manage Nginx & PHP-FPM

# Set Entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
CMD ["/bin/bash", "/entrypoint.sh"]
# Run Entrypoint
# ENTRYPOINT ["entrypoint.sh"]