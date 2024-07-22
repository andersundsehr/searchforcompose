<?php
// Check if the request method is POST (from index.php) and if the container_name is not empty and if the virtual_host is not empty
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['container_name']) && !empty($_POST['virtual_host'])) {
    // Get the container name from the POST request and run the container with said name
    $containerName = $_POST['container_name'];
    $virtual_host = $_POST['virtual_host'];
    shell_exec("docker start $containerName");
    // Show a success message
    echo "<!DOCTYPE html>
        <html>
            <head>
                <title>Container started</title>
            </head>
            <body>
                <h1>Container started</h1>
                <p>The container of <strong>$virtual_host</strong> has been started!</p>
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