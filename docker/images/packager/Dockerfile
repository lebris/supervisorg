FROM supervisorg/base-supervisorg

RUN apt-get update && \
    apt-get install -y git \
                       zlib1g-dev && \
    docker-php-ext-install zip && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/*
