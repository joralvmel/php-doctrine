<?php


use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

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