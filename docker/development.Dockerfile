ARG PHP_VERSION=8.5
FROM ghcr.io/pluswerk/php-dev:nginx-${PHP_VERSION}-alpine
WORKDIR /app
RUN apk add inotify-tools