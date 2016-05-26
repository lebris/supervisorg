FROM php:7.0-apache

RUN echo 'APT::Install-Recommends "0";' >>/etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >>/etc/apt/apt.conf.d/99-suggests

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y automake \
                       build-essential \
                       libtool && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/*

ENV RABBITMQ_VERSION 0.7.1

RUN cd /tmp && \
    curl --stderr - -L -O https://github.com/alanxz/rabbitmq-c/releases/download/v${RABBITMQ_VERSION}/rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    tar xf rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    cd rabbitmq-c-${RABBITMQ_VERSION} && \
    autoreconf -i && \
    ./configure && \
    make && \
    make install && \
    cd /tmp && \
    rm -rf rabbitmq-c-${RABBITMQ_VERSION} && \
    rm rabbitmq-c-${RABBITMQ_VERSION}.tar.gz

RUN pecl install amqp-1.7.0 && \
    docker-php-ext-enable amqp

COPY ./src /var/www/supervisorg

WORKDIR /var/www/supervisorg

RUN a2enmod rewrite && \
    mkdir /var/www/supervisorg/var && \
    chown -Rf www-data:www-data /var/www/supervisorg && \
    rm -r /var/www/html && \
    ln -s /var/www/supervisorg/www /var/www/html
