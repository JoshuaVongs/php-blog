<?php
include("./db.config.php");
$pdo = $dbconnection;
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
            $stmt = $pdo->query("SELECT id, firstname, lastname, email FROM users WHERE id=1");
            $users = $stmt->fetchAll();
    
            echo json_encode($users);
    } catch (\Throwable $th) {
        echo json_encode(['error' => 'Failed to fetch users: ']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
