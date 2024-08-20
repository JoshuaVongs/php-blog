<?php
include("./db.config.php");
$pdo = $dbconnection;

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->comment)) {
    $id = $data->id;
    $comment = $data->comment;

    $stmt = $pdo->prepare('UPDATE comments SET comment = ? WHERE id = ?');
    if ($stmt->execute([$comment, $id])) {
        echo json_encode(['message' => 'Comment updated successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update comment']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
