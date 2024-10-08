<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); 
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

$data = json_decode(file_get_contents("php://input"), true);
$title = $data['title'];
$content = $data['content'];
$categories = $data['categories'];
$tags = $data['tags'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO posts (title, content, author_id, publish_date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $title, $content, $user_id);
$stmt->execute();

$post_id = $stmt->insert_id;

foreach ($categories as $category_id) {
    $conn->query("INSERT INTO post_categories (post_id, category_id) VALUES ($post_id, $category_id)");
}

foreach ($tags as $tag_id) {
    $conn->query("INSERT INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
}

echo json_encode(['success' => true]);
?>