FROM php:7.4-apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Aktifkan mod_rewrite (jika kamu butuh .htaccess)
RUN a2enmod rewrite

# Copy source code jika mau langsung masukin dari host (opsional)
# COPY ./program/bengkel/ /var/www/html/

