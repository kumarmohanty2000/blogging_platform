<?php

header("Access-Control-Allow-Origin:http://127.0.0.1:5500"); 
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");
session_start();
session_unset(); 
session_destroy(); 
echo json_encode(['success' => true]);
exit(); 
?>