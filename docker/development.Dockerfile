FROM pluswerk/php-dev:nginx-8.3-alpine
WORKDIR /app
RUN apk add inotify-tools