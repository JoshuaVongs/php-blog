<?php
include("./db.config.php");
$pdo = $dbconnection;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM post WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if ($post) {
        echo json_encode($post);
    } else {
        echo json_encode(['message' => 'Blog post not found']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
