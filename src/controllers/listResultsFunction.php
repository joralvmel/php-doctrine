<?php

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function listResultsFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/styles.css">
        <title>List Results</title>
    </head>
    <body>
    <table>
        <tr><th>ID</th><th>Result</th><th>User</th><th>Time</th><th>Actions</th></tr>
HTML;

    foreach ($results as $result) {
        $updateUrl = "/results/update/" . $result->getId();
        $viewUrl = "/results/" . $result->getId();
        $deleteUrl = "/results/delete/" . $result->getId();

        echo <<<HTML
        <tr>
            <td>{$result->getId()}</td>
            <td>{$result->getResult()}</td>
            <td>{$result->getUser()->getUsername()}</td>
            <td>{$result->getTime()->format('Y-m-d H:i:s')}</td>
            <td>
                <button onclick="location.href='{$viewUrl}'">Read</button>
                <button onclick="location.href='{$updateUrl}'">Update</button>
                <form action="{$deleteUrl}" method="POST" style="display:inline;">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this result?')">Delete</button>
                </form>
            </td>
        </tr>
HTML;
    }

    echo <<<HTML
    </table>
    <br>
    <div class="buttons">
        <button onclick="location.href='/results/create'">Create Result</button>
        <button onclick="location.href='/'">Home</button>
    </div>
    </body>
    </html>
HTML;
}