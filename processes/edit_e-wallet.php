<?php
session_start();
require '../config/db.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = intval($_POST['id']);
$walletName = trim($_POST['walletName']);
$accountNumber = trim($_POST['accountNumber']);
$accountLabel = trim($_POST['accountLabel']);
$balance = floatval($_POST['balance']);
$status = intval($_POST['status']);

if (empty($walletName) || empty($accountNumber)) {
    echo json_encode(["success" => false, "message" => "Required fields missing"]);
    exit;
}

$stmt = $con->prepare("
    UPDATE wallet_accounts 
    SET account_name = ?, 
        account_number = ?, 
        label = ?, 
        current_balance = ?, 
        status = ?, 
        updated_at = NOW()
    WHERE id = ?
");

$stmt->bind_param("sssddi",
    $walletName,
    $accountNumber,
    $accountLabel,
    $balance,
    $status,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
