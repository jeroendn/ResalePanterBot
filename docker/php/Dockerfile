FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    mc \
    # telnet
    busybox-extras \
    tzdata \
    cronie \
    gmp \
    gmp-dev

RUN docker-php-ext-install \
   gmp

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

COPY ./crontab /etc/crontabs/root
RUN chmod 0644 /etc/crontabs/root
CMD ["sh", "-c", "crond && php-fpm"]