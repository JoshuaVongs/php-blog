<?php
include("./db.config.php");
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$pdo = $dbconnection;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($data['email']) && isset($data['password'])) {
        $email = $data['email'];
        $password = $data['password'];


        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        $user = $stmt->fetch();
    
            if ($user) {
                 echo json_encode(["status" => "success", "message" => "Welcome"]);
            } else {
                echo json_encode(["status" => "error", "message" => "email or password does not exist"]);
            } 
    }else{
       echo json_encode(["message" => "invalid input"]);
    }
} else {
    http_response_code(405); 
    echo json_encode(['error' => 'Method not allowed']);
}
