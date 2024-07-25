#!/bin/bash

# The current Version of this file can be found HERE:
# https://github.com/pluswerk/php-dev/blob/master/start.sh

[ -f .env ] && . .env

CONTEXT=${CONTEXT:-Development}

USER=${APPLICATION_UID:-1000}:${APPLICATION_GID:-1000}

function startFunction {
  key="$1"
  case ${key} in
     start)
        startFunction pull && \
        startFunction build && \
        startFunction up && \
        startFunction login
        return
        ;;
     up)
        docker-compose --project-directory . -f docker-compose.yml up -d --remove-orphans
        return
        ;;
     down)
        docker-compose --project-directory . -f docker-compose.yml down --remove-orphans
        return
        ;;
     login)
        docker-compose --project-directory . -f docker-compose.yml exec -u $USER web bash
        return
        ;;
     login:node)
        docker-compose --project-directory . -f docker-compose.yml exec -u $USER node bash
        return
        ;;
     *)
        docker-compose --project-directory . -f docker-compose.yml "${@:1}"
        return
        ;;
  esac
}

startFunction "${@:1}"
        exit $?
