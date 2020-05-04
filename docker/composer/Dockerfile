FROM composer:latest

RUN addgroup -g 1000 -S composer \
    && adduser -h /home/composer -g "" -s /bin/sh -G composer -S -D -u 1000 composer
