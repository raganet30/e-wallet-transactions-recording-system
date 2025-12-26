<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $con->prepare("
    SELECT name, username 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode([
    'success' => true,
    'data' => $result->fetch_assoc()
]);
