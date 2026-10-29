FROM ghcr.io/pluswerk/php-dev:nginx-8.5-alpine
WORKDIR /app
RUN apk add inotify-tools