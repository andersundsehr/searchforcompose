FROM pluswerk/php-dev:nginx-8.3-alpine
WORKDIR /app
RUN apk add inotify-tools

COPY ./src /app
COPY ./src/supervisor.d/searchForCompose.conf /opt/docker/etc/supervisor.d/searchForCompose.conf

ENV WEB_DOCUMENT_ROOT=/app/public
