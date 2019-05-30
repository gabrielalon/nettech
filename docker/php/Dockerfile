ARG PHP_VERSION=""
FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm

# Install recommended extensions for Symfony
RUN apt-get update && apt-get install -y \
        libicu-dev \
        libpq-dev \
        libxml2-dev \
        zlib1g-dev \
        libzip-dev \
        git \
        zip \
        unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo pdo_mysql pdo_pgsql pgsql \
        mysqli \
        zip \
        pcntl \
        xml \
    && docker-php-ext-enable \
        intl \
        opcache \
        pdo pdo_mysql pdo_pgsql pgsql \
        mysqli \
        zip \
        pcntl \
        xml

# Install amqp
RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install PHP Redis extension
RUN pecl install -o -f redis \
  &&  rm -rf /tmp/pear \
  &&  docker-php-ext-enable redis

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Permission fix
RUN usermod -u 1000 www-data

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
