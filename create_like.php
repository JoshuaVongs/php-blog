<?php
include("./db.config.php");
$pdo = $dbconnection;

$data = json_decode(file_get_contents("php://input"));

if (isset($data->post_id) && isset($data->like)) {
    $post_id = (int)$data->post_id;
    $like = (int)$data->like; // Ensures like is either 1 or 0

    if ($like === 1 || $like === 0) {
        $stmt = $pdo->prepare('INSERT INTO likes (post_id, `like`) VALUES (?, ?)');
        if ($stmt->execute([$post_id, $like])) {
            echo json_encode(['message' => 'Like added successfully']);
        } else {
            echo json_encode(['message' => 'Failed to add like']);
        }
    } else {
        echo json_encode(['message' => 'Invalid like value']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
