<?php

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function updateUserFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if ($user === null) {
        echo '<div>User not found</div>';
        echo '<button onclick="location.href=\'/users\'">Back</button>';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        $entityManager->flush();
        echo '<div>User updated successfully</div>';
        echo '<button onclick="location.href=\'/users\'">Back</button>';
    } else {
        echo <<<FORM
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/styles/styles.css">
            <title>Update User</title>
        </head>
        <body>
        <form method="post" action="/users/update/{$id}">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="{$user->getUsername()}" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{$user->getEmail()}" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div>
                <input type="submit" value="Update User">
            </div>
        </form>
        <div class="buttons">
            <button onclick="location.href='/users'">Back</button>
        </div>
        </body>
        </html>
        FORM;
    }
}