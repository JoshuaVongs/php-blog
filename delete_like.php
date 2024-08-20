<?php

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id = (int)$data->id;

    $stmt = $pdo->prepare('DELETE FROM likes WHERE id = ?');
    if ($stmt->execute([$id])) {
        echo json_encode(['message' => 'Like deleted successfully']);
    } else {
        echo json_encode(['message' => 'Failed to delete like']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
