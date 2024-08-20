<?php
include("./db.config.php");

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id = $data->id;

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    if ($stmt->execute([$id])) {
        echo json_encode(['message' => 'User deleted successfully']);
    } else {
        echo json_encode(['message' => 'Failed to delete user']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
