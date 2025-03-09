FROM php:8.2-apache

# Enable Apache mod_rewrite (for routing)
RUN a2enmod rewrite

# Set the document root to the public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache configuration to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Install system dependencies (zip, unzip, git) and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    gnupg2 \
    lsb-release \
    apt-transport-https

# Install PostgreSQL PDO extension
RUN docker-php-ext-install pdo_pgsql

# Install Node.js (version 18 or 20)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first to cache dependencies
COPY composer.json composer.lock /var/www/html/

# Install PHP dependencies (like PHPMailer)
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application code
COPY . /var/www/html

# Ensure proper permissions for storage and cache (if using Laravel)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
