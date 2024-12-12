<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function homePageFunction(): void
{
    global $routes;
    $listUserRoute = $routes->get('user_list_route')->getPath();
    $listResultRoute = $routes->get('result_list_route')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$listUserRoute">User List</a></li>
        <li><a href="$listResultRoute">Result List</a></li>
    </ul>
    MARCA_FIN;
}

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

function userFunction(string $name): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    echo '<table>';
    echo '<tr><th>ID</th><th>Username</th><th>Email</th></tr>';
    echo '<tr>';
    echo '<td>' . $user->getId() . '</td>';
    echo '<td>' . $user->getUsername() . '</td>';
    echo '<td>' . $user->getEmail() . '</td>';
    echo '</tr>';
    echo '</table>';
}

function createUserFunction(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entityManager = DoctrineConnector::getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        // Check if the email already exists
        $existingUser = $userRepository->findOneBy(['email' => $_POST['email']]);
        if ($existingUser !== null) {
            echo 'Error: Email already exists';
            return;
        }

        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'User created successfully with ID: ' . $user->getId();
    } else {
        echo <<<FORM
        <form method="post" action="/users/create">
            Username: <input type="text" name="username" required><br>
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Create User">
        </form>
        FORM;
    }
}

function updateUserFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        $entityManager->flush();
        echo 'User updated successfully';
    } else {
        echo <<<FORM
        <form method="post" action="/users/update/{$id}">
            Username: <input type="text" name="username" value="{$user->getUsername()}"><br>
            Email: <input type="text" name="email" value="{$user->getEmail()}"><br>
            Password: <input type="password" name="password"><br>
            <input type="submit" value="Update User">
        </form>
        FORM;
    }
}

function deleteUserFunction(int $id): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($id);

    if ($user === null) {
        echo 'User not found';
        return;
    }

    $entityManager->remove($user);
    $entityManager->flush();
    echo 'User deleted successfully';
}

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
    echo 'Result deleted successfully';
}
