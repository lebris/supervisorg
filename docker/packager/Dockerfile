FROM php:7.0

RUN echo 'APT::Install-Recommends "0";' >>/etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >>/etc/apt/apt.conf.d/99-suggests

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y git \
                       zlib1g-dev && \
    docker-php-ext-install zip && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www/supervisorg && \
    chown -Rf www-data:www-data /var/www/supervisorg

WORKDIR /var/www/supervisorg
