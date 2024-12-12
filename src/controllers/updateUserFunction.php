<?php


use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

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