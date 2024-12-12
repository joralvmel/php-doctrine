<?php
use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function updateResultFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $result = $resultRepository->find($id);

    if ($result === null) {
        echo '<div>Result not found</div>';
        echo '<button onclick="location.href=\'/results\'">Back</button>';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($_POST['user_id']);

        if ($user === null) {
            echo 'Error: User not found';
            return;
        }

        $result->setResult($_POST['result']);
        $result->setUser($user);
        $result->setTime(new \DateTime($_POST['time']));

        $entityManager->flush();

        echo '<div>Result updated successfully</div>';
        echo '<button onclick="location.href=\'/results\'">Back</button>';
    } else {
        echo <<<FORM
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/styles/styles.css">
            <title>Update Result</title>
        </head>
        <body>
        <form method="post" action="/results/update/{$id}">
            <div>
                <label for="result">Result:</label>
                <input type="number" id="result" name="result" value="{$result->getResult()}" required>
            </div>
            <div>
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" value="{$result->getUser()->getId()}" required>
            </div>
            <div>
                <label for="time">Time:</label>
                <input type="datetime-local" id="time" name="time" value="{$result->getTime()->format('Y-m-d\TH:i')}" required>
            </div>
            <div>
                <input type="submit" value="Update Result">
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