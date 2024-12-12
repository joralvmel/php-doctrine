<?php

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function userFunction(string $name): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);

    if ($user === null) {
        echo '<div>User not found</div>';
        echo '<button onclick="location.href=\'/users\'">Back</button>';
        return;
    }

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/styles.css">
        <title>User Details</title>
    </head>
    <body>
    <table>
        <tr><th>ID</th><th>Username</th><th>Email</th></tr>
        <tr>
            <td>{$user->getId()}</td>
            <td>{$user->getUsername()}</td>
            <td>{$user->getEmail()}</td>
        </tr>
    </table>
    <div class="buttons">
            <button onclick="location.href='/users'">Back</button>
    </div>
    </body>
    </html>
HTML;
}