FROM php:8.3-apache

# install extension penting
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# aktifin rewrite
RUN a2enmod rewrite

# copy project
COPY . /var/www/html

# ubah document root ke public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install dependency laravel
RUN composer install --no-dev --optimize-autoloader

# permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
