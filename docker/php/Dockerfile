FROM php:latest

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && groupadd -g 1000 php \
    && useradd -g 1000 -u 1000 -m -r -s /bin/sh php
