<?php
function getVirtualHosts()
{
    // Get the list of all running and not running container ids
    $all_container_ids = shell_exec("sudo docker ps -aq");
    // Convert the string of container IDs into an array
    $container_ids_array = explode("\n", trim($all_container_ids));
    // Initialize an array to hold virtual hosts
    $virtualHosts = [];

    // Check if the container ids array is set and is not NULL
    if (isset($container_ids_array)) {

        // Iterate over all container ids and save the virtualhost in container_virtualhosts
        foreach ($container_ids_array as $container_id) {
            // Get the virtual host from the container
            $virtualHostsRaw = shell_exec("sudo docker inspect $container_id --format='{{.Config.Env}}' | grep -oP 'VIRTUAL_HOST=\K[^ ]*'");
            // Split multiple virtual hosts if there are commas
            $virtualHostsParts = preg_split('/,/', trim($virtualHostsRaw));
            // Trim the virtual hosts and add them to the virtualHosts
            foreach ($virtualHostsParts as $vh) {
                //skip empty virtual hosts
                if (trim($vh) === '') continue;
                $virtualHosts[] = trim($vh);
            }
        }
    }

    return $virtualHosts;
}

function getServerDomain()
{
    // Get the server domain from the server name
    $serverDomain = $_SERVER['HTTP_HOST'];
    return $serverDomain;
}

function checkVirtualHostEqualToServerDomain($virtualHosts, $serverDomain)
{
    // Check if the virtualhosts array is not empty
    if (isset($virtualHosts)) {
        foreach ($virtualHosts as $vh) {
            // Check if the server domain is equal to the virtualhost. If it is, get the container name and return it
            if ($vh == $serverDomain) {
                $containerName = trim(shell_exec("sudo docker inspect $(sudo docker ps -aq) --format='{{.Name}} {{.Config.Env}}' | grep 'VIRTUAL_HOST=$vh' | cut -d' ' -f1 | cut -c2- | head -n 1"));
                return $containerName;
            }
        }
    }
}

function getProjectName($containerName)
{
    $projectName = shell_exec("sudo docker inspect $containerName --format='{{.Config.Labels}}' | grep -oP '(?<=com.docker.compose.project:)[^ ]*'");
    return $projectName;
}

function listAllContainerOfProject($projectName)
{
    $allContainers = shell_exec("sudo docker ps -a --filter='label=com.docker.compose.project=$projectName' --format='{{.Names}}'");
    $containerNames = explode("\n", trim($allContainers));
    return $containerNames;
}

function startAllContainersOfProject($projectName)
{
    $allContainers = shell_exec("sudo docker ps -a --filter='label=com.docker.compose.project=$projectName' --format='{{.Names}}'");
    $containerNames = explode("\n", trim($allContainers));
    foreach ($containerNames as $containerName) {
        shell_exec("sudo docker start $containerName");
    }
}

function generateConfFile()
{
    $nginxConfFile = "etc/nginx/conf.d/searchforcompose.conf";
    file_put_contents($nginxConfFile, "");
    $certsDir = '/etc/nginx/certs';
    $certFiles = scandir($certsDir);

    foreach ($certFiles as $cert) {
        if ($cert == '.' || $cert == '..') continue;
        if (pathinfo($cert, PATHINFO_EXTENSION) !== 'crt') continue;
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
proxy_pass http://searchforcompose.vm17.iveins.de;}
}

";

        file_put_contents($nginxConfFile, $nginxConfig, FILE_APPEND);
    }

}