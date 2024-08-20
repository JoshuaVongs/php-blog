<?php
include("./db.config.php");
$pdo = $dbconnection;

header('Content-Type: application/json');
$input = file_get_contents('php://input');

$data = json_decode(file_get_contents("php://input"), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$email = $data['email'];
$password = $data['password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    try {
        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $password]);

        $stmt = $pdo->query("SELECT id, firstName, lastName, email FROM users");
        $users = $stmt->fetchAll();

        echo json_encode([
            'message' => 'User added successfully',
            "data" => [
                "firstName" => $firstName,
                "lastName" => $lastName,
                "email" => $email,
            ],
        ]);
    } catch (Throwable $th) {
        echo json_encode(['error' => 'Failed to add user']);
    }
    
} else {
    $status = http_response_code(405); 
    echo json_encode(['error' => 'Method not allowed', 'status' => $status]);
}
