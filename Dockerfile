FROM php:8.2-apache

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd opcache

# Enable Apache rewrite
RUN a2enmod rewrite

# Set correct document root for Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-scripts \
 && mkdir -p var \
 && chown -R www-data:www-data var vendor

# Expose Apache
EXPOSE 80
