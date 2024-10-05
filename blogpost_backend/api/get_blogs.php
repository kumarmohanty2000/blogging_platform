<?php
header("Access-Control-Allow-Origin:http://127.0.0.1:5500"); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
session_start();

include '../db.php';
$sql = "SELECT b.id, b.title, b.content, b.publish_date, GROUP_CONCAT(c.name) AS categories, GROUP_CONCAT(t.name) AS tags
        FROM blogs b
        LEFT JOIN blog_categories bc ON b.id = bc.blog_id
        LEFT JOIN categories c ON bc.category_id = c.id
        LEFT JOIN blog_tags bt ON b.id = bt.blog_id
        LEFT JOIN tags t ON bt.tag_id = t.id
        GROUP BY b.id";
        
$result = $conn->query($sql);
$blogs = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'publish_date' => $row['publish_date'],
            'categories' => explode(',', $row['categories']),
            'tags' => explode(',', $row['tags'])
        );
    }
}

header('Content-Type: application/json');
echo json_encode($blogs);

$conn->close();
?>