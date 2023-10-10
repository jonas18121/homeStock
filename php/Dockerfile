FROM php:7.4-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev sudo;

RUN set -xe \
    && docker-php-ext-configure \
		intl \
    && docker-php-ext-install \
		intl \
		zip \
		pdo \
		pdo_mysql \
		gd \
		bcmath \
		xml

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# Composer
COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN mkdir -p /var/www/.composer && chown -R www-data:www-data /var/www/.composer && chmod 777 -R /var/www/.composer


RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu

# COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

RUN usermod -u 1000 www-data

# Installation de Docker Compose
RUN apt-get install -y docker-compose

# Configuration de sudo pour permettre aux utilisateurs de l'utiliser sans mot de passe (optionnel, à des fins de démonstration uniquement)
RUN echo 'ALL ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

WORKDIR /var/www/app

