<?php
include("./db.config.php");
$pdo = $dbconnection;
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->title) && isset($data->content) && isset($data->image_url)) {
    $id = $data->id;
    $title = $data->title;
    $content = $data->content;
    $image_url = $data->image_url;

    $stmt = $pdo->prepare('UPDATE post SET title = ?, content = ?, image_url = ? WHERE id = ?');
    if ($stmt->execute([$title, $content, $image_url, $id])) {
        echo json_encode(['message' => 'Blog post updated successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update blog post']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
