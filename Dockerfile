FROM ubuntu:16.04

MAINTAINER Jim "https://github.com/hldh214"

RUN apt-get update
RUN apt-get install -y openssh-server nginx php vim lrzsz git
RUN mkdir /var/run/sshd

RUN echo 'root:root' | chpasswd

RUN sed -ri 's/^PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed -ri 's/UsePAM yes/#UsePAM yes/g' /etc/ssh/sshd_config

EXPOSE 22

COPY endpoint.sh /
COPY default /
RUN chmod +x /*.sh

ENTRYPOINT ["/endpoint.sh"]