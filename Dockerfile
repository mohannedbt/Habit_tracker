# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory inside the container
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files into the container
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

# Set environment variable to production (or dev for local)
ENV APP_ENV=prod

# Expose port 80
EXPOSE 80

# Run Symfony cache warmup
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && php bin/console cache:clear

# Start Apache
CMD ["apache2-foreground"]
