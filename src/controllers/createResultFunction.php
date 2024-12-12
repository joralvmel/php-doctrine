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
            echo 'Error: User not found';
            return;
        }

        // Validate result and time
        if (empty($_POST['result']) || empty($_POST['time'])) {
            echo 'Error: Please provide valid result and time';
            return;
        }

        // Create and persist the result
        $result = new Result();
        $result->setResult($_POST['result']);
        $result->setUser($user);
        $result->setTime(new \DateTime($_POST['time']));

        $entityManager->persist($result);
        $entityManager->flush();

        echo 'Result created successfully with ID: ' . $result->getId();
    } else {
        echo <<<FORM
        <form method="post" action="/results/create">
            Result: <input type="number" name="result" required><br>
            User ID: <input type="number" name="user_id" required><br>
            Time: <input type="datetime-local" name="time" required><br>
            <input type="submit" value="Create Result">
        </form>
        FORM;
    }
}