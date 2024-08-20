<?php
include("./db.config.php");
$pdo = $dbconnection;

$data = json_decode(file_get_contents("php://input"));

if (isset($data->post_id) && isset($data->comment)) {
    $post_id = $data->post_id;
    $comment = $data->comment;

    $stmt = $pdo->prepare('INSERT INTO comments (post_id, comment) VALUES (?, ?)');
    if ($stmt->execute([$post_id, $comment])) {
        echo json_encode(['message' => 'Comment created successfully']);
    } else {
        echo json_encode(['message' => 'Failed to create comment']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
