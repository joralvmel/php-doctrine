<?php

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function listUsersFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/styles.css">
        <title>List Users</title>
    </head>
    <body>
    <table>
        <tr><th>ID</th><th>Username</th><th>Actions</th></tr>
HTML;

    foreach ($users as $user) {
        $updateUrl = "/users/update/" . $user->getId();
        $viewUrl = "/users/" . $user->getUsername();
        $deleteUrl = "/users/delete/" . $user->getId();

        echo <<<HTML
        <tr>
            <td>{$user->getId()}</td>
            <td>{$user->getUsername()}</td>
            <td>
                <button onclick="location.href='{$viewUrl}'">Read</button>
                <button onclick="location.href='{$updateUrl}'">Update</button>
                <form action="{$deleteUrl}" method="POST" style="display:inline;">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
            </td>
        </tr>
HTML;
    }

    echo <<<HTML
    </table>
    <br>
    <div class="buttons">
        <button onclick="location.href='/users/create'">Create User</button>
        <button onclick="location.href='/'">Home</button>
    </div>
    </body>
    </html>
HTML;
}