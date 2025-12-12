<?php
session_start();
require  '../config/db.php';
header('Content-Type: application/json');

$branch_id = $_SESSION['branch_id'] ?? null;

if (!$branch_id) {
    echo json_encode(["data" => []]);
    exit;
}

// Fetch wallet accounts for the branch
$stmt = $con->prepare("
    SELECT id, account_name, account_number, label, current_balance, status, created_at, updated_at
    FROM wallet_accounts
    WHERE branch_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

$wallets = [];
$no = 1;

while ($row = $result->fetch_assoc()) {
    $statusBadge = $row['status'] == 1 
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-danger">Inactive</span>';

    $wallets[] = [
        "no" => $no++,
        "account_name" => $row['account_name'],
        "account_number" => $row['account_number'],
        "label" => $row['label'],
        "current_balance" => number_format($row['current_balance'], 2),
        "status" => $statusBadge,
        "created_at" => $row['created_at'],
        "updated_at" => $row['updated_at'],
        "id" => $row['id']
    ];
}

echo json_encode(["data" => $wallets]);
