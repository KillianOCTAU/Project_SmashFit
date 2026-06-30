FROM php:8.2-apache

# Dépendances système + extensions PHP nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libzip-dev libpng-dev libonig-dev \
    libxml2-dev libicu-dev libssl-dev \
    && docker-php-ext-install \
        pdo pdo_mysql zip intl opcache mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Active la réécriture d'URL Apache (nécessaire pour Symfony)
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Installe Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80