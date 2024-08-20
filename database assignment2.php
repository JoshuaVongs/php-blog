<?php
include("./db.config.php");
$pdo = $dbconnection;
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'];
$title = $data['title'];
$content = $data['content'];
$image_url = $data['image_url'];


try {
    $stmt = $pdo->prepare("INSERT INTO post (user_id, title, content, image_url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $content, $image_url]);
    echo json_encode(['message' => 'User added successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to add user: ' . $e->getMessage()]);
}
