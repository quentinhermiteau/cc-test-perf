FROM php:alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && addgroup -g 1000 -S php \
    && adduser -h /home/php -g "" -s /bin/sh -G php -S -D -u 1000 php
