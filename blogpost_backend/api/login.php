<?php
header("Access-Control-Allow-Origin:http://127.0.0.1:5500"); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true"); // Allow cookies/session to be sent
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Please fill in both fields.']);
        http_response_code(400);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, email, username, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user_email, $user_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $user_name;
            $_SESSION['loggedin'] = true;
            echo json_encode([
                'message' => 'Login successful',
            ]);
            http_response_code(200);
        } else {
            echo json_encode(['error' => 'Invalid password.']);
            http_response_code(401);
        }
    } else {
        echo json_encode(['error' => 'User not found.']);
        http_response_code(404);
    }

    $stmt->close();
    $conn->close();
}
