FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libxml2-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app source
COPY . .

# Set permissions
RUN chown -R www-data:www-data var vendor

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80
