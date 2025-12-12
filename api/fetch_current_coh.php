<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$branch_id = $_SESSION['branch_id'];

$stmt = $con->prepare("SELECT current_coh FROM branches WHERE id = ?");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "current_coh" => $row['current_coh']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Branch not found"]);
}
