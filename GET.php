<?php
header('Content-Type: application/json');


$products = [
    [
        'id' => 1,
        'name' => 'David',
        'Dept' => 'Computer science',
        'Cgpa' => 4.50
    ],
    [
        'id' => 2,
        'name' => 'Elma',
        'Dept' => 'Medicine',
        'Cgpa' => 5.0
    ],
];

echo json_encode($products);
?>
