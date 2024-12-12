<?php

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function createUserFunction(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entityManager = DoctrineConnector::getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        $existingUser = $userRepository->findOneBy(['email' => $_POST['email']]);
        if ($existingUser !== null) {
            echo '<div>Error: Email already exists</div>';
            echo '<button onclick="location.href=\'/users\'">Back</button>';
            return;
        }

        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setEnabled(true);
        $user->setIsAdmin(false);

        $entityManager->persist($user);
        $entityManager->flush();

        echo '<div>User created successfully</div>';
        echo '<button onclick="location.href=\'/users\'">Back</button>';
    } else {
        echo <<<FORM
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/styles/styles.css">
            <title>Create User</title>
        </head>
        <body>
        <form method="post" action="/users/create">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <input type="submit" value="Create User">
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