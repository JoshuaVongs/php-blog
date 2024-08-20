<?php
include("./db.config.php");
$pdo = $dbconnection;

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id = $data->id;

    $stmt = $pdo->prepare('DELETE FROM comments WHERE id = ?');
    if ($stmt->execute([$id])) {
        echo json_encode(['message' => 'Comment deleted successfully']);
    } else {
        echo json_encode(['message' => 'Failed to delete comment']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
