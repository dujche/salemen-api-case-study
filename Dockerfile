FROM php:7.4-apache AS development

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zip \
        git \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-configure sockets \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

FROM development AS seller-api

COPY ./seller/ /var/www/html/

RUN composer install --no-dev && composer dump-autoload

CMD apachectl -D FOREGROUND

FROM development AS contact-api

COPY ./contact/ /var/www/html/

RUN composer install --no-dev && composer dump-autoload

CMD apachectl -D FOREGROUND

FROM development AS sale-api

COPY ./sale/ /var/www/html/

RUN composer install --no-dev && composer dump-autoload

CMD apachectl -D FOREGROUND

FROM development AS parser-api

COPY ./csv-parser/ /var/www/html/

RUN composer install --no-dev && composer dump-autoload

CMD apachectl -D FOREGROUND