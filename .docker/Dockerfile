FROM --platform=linux/amd64 ubuntu:20.04


ARG PHP_VERSION=7.4
ARG NODE_VERSION=12.22.12
ARG YARN_VERSION=1.22.4
ARG COMPOSER_VERSION=2.3.10

ENV LANG="en_US.UTF-8" \
    LC_ALL="en_US.UTF-8" \
    LANGUAGE="en_US.UTF-8" \
    TERM="xterm" \
    DEBIAN_FRONTEND="noninteractive" \
    COMPOSER_ALLOW_SUPERUSER=1 \
    TZ="America/Los_Angeles"

EXPOSE 80
WORKDIR /app

RUN apt-get update -q && \
    apt-get install -qy software-properties-common language-pack-en-base && \
    export LC_ALL=en_US.UTF-8 && \
    export LANG=en_US.UTF-8 && \
    add-apt-repository ppa:ondrej/php && \
    apt-get -yqq install ssh && \
    apt-get install --no-install-recommends -qy \
        build-essential \
        ca-certificates \
        cron \
        curl \
        nano \
        vim \
        nginx \
        git \
        zip \
        graphviz \
        mysql-client \
        php${PHP_VERSION} \
        php${PHP_VERSION}-apcu \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-iconv \
        php${PHP_VERSION}-imagick \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-pdo \
        php${PHP_VERSION}-phar \
        php${PHP_VERSION}-soap \
        php${PHP_VERSION}-sqlite \
        php${PHP_VERSION}-xdebug \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-redis \
        php${PHP_VERSION}-simplexml \
        php${PHP_VERSION}-zip \
        supervisor \
        tzdata \
        apt-transport-https \
        pdftk \
        wget && \
    phpdismod xdebug && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    cp /usr/share/zoneinfo/America/Los_Angeles /etc/localtime && echo "America/Los_Angeles" > /etc/timezone && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} && \
    curl -L -o /tmp/nodejs.tar.gz https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.gz && \
    tar xfvz /tmp/nodejs.tar.gz -C /usr/local --strip-components=1 && \
    rm -f /tmp/nodejs.tar.gz && \
    mkdir /run/php

RUN curl -L -o /tmp/yarn.deb https://github.com/yarnpkg/yarn/releases/download/v${YARN_VERSION}/yarn_${YARN_VERSION}_all.deb && \
        dpkg -i /tmp/yarn.deb

COPY php.ini /etc/php/${PHP_VERSION}/cli/conf.d/50-setting.ini
COPY php.ini /etc/php/${PHP_VERSION}/fpm/conf.d/50-setting.ini
COPY pool.conf /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY symfony.conf /etc/nginx/symfony.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
