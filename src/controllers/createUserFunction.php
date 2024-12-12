<?php


use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

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
        $user->setEnabled(true);
        $user->setIsAdmin(false);
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'User created successfully with ID: ' . $user->getId();
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