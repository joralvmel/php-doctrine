<?php

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function resultFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $result = $resultRepository->find($id);

    if ($result === null) {
        echo '<div>Result not found</div>';
        echo '<button onclick="location.href=\'/results\'">Back</button>';
        return;
    }

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/styles.css">
        <title>Result Details</title>
    </head>
    <body>
    <table>
        <tr><th>ID</th><th>Result</th><th>User</th><th>Time</th></tr>
        <tr>
            <td>{$result->getId()}</td>
            <td>{$result->getResult()}</td>
            <td>{$result->getUser()->getUsername()}</td>
            <td>{$result->getTime()->format('Y-m-d H:i:s')}</td>
        </tr>
    </table>
    <div class="buttons">
            <button onclick="location.href='/results'">Back</button>
    </div>
    </body>
    </html>
HTML;
}