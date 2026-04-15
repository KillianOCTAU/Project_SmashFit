FROM php:8.3-apache


RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip opcache


RUN a2enmod rewrite


ENV APACHE_DOCUMENT_ROOT /var/www/symfony/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf


RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony