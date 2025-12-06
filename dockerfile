FROM php:8.2-apache

# Установка расширений PHP для PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Включение mod_rewrite для Apache
RUN a2enmod rewrite

# Копирование файлов проекта
COPY . /var/www/html/

# Установка прав
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Настройка Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80