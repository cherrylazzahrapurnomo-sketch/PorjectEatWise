
FROM php:8.2-apache

# Install ekstensi PHP untuk database MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan Apache rewrite
RUN a2enmod rewrite

# Tentukan direktori aplikasi
WORKDIR /var/www/html

# Salin source code EatWise
COPY . /var/www/html/

# Buka port HTTP
EXPOSE 80
