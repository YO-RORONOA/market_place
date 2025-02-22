FROM php:8.2-apache

# Enable Apache mod_rewrite (for routing)
RUN a2enmod rewrite

# Set the document root to the public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache configuration to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install required PHP extensions for PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only necessary files to improve build speed
COPY . /var/www/html

# Ensure proper permissions for storage and cache (if using Laravel)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

