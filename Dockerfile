# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set environment variables
ENV APP_ENV=prod
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy the project files
COPY . .

# Install dependencies without scripts (fixes symfony-cmd not found)
RUN composer install --no-scripts --no-interaction --optimize-autoloader --no-dev

# Clear and warm up the cache
RUN php bin/console cache:clear --env=prod \
    && php bin/console cache:warmup --env=prod

# Ensure necessary directories exist and set permissions
RUN mkdir -p var vendor \
    && chown -R www-data:www-data var vendor

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
