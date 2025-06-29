# Use PHP 8.2 with Apache
FROM php:8.2-apache

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

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Set Apache DocumentRoot to Symfony's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Set environment variables for Symfony and Composer
ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies and prepare Symfony for production
RUN composer install --no-scripts --no-interaction --optimize-autoloader --no-dev \
 && echo "✅ Composer install done" \
 && php bin/console importmap:install \
 && echo "✅ importmap:install done" \
 && php bin/console cache:clear --env=prod \
 && echo "✅ cache:clear done" \
 && php bin/console cache:warmup --env=prod \
 && echo "✅ cache:warmup done" \
 && php bin/console importmap:dump \
 && echo "✅ importmap:dump done" \
 && mkdir -p var vendor \
 && chown -R www-data:www-data var vendor


# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
