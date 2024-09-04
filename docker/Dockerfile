FROM pluswerk/php-dev:nginx-8.0-alpine
WORKDIR /var/www
RUN apk update && apk add inotify-tools
RUN { \
      echo 'server_tokens off;'; \
      echo 'client_max_body_size 100m;'; \
    } > /etc/nginx/conf.d/searchforcompose.conf \
