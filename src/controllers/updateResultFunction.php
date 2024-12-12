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
        echo 'Result not found';
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
        echo 'Result updated successfully';
    } else {
        echo <<<FORM
        <form method="post" action="/results/update/{$id}">
            Result: <input type="number" name="result" value="{$result->getResult()}" required><br>
            User ID: <input type="number" name="user_id" value="{$result->getUser()->getId()}" required><br>
            Time: <input type="datetime-local" name="time" value="{$result->getTime()->format('Y-m-d\TH:i')}" required><br>
            <input type="submit" value="Update Result">
        </form>
        FORM;
    }
}