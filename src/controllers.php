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
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$listRoute">User List</a></li>
    </ul>
    MARCA_FIN;
}

function listUsersFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th>Actions</th></tr>';
    foreach ($users as $user) {
        $updateUrl = "/users/update/" . $user->getId();
        $viewUrl = "/users/" . $user->getUsername();
        $deleteUrl = "/users/delete/" . $user->getId();

        echo '<tr>';
        echo '<td>' . $user->getId() . '</td>';
        echo '<td>' . $user->getUsername() . '</td>';
        echo '<td>
        <button onclick="location.href=\'' . $viewUrl . '\'">Read</button>
        <button onclick="location.href=\'' . $updateUrl . '\'">Update</button>
        <button onclick="if(confirm(\'Are you sure you want to delete this user?\')) location.href=\'' . $deleteUrl . '\'">Delete</button>
    </td>';
        echo '</tr>';
    }
    echo '</table>';

    $createRoute = '/users/create';
    echo <<<HTML
    <br>
    <button onclick="location.href='$createRoute'">Create User</button>
    HTML;
}

function userFunction(string $name): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);

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

function createUserFunction(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entityManager = DoctrineConnector::getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        // Check if the email already exists
        $existingUser = $userRepository->findOneBy(['email' => $_POST['email']]);
        if ($existingUser !== null) {
            echo 'Error: Email already exists';
            return;
        }

        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'User created successfully';
    } else {
        echo <<<FORM
        <form method="post" action="/users/create">
            Username: <input type="text" name="username" required><br>
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Create User">
        </form>
        FORM;
    }
}

function updateUserFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        $entityManager->flush();
        echo 'User updated successfully';
    } else {
        echo <<<FORM
        <form method="post" action="/users/update/{$id}">
            Username: <input type="text" name="username" value="{$user->getUsername()}"><br>
            Email: <input type="text" name="email" value="{$user->getEmail()}"><br>
            Password: <input type="password" name="password"><br>
            <input type="submit" value="Update User">
        </form>
        FORM;
    }
}

function deleteUserFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    $entityManager->remove($user);
    $entityManager->flush();
    echo 'User deleted successfully';
}