<?php
header("Access-Control-Allow-Origin:http://127.0.0.1:5500"); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header("Access-Control-Allow-Credentials: true");

include '../db.php';

$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);

$categories = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            'id' => $row['id'],
            'name' => $row['name']
        );
    }
}
header('Content-Type: application/json');
echo json_encode($categories);

$conn->close();
?>
