FROM php:7.0

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

RUN apt-get -y update && \
    apt-get -y install supervisor \
                       ca-certificates \
                       apt-utils \
                       vim \
                       curl \
                       psmisc \
                       procps && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/* && \
    sed 's%^files = .*%files = /etc/supervisor/system.conf.d/*.conf /etc/supervisor/conf.d/*.conf%g' -i /etc/supervisor/supervisord.conf

ADD supervisor/system.conf.d /etc/supervisor/system.conf.d

ADD bootstrap.sh /root/bootstrap.sh

ENTRYPOINT [ "sh", "/root/bootstrap.sh" ]

RUN mkdir -p /var/www/supervisorg/var && \
    chown -Rf www-data:www-data /var/www/supervisorg && \
    ln -s /var/www/supervisorg/www /var/www/html

WORKDIR /var/www/supervisorg

CMD ["/usr/bin/supervisord"]
