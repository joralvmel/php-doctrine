<?php

/**
 * src/scripts/delete_user.php
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

if ($userId === null) {
    echo "Usage: php delete_user.php <user_id>" . PHP_EOL;
    exit(1);
}

try {
    $user = $entityManager->find(User::class, $userId);
    if ($user === null) {
        echo "User not found" . PHP_EOL;
        exit(1);
    }

    $entityManager->remove($user);
    $entityManager->flush();

    echo "User deleted: " . $userId . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}