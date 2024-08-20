<?php
include("./db.config.php");
$pdo = $dbconnection;
$data = json_decode(file_get_contents('php://input'), true);
function createComment($pdo, $user_id, $post_id, $comment)
{
    try {
        $stmt = $pdo->prepare('INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)');
        if ($stmt->execute([$user_id, $post_id, $comment])) {
            return ['message' => 'Comment created successfully', 'comment_id' => $pdo->lastInsertId()];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to create comment: ' . $e->getMessage()];
    }
}

function getCommentsByPostId($pdo, $post_id)
{
    try {
        $stmt = $pdo->prepare('SELECT * FROM comments WHERE post_id = ?');
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => 'Failed to retrieve comments: ' . $e->getMessage()];
    }
}

function updateComment($pdo, $id, $comment)
{
    try {
        $stmt = $pdo->prepare('UPDATE comments SET comment = ? WHERE id = ?');
        if ($stmt->execute([$comment, $id])) {
            return ['message' => 'Comment updated successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to update comment: ' . $e->getMessage()];
    }
}

function deleteComment($pdo, $id)
{
    try {
        $stmt = $pdo->prepare('DELETE FROM comments WHERE id = ?');
        if ($stmt->execute([$id])) {
            return ['message' => 'Comment deleted successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to delete comment: ' . $e->getMessage()];
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = createComment($pdo, $data['user_id'], $data['post_id'], $data['comment']);
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['post_id'])) {
        $response = getCommentsByPostId($pdo, (int)$_GET['post_id']);
    } else {
        $response = ['error' => 'Post ID is required'];
    }
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $response = updateComment($pdo, $data['id'], $data['comment']);
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = deleteComment($pdo, $data['id']);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Unsupported HTTP method']);
}
