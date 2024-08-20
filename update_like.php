<?php
include("./db.config.php");
$pdo = $dbconnection;

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->like)) {
    $id = (int)$data->id;
    $like = (int)$data->like; // Ensures like is either 1 or 0

    if ($like === 1 || $like === 0) {
        $stmt = $pdo->prepare('UPDATE likes SET `like` = ? WHERE id = ?');
        if ($stmt->execute([$like, $id])) {
            echo json_encode(['message' => 'Like updated successfully']);
        } else {
            echo json_encode(['message' => 'Failed to update like']);
        }
    } else {
        echo json_encode(['message' => 'Invalid like value']);
    }
} else {
    echo json_encode(['message' => 'Invalid input']);
}
?>
