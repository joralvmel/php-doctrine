<?php


use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function listResultsFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();

    echo '<table>';
    echo '<tr><th>ID</th><th>Result</th><th>User</th><th>Time</th><th>Actions</th></tr>';
    foreach ($results as $result) {
        $updateUrl = "/results/update/" . $result->getId();
        $viewUrl = "/results/" . $result->getId();
        $deleteUrl = "/results/delete/" . $result->getId();

        echo '<tr>';
        echo '<td>' . $result->getId() . '</td>';
        echo '<td>' . $result->getResult() . '</td>';
        echo '<td>' . $result->getUser()->getUsername() . '</td>';
        echo '<td>' . $result->getTime()->format('Y-m-d H:i:s') . '</td>';
        echo '<td>
        <button onclick="location.href=\'' . $viewUrl . '\'">Read</button>
        <button onclick="location.href=\'' . $updateUrl . '\'">Update</button>
         <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
            <button type="submit" onclick="return confirm(\'Are you sure you want to delete this result?\')">Delete</button>
        </form>
    </td>';
        echo '</tr>';
    }
    echo '</table>';

    $createRoute = '/results/create';
    echo <<<HTML
    <br>
    <button onclick="location.href='$createRoute'">Create Result</button>
    HTML;
}