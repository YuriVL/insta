FROM php:7.2-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        libxml++2.6-dev \
        libmcrypt-dev \
        libbz2-dev \
        libc-client-dev \
        libxslt-dev \
        libkrb5-dev \
        libgraphicsmagick1-dev \
        libjpeg-dev \
        subversion \
        wget \
        net-tools \
        iproute2 \
        libcurl3-dev \
        git \
            --no-install-recommends

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install bz2 calendar imap pcntl shmop sockets sysvmsg sysvsem sysvshm wddx xmlrpc xsl zip intl mbstring pdo_mysql mysqli opcache gettext xml exif \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN apt-get install libmcrypt-dev
RUN pecl install mcrypt-1.0.1 && docker-php-ext-enable mcrypt

#Install memcached
RUN apt-get update && apt-get install -y \
        libmemcached11 \
        libmemcachedutil2 \
        libmemcached-dev \
        libz-dev \
        git \
    && cd /root \
    && git clone -b php7 https://github.com/php-memcached-dev/php-memcached \
    && cd php-memcached \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && cd .. \
    && rm -rf  php-memcached \
    && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
    && apt-get remove -y build-essential libmemcached-dev libz-dev \
    && apt-get remove -y libmemcached-dev libz-dev \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

#Install apcu
ARG APCU_VERSION=5.1.11
RUN pecl install apcu-${APCU_VERSION} && docker-php-ext-enable apcu
RUN echo "apc.enable_cli=1" >> /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" >> /usr/local/etc/php/php.ini

# install xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# install gmagick
RUN pecl install gmagick-2.0.4RC1 \
    && docker-php-ext-enable gmagick \
    && rm -rf /tmp/pear
# clear
RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && composer global require hirak/prestissimo --no-plugins --no-scripts

RUN usermod -u 1000 www-data

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./www.conf /usr/local/etc/php/
COPY ./conf.d /usr/local/etc/php/conf.d

# install additional packages
RUN apt-get update && apt-get -y install cron procps iputils-ping ssh
# Add crontab file in the cron directory
COPY ./crontab.cron /etc/cron.d/root
RUN crontab /etc/cron.d/root

WORKDIR /app

COPY ./entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint
