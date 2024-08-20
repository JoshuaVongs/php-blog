<?php
include "./db.config.php";
$pdo = $dbconnection;
$data = json_decode(file_get_contents('php://input'), true);

function isValidUser($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() > 0;
}


function isValidPost($pdo, $post_id) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM post WHERE id = ?');
    $stmt->execute([$post_id]);
    return $stmt->fetchColumn() > 0;
}

// Function to create a like
function createLike($pdo, $user_id, $post_id, $likes) {
    try {
        if (!isValidUser($pdo, $user_id)) {
            return ['error' => 'Invalid user ID'];
        }
        if (!isValidPost($pdo, $post_id)) {
            return ['error' => 'Invalid post ID'];
        }
        
        $stmt = $pdo->prepare('
            INSERT INTO likes (user_id, post_id, likes)
            VALUES (?, ?, ?)
        ');
        if ($stmt->execute([$user_id, $post_id, $likes])) {
            return ['message' => 'Like added successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to create like: ' . $e->getMessage()];
    }
}


function updateLike($pdo, $user_id, $post_id, $likes) {
    try {
        if (!isValidUser($pdo, $user_id)) {
            return ['error' => 'Invalid user ID'];
        }
        if (!isValidPost($pdo, $post_id)) {
            return ['error' => 'Invalid post ID'];
        }
        
        $stmt = $pdo->prepare('
            UPDATE likes
            SET likes = ?
            WHERE user_id = ? AND post_id = ?
        ');
        if ($stmt->execute([$likes, $user_id, $post_id])) {
            return ['message' => 'Like status updated successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to update like: ' . $e->getMessage()];
    }
}


function getTotalLikes($pdo, $post_id) {
    try {
        $stmt = $pdo->prepare('
            SELECT COUNT(*) AS total_likes
            FROM likes
            WHERE post_id = ? AND likes = 1
        ');
        $stmt->execute([$post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ['total_likes' => $result['total_likes']];
    } catch (PDOException $e) {
        return ['error' => 'Failed to retrieve total likes: ' . $e->getMessage()];
    }
}


function deleteLike($pdo, $user_id, $post_id) {
    try {
        if (!isValidUser($pdo, $user_id)) {
            return ['error' => 'Invalid user ID'];
        }
        if (!isValidPost($pdo, $post_id)) {
            return ['error' => 'Invalid post ID'];
        }
        
        $stmt = $pdo->prepare('DELETE FROM likes WHERE user_id = ? AND post_id = ?');
        if ($stmt->execute([$user_id, $post_id])) {
            return ['message' => 'Like deleted successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to delete like: ' . $e->getMessage()];
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($data['post_id']) && $data['user_id'] ) {
        // Create a like
        $response = createLike($pdo, $data['user_id'], $data['post_id'], $data['likes']);
    } else {
        $response = ['error' => 'Invalid action for POST request'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Update a like
    if (isset($data['user_id']) && isset($data['post_id']) && isset($data['likes'])) {
        $response = updateLike($pdo, $data['user_id'], $data['post_id'], $data['likes']);
    } else {
        $response = ['error' => 'User ID, Post ID, and likes status are required'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get total likes
    if (isset($_GET['post_id'])) {
        $response = getTotalLikes($pdo, (int)$_GET['post_id']);
    } else {
        $response = ['error' => 'Post ID is required'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Delete a like
    if (isset($data['user_id']) && isset($data['post_id'])) {
        $response = deleteLike($pdo, $data['user_id'], $data['post_id']);
    } else {
        $response = ['error' => 'User ID and Post ID are required'];
    }
} else {
    $response = ['error' => 'Unsupported HTTP method'];
}

// Output the response
echo json_encode($response);
?>