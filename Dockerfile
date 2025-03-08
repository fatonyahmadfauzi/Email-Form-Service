# Gunakan image PHP resmi
FROM php:8.1-apache

# Install Composer
RUN apt-get update && apt-get install -y git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Salin file proyek ke dalam container
COPY . /var/www/html/

# Set direktori kerja
WORKDIR /var/www/html/

# Install dependensi dengan Composer
RUN composer install

# Berikan izin pada folder
RUN chmod -R 775 /var/www/html

# Expose port 80 untuk HTTP
EXPOSE 80
