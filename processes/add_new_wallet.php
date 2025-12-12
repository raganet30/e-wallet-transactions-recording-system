<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$branch_id = $_SESSION['branch_id'] ?? null;

if (!$branch_id) {
    echo json_encode(["success" => false, "message" => "Branch ID missing."]);
    exit;
}

$walletName = trim($_POST['walletName']);
$accountNumber = trim($_POST['accountNumber']);
$accountLabel = trim($_POST['accountLabel'] ?? "");
$initialBalance = floatval($_POST['initialBalance']);
$status = intval($_POST['status']);

if (empty($walletName) || empty($accountNumber)) {
    echo json_encode(["success" => false, "message" => "Required fields missing."]);
    exit;
}

$stmt = $con->prepare("
    INSERT INTO wallet_accounts 
    (branch_id, account_name, account_number, label, current_balance, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$stmt->bind_param(
    "isssdi",
    $branch_id,
    $walletName,
    $accountNumber,
    $accountLabel,
    $initialBalance,
    $status
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $stmt->error
    ]);
}
