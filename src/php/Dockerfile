FROM php:8.3-apache

COPY . /var/www/html 

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql
# tem a mesma função do comando para o mysql, só que adaptado para PostgreSQL

WORKDIR /var/www/html