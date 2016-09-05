FROM debian:wheezy

ENV DEBIAN_FRONTEND noninteractive

RUN echo 'APT::Install-Recommends "0";' >>/etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >>/etc/apt/apt.conf.d/99-suggests && \
    echo "alias d='ls -al --color'" >>/root/.bashrc && \
    apt-get -y update && \
    apt-get -y install supervisor \
                       psmisc \
                       procps && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/* && \
    sed 's%^files = .*%files = /etc/supervisor/system.conf.d/*.conf /etc/supervisor/conf.d/*.conf%g' -i /etc/supervisor/supervisord.conf

ADD system.conf.d /etc/supervisor/system.conf.d

CMD ["/usr/bin/supervisord"]
