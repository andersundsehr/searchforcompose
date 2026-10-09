ARG PHP_VERSION=8.5
FROM ghcr.io/pluswerk/php-dev:nginx-${PHP_VERSION}-alpine
WORKDIR /app
RUN apk add inotify-tools

COPY ./src /app
COPY ./src/supervisor.d/searchForCompose.conf /opt/docker/etc/supervisor.d/searchForCompose.conf

ENV WEB_DOCUMENT_ROOT=/app/public
