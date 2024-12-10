<?php

/**
 * src/update_result.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Load environment variables
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$resultId = $argv[1] ?? null;
$newResultValue = $argv[2] ?? null;
$newUserId = $argv[3] ?? null;
$newTimestamp = $argv[4] ?? 'now';

if ($resultId === null || $newResultValue === null || $newUserId === null || $newTimestamp === null) {
    echo "Usage: php update_result.php <result_id> <new_result_value> <new_user_id> <new_timestamp>" . PHP_EOL;
    exit(1);
}

try {
    $result = $entityManager->find(Result::class, $resultId);
    if ($result === null) {
        echo "Result not found" . PHP_EOL;
        exit(1);
    }

    $user = $entityManager->find(User::class, $newUserId);
    if ($user === null) {
        echo "User not found" . PHP_EOL;
        exit(1);
    }

    $result->setResult($newResultValue);
    $result->setUser($user);
    $result->setTime(new DateTime($newTimestamp));
    $entityManager->flush();

    echo "Result updated: " . $result->getId() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}