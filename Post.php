<?php
header('Content-Type: application/json');
$data = json_decode($input, true);

$description = $data['description'] ?? '';
$price = $data['price'] ?? 0.0;
$stock = $data['stock'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['name']) && isset($data['category'])) {
        $response = [
            'status' => 'success',
            'message' => 'Product added successfully',
            'data' => [
                'name' => $data['name'],
                'category' => $data['category'],
                'description' => $description,
                'price' => $price,
                'stock' => $stock
            ]
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields (name and/or category)'
        ];
        http_response_code(400); // Bad request
    }

    // Return JSON response
    echo json_encode($response);
} else {
    // Handle unsupported request methods
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
