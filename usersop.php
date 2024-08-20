<?php
include "./db.config.php";
$pdo = $dbconnection;
$data = json_decode(file_get_contents('php://input'), true);


function createUser(PDO $pdo, array $userData)
{
    if (!isset($userData['firstname'], $userData['lastname'], $userData['email'], $userData['password'])) {
        return ['error' => 'Missing required fields'];
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userData['firstname'], $userData['lastname'], $userData['email'], $userData['password']]);
        return ['message' => 'User created successfully'];
    } catch (PDOException $e) {
        error_log("Error creating user: " . $e->getMessage());
        return ['error' => 'Failed to create user: ' . $e->getMessage()];
    }
}

function updateUser(PDO $pdo, int $id, string $firstname, string $lastname, string $email, string $password)
{
    try {
        $stmt = $pdo->prepare('UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, password = :password WHERE id = :id');
        $stmt->bindParam(':firstName', $firstname);
        $stmt->bindParam(':lastName', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['message' => 'User updated successfully'];
    } catch (PDOException $e) {
        echo("Error updating user: " . $e->getMessage());
        return ['error' => 'Failed to update user'];
    }
}

function deleteUser(PDO $pdo, int $id)
{
    try {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return ['message' => 'User deleted successfully'];
    } catch (PDOException $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return ['error' => 'Failed to delete user'];
    }
}

function getUserById(PDO $pdo, int $userId)
{
    try {
        $stmt = $pdo->prepare('SELECT id, firstname, lastname, email FROM users WHERE id = :userId');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : ['error' => 'User not found'];
    } catch (\Throwable $th) {
        error_log("Error fetching user: " . $th->getMessage());
        return ['error' => 'Failed to fetch user'];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $response = createUser($pdo, $data);
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $firstname = $data['firstname'] ?? null;
        $lastname = $data['lastname'] ?? null;
        $email = $data['email'] ?? null;
        $password = isset($data['password']) ? $data['password'] : null;

        $response = updateUser($pdo, $id, $firstname, $lastname, $email, $password);
        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field: id']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $userId = (int)$_GET['id'];
        $user = getUserById($pdo, $userId);
        $response = deleteUser($pdo, $id);
        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field: id']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $userId = (int)$_GET['id'];
        $user = getUserById($pdo, $userId);

        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
        }
    } else {
        echo json_encode(['message' => 'id not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
