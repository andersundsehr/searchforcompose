<?php

$nginxConfFile = "/searchforcompose.conf";
$oldConfig = file_get_contents($nginxConfFile);
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

//nginx proxy nich restarten wenn nichts geändert wurde
if($oldConfig === $nginxConfig){
    echo "\033[32m" . "Nginx config file did not change" . "\033[0m" . PHP_EOL;
    exit(0);
} else {
    file_put_contents($nginxConfFile, $nginxConfig);
    echo "\033[32m" . "Nginx config file generated successfully" . "\033[0m" . PHP_EOL;

    if(passthru("sudo docker restart $(sudo docker ps -f 'label=com.github.kanti.local_https.nginx_proxy' -q)") === false){
        echo "\033[31m" . "Nginx restart failed" . "\033[0m" . PHP_EOL;
        exit(1);
    }
    echo "\033[32m" . "Nginx restarted successfully" . "\033[0m" . PHP_EOL;
}