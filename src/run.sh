#!/bin/bash

set -euo pipefail

function generateNginxConfig() {
  php /app/public/generateNginxConfig.php
}

while true; do
  generateNginxConfig
  inotifywait -r -e modify -e move -e create -e attrib -e delete /certs
done