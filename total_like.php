<?php
include("./db.config.php");
$pdo = $dbconnection;

if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];

    // Calculate the total likes (sum of all likes where `like` = 1)
    $stmt = $pdo->prepare('SELECT SUM(`like`) AS total_likes FROM likes WHERE post_id = ?');
    $stmt->execute([$post_id]);
    $result = $stmt->fetch();

    $total_likes = $result['total_likes'] ?: 0; // Default to 0 if no likes are found

    echo json_encode(['total_likes' => $total_likes]);
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
