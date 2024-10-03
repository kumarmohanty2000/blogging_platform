<?php
header("Access-Control-Allow-Origin:http://127.0.0.1:5500"); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
session_start();

include '../db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$sql = "SELECT * FROM posts ORDER BY publish_date DESC";
$result = $conn->query($sql);

$blogs = [];
while ($row = $result->fetch_assoc()) {
    $blogs[] = $row;
}

echo json_encode("hi how are you");
?>