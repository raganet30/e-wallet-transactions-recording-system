<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

$role = $_SESSION['role'] ?? null;
$session_branch = $_SESSION['branch_id'] ?? null;

$selected = $_GET['branch_id'] ?? ""; // always defined

// ⭐ FORCE DEFAULT = ALL for super_admin
if ($role === "super_admin" && ($selected === "" || $selected === null)) {
    $selected = "all";
}

// CASE 1: super_admin → ALL branches
if ($role === "super_admin" && $selected === "all") {

    $query = "
        SELECT id, account_name, account_number, label, current_balance, status, created_at, updated_at
        FROM wallet_accounts
        ORDER BY created_at DESC
    ";
    $stmt = $con->prepare($query);

// CASE 2: super_admin selected specific branch
} elseif ($role === "super_admin" && is_numeric($selected)) {

    $query = "
        SELECT id, account_name, account_number, label, current_balance, status, created_at, updated_at
        FROM wallet_accounts
        WHERE branch_id = ?
        ORDER BY created_at DESC
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected);

// CASE 3: Normal user → only their own branch
} elseif (!empty($session_branch)) {

    $query = "
        SELECT id, account_name, account_number, label, current_balance, status, created_at, updated_at
        FROM wallet_accounts
        WHERE branch_id = ?
        ORDER BY created_at DESC
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $session_branch);

// CASE 4: No branch
} else {
    echo json_encode(["data" => []]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$wallets = [];
$no = 1;

while ($row = $result->fetch_assoc()) {
    $wallets[] = [
        "no" => $no++,
        "account_name" => $row['account_name'],
        "account_number" => $row['account_number'],
        "label" => $row['label'],
        "current_balance" => number_format($row['current_balance'], 2),
        "status" => ($row['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'),
        "created_at" => $row['created_at'],
        "updated_at" => $row['updated_at'],
        "id" => $row['id']
    ];
}

echo json_encode(["data" => $wallets]);
?>
