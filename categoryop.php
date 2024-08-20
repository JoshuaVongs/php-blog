<?php
include "./db.config.php";
$pdo = $dbconnection;
$data = json_decode(file_get_contents("php://input"), true);


function createCategory($pdo, $name)
{
    try {
        $stmt = $pdo->prepare('
            INSERT INTO category (name)
            VALUES (?)
        ');
        if ($stmt->execute([$name])) {
            return ['message' => 'Category created successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to create category: ' . $e->getMessage()];
    }
}


function readCategory($pdo, $id)
{
    try {
        $stmt = $pdo->prepare('
            SELECT * FROM category WHERE id = ?
        ');
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            return $category;
        } else {
            return ['error' => 'Category not found'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to retrieve category: ' . $e->getMessage()];
    }
}


function updateCategory($pdo, $id, $name)
{
    try {
        $stmt = $pdo->prepare('
            UPDATE category
            SET name = ?
            WHERE id = ?
        ');
        if ($stmt->execute([$name, $id])) {
            return ['message' => 'Category updated successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to update category: ' . $e->getMessage()];
    }
}


function deleteCategory($pdo, $id)
{
    try {
        $stmt = $pdo->prepare('
            DELETE FROM category WHERE id = ?
        ');
        if ($stmt->execute([$id])) {
            return ['message' => 'Category deleted successfully'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Failed to delete category: ' . $e->getMessage()];
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($data['name'])) {
        $response = createCategory($pdo, $data['name']);
    } else {
        $response = ['error' => 'Name is required'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $response = readCategory($pdo, (int)$_GET['id']);
    } else {
        $response = ['error' => 'Category ID is required'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($data['id']) && isset($data['name'])) {
        $response = updateCategory($pdo, $data['id'], $data['name']);
    } else {
        $response = ['error' => 'ID and name are required'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    if (isset($data['id'])) {
        $response = deleteCategory($pdo, $data['id']);
    } else {
        $response = ['error' => 'Category ID is required'];
    }
} else {
    $response = ['error' => 'Unsupported HTTP method'];
}


echo json_encode($response);
