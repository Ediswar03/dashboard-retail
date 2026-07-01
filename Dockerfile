# Gunakan image resmi PHP dengan Apache
FROM php:8.4-apache

# Instal dependensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Bersihkan cache manager
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instal ektensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Aktifkan mod_rewrite Apache (wajib untuk routing Laravel)
RUN a2enmod rewrite

# Instal Composer secara global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur working directory di dalam container
WORKDIR /var/www/html

# Salin semua file proyek ke dalam container
COPY . .

# Berikan hak akses untuk folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Atur DocumentRoot Apache agar mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instal dependensi Laravel (mengabaikan paket dev untuk production)
RUN composer install --optimize-autoloader --no-dev

# Koyeb secara default akan mencari layanan di port 8000
# Ubah port mendengarkan Apache dari 80 ke 8000
RUN sed -i 's/80/8000/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Ekspos port
EXPOSE 8000

# Jalankan Apache di background
CMD ["apache2-foreground"]
