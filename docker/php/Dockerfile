FROM php:8.2-fpm


RUN apt-get update && apt-get install -y bash libzip-dev libpng-dev libcurl4-openssl-dev zip unzip


RUN docker-php-ext-install pdo pdo_mysql mysqli zip gd curl

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www || true

EXPOSE 9000
CMD ["php-fpm"]