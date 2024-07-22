<?php
//Anschauen: docker restart mit label; inotifywait; Verknüpfung mit nginx proxy, um Zertifikate zu hinterlegen
// -> wie genau? Ich denke über Volume (siehe mein Versuch) aber muss man noch was machen? Wie kann ich meine Sachen im Volume vom nginx proxy hinterlegen? Gerade im Global
$certsDir = '/etc/nginx/certs';

//wie inotifywait in Code einbinden? So richtig?
while (true) {
    $inotifyCommand = "inotifywait -m -e create,modify --format '%w%f' $certsDir";
    $output = shell_exec($inotifyCommand);

    $certFiles = scandir($certsDir);
    foreach ($certFiles as $cert) {
        if ($cert == '.' || $cert == '..') continue;
        $domain = pathinfo($cert, PATHINFO_FILENAME);

        $nginxConfig = "server {
server_name *.$domain;
access_log /var/log/nginx/access.log vhost;
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
}";

        $nginxConfFile = "etc/nginx/conf.d/$domain.conf";
        file_put_contents($nginxConfFile, $nginxConfig);

//Fehler: ich möchte nginx proxy restarten
        $result = shell_exec('docker ps -f "label=com.github.kanti.local_https.nginx_proxy" -q');
        if (!$result) {
            throw new Exception('ERROR NginxProxy Not found. did you not set the label=com.github.kanti.local_https.nginx_proxy on jwilder/nginx-proxy');
        } else {
            $result = shell_exec("docker restart %s");
            $this->output->writeln($result . PHP_EOL . '<info>Nginx Restarted.</info>');
        }
    }
}
