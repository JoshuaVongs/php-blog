<?php
include("./db.config.php");
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents("php://input"), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$email = $data['email'];
$password = $data['password'];
$host = 'localhost';
$db = 'blog';
$username = 'root';
$password = '';
$type = 'mysql';



try {
    // $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
    // $stmt->execute([$firstName, $lastName, $email, $password]);

    $stmt = $pdo->query("SELECT id, firstName, lastName, email FROM users");
    $users = $stmt->fetchAll();
    echo json_decode($users);

//     echo json_encode([
//         'message' => 'User added successfully',
//         "data" => [
//             "firstName"=> $firstName,
//             "lastName" => $lastName,
//             "email" => $email,
//         ],
// ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to add user: ' . $e->getMessage()]);
}
try {
    $stmt = $pdo->query("SELECT id, firstName, lastName, email FROM users");
     echo $users ;
} catch ( PDOException $e){
    echo json_encode(['error'   . $e->getMessage()]);
}