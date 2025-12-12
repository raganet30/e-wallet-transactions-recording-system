<?php
require '../config/db.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Missing wallet ID"]);
    exit;
}

$stmt = $con->prepare("SELECT * FROM wallet_accounts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows) {
    echo json_encode(["success" => true, "wallet" => $result->fetch_assoc()]);
} else {
    echo json_encode(["success" => false, "message" => "Wallet not found"]);
}
