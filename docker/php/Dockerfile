FROM php:7.1-apache

RUN apt-get update && apt-get install -y git libmemcached-dev zlib1g-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

RUN git clone https://github.com/php-memcached-dev/php-memcached.git /tmp/php-memcached \
    && cd /tmp/php-memcached \
    && git checkout php7 \
    && phpize \
    && ./configure --disable-memcached-sasl \
    && make \
    && make install \
    && docker-php-ext-enable memcached \
    && rm -fr /tmp/php-memcached

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php

COPY site.conf /etc/apache2/sites-available/

RUN a2dissite 000-default \
    && a2ensite site \
    && a2enmod rewrite
