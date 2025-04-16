# Use official PHP with Apache
FROM php:8.2-apache

# Install mysqli extension for MySQL
RUN docker-php-ext-install mysqli

# Copy your app files to Apache's web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
