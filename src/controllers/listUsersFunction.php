<?php


use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function listUsersFunction(): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th>Actions</th></tr>';
    foreach ($users as $user) {
        $updateUrl = "/users/update/" . $user->getId();
        $viewUrl = "/users/" . $user->getUsername();
        $deleteUrl = "/users/delete/" . $user->getId();

        echo '<tr>';
        echo '<td>' . $user->getId() . '</td>';
        echo '<td>' . $user->getUsername() . '</td>';
        echo '<td>
        <button onclick="location.href=\'' . $viewUrl . '\'">Read</button>
        <button onclick="location.href=\'' . $updateUrl . '\'">Update</button>
        <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
            <button type="submit" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</button>
        </form>
    </td>';
        echo '</tr>';
    }
    echo '</table>';

    $createRoute = '/users/create';
    echo <<<HTML
    <br>
    <button onclick="location.href='$createRoute'">Create User</button>
    HTML;
}