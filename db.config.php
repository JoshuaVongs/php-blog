<?php
$type = "mysql";
$host = "localhost";
$db = "blog";
$username = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,
];
$dsn = "$type:host=$host;dbname=$db";

$dbconnection = new PDO($dsn, $username, $password, $options);
if (!$dbconnection){
    echo "connection error:" . $dbconnection->errorInfo();
}