<?php
include("./db.config.php");

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->firstName) && isset($data->lastName) && isset($data->email) && isset($data->password)) {
    $id = $data->id;
    $firstName = $data->firstName;
    $lastName = $data->lastName;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT); // Hash the password

    $stmt = $pdo->prepare('UPDATE users SET firstName = ?, lastName = ?, email = ?, password = ? WHERE id = ?');
    if ($stmt->execute([$firstName, $lastName, $email, $password, $id])) {
        echo json_encode(['message' => 'User updated successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update user']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
