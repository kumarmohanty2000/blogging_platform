<?php
session_start();
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$conn = new mysqli('host', 'username', 'password', 'database');

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'];
$title = $data['title'];
$content = $data['content'];
$categories = $data['categories'];
$tags = $data['tags'];

$sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['user_id']);
$stmt->execute();

$conn->query("DELETE FROM post_categories WHERE post_id = $post_id");
$conn->query("DELETE FROM post_tags WHERE post_id = $post_id");

foreach ($categories as $category_id) {
    $conn->query("INSERT INTO post_categories (post_id, category_id) VALUES ($post_id, $category_id)");
}

foreach ($tags as $tag_id) {
    $conn->query("INSERT INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
}

echo json_encode(['success' => true]);
?>
