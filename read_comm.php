<?php
include("./db.config.php");
$pdo = $dbconnection;

if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];

    $stmt = $pdo->prepare('SELECT * FROM comments WHERE post_id = ?');
    $stmt->execute([$post_id]);
    $comments = $stmt->fetchAll();

    if ($comments) {
        echo json_encode($comments);
    } else {
        echo json_encode(['message' => 'No comments found']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
