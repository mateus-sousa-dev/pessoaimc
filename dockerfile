FROM composer:2.0 as vendor
COPY ./ /app

RUN docker-php-ext-install sockets \
    && apk update \
    && apk add zip \
    && apk add --no-cache  $PHPIZE_DEPS \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN composer install

FROM php:7.4-apache
RUN a2enmod rewrite
COPY ./ /var/www/html
COPY --from=vendor /app /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN apt update \
    && apt install -y libcurl4-openssl-dev pkg-config libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install sockets
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv /var/www/html/.env.example /var/www/html/.env
RUN chmod -R 777 /var/www/html/storage
