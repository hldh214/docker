FROM ubuntu:16.04

MAINTAINER Jim "https://github.com/hldh214"

RUN apt-get update
RUN apt-get install -y openssh-server nginx php vim lrzsz git supervisor

RUN mkdir -p /run/sshd
RUN mkdir -p /var/log/supervisor
RUN mkdir -p /run/php

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY default /etc/nginx/sites-available/default

RUN echo 'root:root' | chpasswd
RUN sed -ri 's/^PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed -ri 's/UsePAM yes/#UsePAM yes/g' /etc/ssh/sshd_config

RUN echo "<?php phpinfo(); ?>" >> /var/www/html/index.php

EXPOSE 22 80

CMD ["/usr/bin/supervisord"]