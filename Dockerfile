FROM php:8.2-apache

# Install PHP extensions and system dependencies

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Symfony public directory as DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow composer as root (Render uses root)
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (including symfony/runtime)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Install frontend assets
RUN php bin/console importmap:install
# Create necessary directories and fix permissions
RUN mkdir -p var && chown -R www-data:www-data var vendor

# Expose Apache port
EXPOSE 80
