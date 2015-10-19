FROM alpine:latest

MAINTAINER Kai Hendry <hendry@iki.fi>

RUN apk upgrade --update --available && \
    apk add \
      nginx \
      php \
      php-fpm \
      php-json \
      bash \
      vim \
      alpine-sdk \
      cmake \
      supervisor \
    && rm -f /var/cache/apk/*

ADD www /srv/http
ADD nginx.conf /etc/nginx/nginx.conf
ADD php-fpm.ini /etc/supervisor.d/php-fpm.ini
ADD nginx.ini /etc/supervisor.d/nginx.ini

RUN git clone https://github.com/jgm/cmark.git && cd cmark && INSTALL_PREFIX=/usr make install

VOLUME /srv/http/
VOLUME /var/log/nginx

EXPOSE 80

CMD supervisord -n -c /etc/supervisord.conf
