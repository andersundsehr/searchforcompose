<?php

require '../functions.php';

$virtualHosts = getVirtualHosts();
$serverDomain = getServerDomain();
$containerName = checkVirtualHostEqualToServerDomain($virtualHosts, $serverDomain);
if ($containerName) {
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
                <h3>Start all containers of project <em>$projectName</em></h3>
                <p>All found containers: </p>
                <ul><li>" . implode("<li>", $containerNames) . "</ul></p>
                <form method='post' action=''>
                <input type='hidden' name='projectName' value='$projectName'>
                <button class='button' type='submit' name='submit'>Start</button>
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
               <p>Didn't find the virtual host <b>$serverDomain</b>, so couldn't start the containers for it</p>
               <p>Virtual Hosts found instead:</p>
               <ul><li>" . implode("<li>", $virtualHosts) . "</ul>
                 </div>
            </body>
        </html>";
}

if (isset($_POST['submit'])) {
    startAllContainersOfProject($_POST['projectName']);
}
