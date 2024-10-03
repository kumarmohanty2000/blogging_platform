<?php

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'Please fill in all fields.']);
        http_response_code(400);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format.']);
        http_response_code(400);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Registration successful.']);
        http_response_code(200);
    } else {
        echo json_encode(['error' => 'Registration failed.']);
        http_response_code(500);
    }

    $stmt->close();
    $conn->close();
}
?>