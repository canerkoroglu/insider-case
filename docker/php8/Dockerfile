FROM php:8.2-fpm

RUN apt-get -y update
RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer
RUN apt-get install -y git mc
RUN docker-php-ext-install pdo_mysql mysqli
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug-3.3.0 && docker-php-ext-enable xdebug
RUN echo "date.timezone=Europe/Istanbul" >> /usr/local/etc/php/conf.d/timezone.ini
WORKDIR /var/www
RUN php artisan storage:link