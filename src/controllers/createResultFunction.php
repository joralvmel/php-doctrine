<?php

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function createResultFunction(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entityManager = DoctrineConnector::getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->find($_POST['user_id']);

        if ($user === null) {
            echo '<div>Error: User not found</div>';
            echo '<button onclick="location.href=\'/results\'">Back</button>';
            return;
        }

        if (empty($_POST['result']) || empty($_POST['time'])) {
            echo '<div>Error: Please provide valid result and time</div>';
            echo '<button onclick="location.href=\'/results\'">Back</button>';
            return;
        }

        $result = new Result();
        $result->setResult($_POST['result']);
        $result->setUser($user);
        $result->setTime(new \DateTime($_POST['time']));

        $entityManager->persist($result);
        $entityManager->flush();

        echo '<div>Result created successfully</div>';
        echo '<button onclick="location.href=\'/results\'">Back</button>';
    } else {
        echo <<<FORM
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/styles/styles.css">
            <title>Create Result</title>
        </head>
        <body>
        <form method="post" action="/results/create">
            <div>
                <label for="result">Result:</label>
                <input type="number" id="result" name="result" required>
            </div>
            <div>
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required>
            </div>
            <div>
                <label for="time">Time:</label>
                <input type="datetime-local" id="time" name="time" required>
            </div>
            <div>
                <input type="submit" value="Create Result">
            </div>
        </form>
        <div class="buttons">
            <button onclick="location.href='/results'">Back</button>
        </div>
        </body>
        </html>
        FORM;
    }
}