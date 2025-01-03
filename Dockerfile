# Gunakan image PHP dengan Apache
FROM php:8.1-apache

# Install ekstensi yang dibutuhkan CodeIgniter
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Copy semua file proyek ke dalam container
COPY . /var/www/html

# Set hak akses
RUN chown -R www-data:www-data /var/www/html

# Aktifkan rewrite module di Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80
