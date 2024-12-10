<?php

/**
 * src/scripts/delete_result.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Load environment variables
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$resultId = $argv[1] ?? null;

if ($resultId === null) {
    echo "Usage: php delete_result.php <result_id>" . PHP_EOL;
    exit(1);
}

try {
    $result = $entityManager->find(Result::class, $resultId);
    if ($result === null) {
        echo "Result not found" . PHP_EOL;
        exit(1);
    }

    $entityManager->remove($result);
    $entityManager->flush();

    echo "Result deleted: " . $resultId . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}