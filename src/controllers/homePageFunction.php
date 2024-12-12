<?php

use MiW\Results\Utility\DoctrineConnector;

function homePageFunction(): void
{
    global $routes;
    $listUserRoute = $routes->get('user_list_route')->getPath();
    $listResultRoute = $routes->get('result_list_route')->getPath();
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/styles.css">
        <title>Home Page</title>
    </head>
    <body>
    <div>
        <button onclick="location.href='/users'">User List</button>
        <button onclick="location.href='/results'">Result List</button>
    </div>
    </body>
    </html>
HTML;
}