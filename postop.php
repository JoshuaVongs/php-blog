<?php
include "./db.config.php";
$pdo = $dbconnection;
$data = json_decode(file_get_contents('php://input'), true);


function createPost($pdo, $user_id, $title, $content, $image_url = null, $like_id = null, $category_id) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO post (user_id, title, content, image_url, like_id, category_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        if ($stmt->execute([$user_id, $title, $content, $image_url, $like_id, $category_id])) {
            return ['message' => 'Post created successfully', 'post_id' => $pdo->lastInsertId()];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to create post: ' . $e->getMessage()];
    }
}


function getPosts($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM post');
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return ['error' => 'Failed to retrieve posts: ' . $e->getMessage()];
    }
}


function getPostById($pdo, $id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM post WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return ['error' => 'Failed to retrieve post: ' . $e->getMessage()];
    }
}
function updatePost($pdo, $id, $title, $content, $image_url = null, $like_id = null, $category_id) {
    try {
        $stmt = $pdo->prepare('
            UPDATE post
            SET title = ?, content = ?, image_url = ?, like_id = ?, category_id = ?
            WHERE id = ?
        ');
        if ($stmt->execute([$title, $content, $image_url, $like_id, $category_id, $id])) {
            return ['message' => 'Post updated successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to update post: ' . $e->getMessage()];
    }
}


function deletePost($pdo, $id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM post WHERE id = ?');
        if ($stmt->execute([$id])) {
            return ['message' => 'Post deleted successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to delete post: ' . $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = createPost(
        $pdo,
        $data['user_id'],
        $data['title'],
        $data['content'],
        $data['image_url'] ?? null,
        $data['like_id'] ?? null,
        $data['category_id']
    );
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $response = getPostById($pdo, (int)$_GET['id']);
    } else {
        $response = getPosts($pdo);
    }
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $response = updatePost(
        $pdo,
        $data['id'],
        $data['title'],
        $data['content'],
        $data['image_url'] ?? null,
        $data['like_id'] ?? null,
        $data['category_id']
    );
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = deletePost($pdo, $data['id']);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Unsupported HTTP method']);
}
?>
