FROM php:8.1-alpine3.15
MAINTAINER Astislav Bozhevolnov <astislav@gmail.com>

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/composer
ENV COMPOSER_VERSION master
ENV PATH $COMPOSER_HOME/vendor/bin:$PATH
ENV PATH /app/vendor/bin:$PATH

RUN apk update \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add bash \
    && apk add --no-cache --virtual .persistent-deps \
        icu \
        icu-dev \
        libzip-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    # install pickle and docker-php-extension-installer that replace PECL for PHP8
        && wget -O /usr/local/bin/pickle https://github.com/FriendsOfPHP/pickle/releases/latest/download/pickle.phar \
        && wget -O /usr/local/bin/install-php-extensions https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions \
        && chmod u+x /usr/local/bin/pickle /usr/local/bin/install-php-extensions \
    # build and install extensions
        && pickle install ds \
        && install-php-extensions igbinary xdebug redis memcached \
        && docker-php-ext-enable xdebug \
############################################
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require sebastian/comparator \
    && composer global require phpunit/phpunit  \
    && composer global require psy/psysh \
    && apk del .build-deps \
    && rm /tmp/* -r

COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/30-xdebug.ini
COPY ./config/php.ini /usr/local/etc/php/php.ini

USER 1000

VOLUME /app
WORKDIR /app
