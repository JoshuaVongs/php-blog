<?php
header('Content-Type: application/json');

$input = file_get_contents('php://input');

$data = json_decode($input, true);

$numbers = $data['numbers'] ?? null;
$operation = $data['operation'] ?? null;



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $total = 1;
    for ($i = 0; $i < count($numbers); $i++) {
        switch ($operation) {
            case '*':
                $total *= $numbers[$i];
                $response = [
                    "result" => $total,
                    "operation_type" => "multiplication",
                    "message" => "succesful",
                ];

                break;
            case '+':
                $total += $numbers[$i];
                $response = [
                    "result" => $total,
                    "operation_type" => "addition",
                    "message" => "succesful",
                ];
            default:
                echo "no operation";
                break;
        }
    
    }
echo json_encode($response);
};
