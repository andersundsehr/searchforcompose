<?php

require 'functions.php';

$virtualHosts = getVirtualHosts();
$serverDomain = getServerDomain();
$containerName = checkVirtualHostEqualToServerDomain($virtualHosts, $serverDomain);
if($containerName) {
    $projectName = getProjectName($containerName);
    $containerNames = listAllContainerOfProject($projectName);
}
//Start containers by click on button
if ($containerName) {
    echo "<!DOCTYPE html>
        <html>
            <head>
                <title>Search for Compose</title>
                <link rel='stylesheet' type='text/css' href='styles.css'>
            </head>
            <body>
            <div class='wrapper'>
                <form method='post' action='start.php'>
                <input type='hidden' name='projectName' value='$projectName'>
                <button type='submit' name='submit'>Start</button>
                </form>
                </div>
            </body>
        </html>";
} else {
    echo "<!DOCTYPE html>
        <html>
            <head>
                <title>Error</title>
                <link rel='stylesheet' type='text/css' href='styles.css'>
            </head>
            <body>
            <div class='wrapper'>
               <h3 class='red'>Error</h3>
               <p>Didn't get a container name or virtual host, so couldn't start a container</p>
                 </div>
            </body>
        </html>";
}