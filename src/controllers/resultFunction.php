<?php


use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function resultFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $result = $resultRepository->find($id);

    if ($result === null) {
        echo 'Result not found';
        return;
    }

    echo '<table>';
    echo '<tr><th>ID</th><th>Result</th><th>User</th><th>Time</th></tr>';
    echo '<tr>';
    echo '<td>' . $result->getId() . '</td>';
    echo '<td>' . $result->getResult() . '</td>';
    echo '<td>' . $result->getUser()->getUsername() . '</td>';
    echo '<td>' . $result->getTime()->format('Y-m-d H:i:s') . '</td>';
    echo '</tr>';
    echo '</table>';
}