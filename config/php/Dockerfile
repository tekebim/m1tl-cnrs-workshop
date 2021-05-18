ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm

# git, unzip & zip are for composer
RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    vim \
    gnupg \
    unzip \
    default-mysql-client \
    libbz2-dev \
    libc-client-dev \
    libcurl4-gnutls-dev \
    libedit-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libkrb5-dev \
    libldap2-dev \
    libldb-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libpng-dev \
    libpq-dev \
    libsqlite3-dev \
    libssl-dev \
    libreadline-dev \
    libxslt1-dev \
    libzip-dev \
    memcached \
    wget \
    unzip \
    zlib1g-dev \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    exif \
    gettext \
    mysqli \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    gd \
    soap \
    sockets \
    xmlrpc \
    xsl \
    zip && \
    pecl install imagick && docker-php-ext-enable imagick && \
    pecl install xdebug && docker-php-ext-enable xdebug && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP Extensions
RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql

COPY ./php.ini /usr/local/etc/php/conf.d/app.ini
COPY www.conf /usr/local/etc/php-fpm.d/

USER www-data

EXPOSE 9000
WORKDIR /var/www/html/
