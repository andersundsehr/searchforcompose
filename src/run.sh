#!/bin/bash

set -euo pipefail

function generateNginxConfig() {
  php /app/generateNginxConfig.php
}

while true; do
  generateNginxConfig
  inotifywait -r -e modify -e move -e create -e attrib -e delete --timeout 3600 /certs || status=$?
  if [[ "${status:-0}" != "0" && "${status:-0}" != "2" ]]; then
    sleep 5
  fi
  unset status
done
