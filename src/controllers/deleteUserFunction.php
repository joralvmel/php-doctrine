<?php


use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

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