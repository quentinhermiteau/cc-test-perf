FROM php:fpm

RUN groupadd -g 1000 php
RUN useradd -g 1000 -u 1000 -m -r -s /bin/sh php
RUN apt-get update && apt-get install --yes libpq-dev
RUN docker-php-ext-install pgsql pdo_pgsql mysqli pdo pdo_mysql
RUN sed -i 's/\(listen =\).*/\1 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/\(expose_php =\) On/\1 Off/' "$PHP_INI_DIR/php.ini"

RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

USER php:www-data