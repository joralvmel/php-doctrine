<?php

/**
 * src/update_user.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$userId = $argv[1] ?? null;
$newUsername = $argv[2] ?? null;
$newEmail = $argv[3] ?? null;
$newPassword = $argv[4] ?? null;

if ($userId === null || $newUsername === null || $newEmail === null || $newPassword === null) {
    echo "Usage: php update_user.php <user_id> <new_username> <new_email> <new_password>" . PHP_EOL;
    exit(1);
}

try {
    $user = $entityManager->find(User::class, $userId);
    if ($user === null) {
        echo "User not found" . PHP_EOL;
        exit(1);
    }

    $user->setUsername($newUsername);
    $user->setEmail($newEmail);
    $user->setPassword($newPassword);
    $entityManager->flush();

    echo "User updated: " . $user->getId() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}