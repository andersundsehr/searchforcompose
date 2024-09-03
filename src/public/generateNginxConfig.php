<?php
$nginxConfFile = "/searchforcompose.conf";
$certsDir = '/certs';
$certFiles = scandir($certsDir);

$nginxConfig = '';
foreach ($certFiles as $cert) {
    if ($cert == '.' || $cert == '..') continue;
    if (pathinfo($cert, PATHINFO_EXTENSION) !== 'crt') continue;
    $domain = pathinfo($cert, PATHINFO_FILENAME);
    $nginxConfig .= "server {
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
                proxy_pass http://searchforcompose.vm17.iveins.de;
                }
            }
            ";

}
file_put_contents($nginxConfFile, $nginxConfig);