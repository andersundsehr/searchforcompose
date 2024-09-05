<?php
$certsDir = '/certs';
$certFiles = scandir($certsDir);

$newConfig = '';
echo PHP_EOL . "\033[32m" . "Certificates : " . "\033[0m" . PHP_EOL;
foreach ($certFiles as $cert) {
    if ($cert == '.' || $cert == '..') continue;
    if (pathinfo($cert, PATHINFO_EXTENSION) === 'key') continue;
    if ($cert === 'default.crt') continue;
    $domain = pathinfo($cert, PATHINFO_FILENAME);
    echo PHP_EOL . "\033[32m" . "- " . $domain . "\033[0m" . PHP_EOL;
    $virtualHost = getenv('VIRTUAL_HOST') ?: throw new \Exception('env VIRTUAL_HOST is required');
    $newConfig .= "
server {
  server_name *.$domain;
  access_log /var/log/nginx/access.log;
  http2 on;
  listen 443 ssl ;
  ssl_session_timeout 5m;
  ssl_session_cache shared:SSL:50m;
  ssl_session_tickets off;
  ssl_certificate /etc/nginx/certs/$domain.crt;
  ssl_certificate_key /etc/nginx/certs/$domain.key;
  location / {
    proxy_pass http://$virtualHost;
    }
  }
  ";
}

$nginxConfFile = "/searchforcompose.conf";
//only restart nginx if the config file has changed
$oldConfig = file_get_contents($nginxConfFile);
if ($oldConfig === $newConfig) {
    echo PHP_EOL . "\033[32m" . "Nginx config file did not change" . "\033[0m" . PHP_EOL;
    exit(0);
}

file_put_contents($nginxConfFile, $newConfig);
echo PHP_EOL ."\033[32m" . "Nginx config file generated successfully" . "\033[0m" . PHP_EOL;

if (passthru("sudo docker restart $(sudo docker ps -f 'label=com.github.kanti.local_https.nginx_proxy' -q)") === false) {
    echo PHP_EOL ."\033[31m" . "Nginx restart failed" . "\033[0m" . PHP_EOL;
    exit(1);
}
echo PHP_EOL ."\033[32m" . "Nginx restarted successfully" . "\033[0m" . PHP_EOL;
exit(0);
