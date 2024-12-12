<?php


use MiW\Results\Utility\DoctrineConnector;

function homePageFunction(): void
{
    global $routes;
    $listUserRoute = $routes->get('user_list_route')->getPath();
    $listResultRoute = $routes->get('result_list_route')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$listUserRoute">User List</a></li>
        <li><a href="$listResultRoute">Result List</a></li>
    </ul>
    MARCA_FIN;
}