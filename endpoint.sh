#!/bin/bash

unlink /etc/nginx/sites-available/default
cp ./default /etc/nginx/sites-available/default

/usr/sbin/sshd -D
service php7.0-fpm start
/usr/sbin/nginx