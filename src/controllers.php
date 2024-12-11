<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function homePageFunction(): void
{
    global $routes;

    $listRoute = $routes->get('user_list_route')->getPath();
    $userRoute = $routes->get('user_route')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$listRoute">User List</a></li>
    </ul>
    <form action="$userRoute" method="get">
        <label for="username">Search User by Name:</label>
        <input type="text" id="username" name="name" required>
        <button type="submit">Search</button>
    </form>
    MARCA_FIN;
}

function listUsersFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th>Email</th></tr>';
    foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . $user->getId() . '</td>';
        echo '<td>' . $user->getUsername() . '</td>';
        echo '<td>' . $user->getEmail() . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function userFunction(): void
{
    if (!isset($_GET['name'])) {
        echo 'No username provided';
        return;
    }

    $username = $_GET['name'];
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $username]);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th>Email</th></tr>';
    echo '<tr>';
    echo '<td>' . $user->getId() . '</td>';
    echo '<td>' . $user->getUsername() . '</td>';
    echo '<td>' . $user->getEmail() . '</td>';
    echo '</tr>';
    echo '</table>';
}