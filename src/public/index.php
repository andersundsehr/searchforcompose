<?php

require 'functions.php';

$virtualHosts = getVirtualHosts();
$serverDomain = getServerDomain();
$containerName = checkVirtualHostEqualToServerDomain($virtualHosts, $serverDomain);
$projectName = getProjectName($containerName);

//Start containers by click on button
if ($containerName) {
    echo "<!DOCTYPE html>
        <html>
            <head>
                <title>Starte alle Container</title>
            </head>
            <body>
                <h3>Starte alle Container</h3>
                <form method='post' action='functions.php'>
                <input type='hidden' name='projectName' value='$projectName'>
                <button type='submit' name='submit'>Start</button>
                </form>
            </body>
        </html>";
} else {
    echo "<!DOCTYPE html>
        <html>
            <head>
                <title>Error</title>
            </head>
            <body>
               <h3>Error</h3>
               <p>Didn't get a container name or virtual host, so couldn't start a container</p>
            </body>
        </html>";
}