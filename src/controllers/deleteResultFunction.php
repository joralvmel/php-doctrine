<?php
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function deleteResultFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $result = $resultRepository->find($id);

    if ($result === null) {
        echo 'Result not found';
        return;
    }

    $entityManager->remove($result);
    $entityManager->flush();
    echo '<div>Result deleted successfully</div>';
    echo '<button onclick="location.href=\'/results\'">Back</button>';
}