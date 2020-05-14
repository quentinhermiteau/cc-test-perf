FROM php:fpm

RUN groupadd -g 1000 php
RUN useradd -g 1000 -u 1000 -m -r -s /bin/sh php
RUN apt-get update && apt-get install --yes libpq-dev
RUN docker-php-ext-install pgsql pdo_pgsql mysqli pdo pdo_mysql
RUN sed -i 's/\(listen =\).*/\1 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/\(expose_php =\) On/\1 Off/' "$PHP_INI_DIR/php.ini"

USER php:www-data
