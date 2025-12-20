<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

$role           = $_SESSION['role'] ?? null;
$session_branch = $_SESSION['branch_id'] ?? null;
$selected       = $_GET['branch_id'] ?? ""; // always defined

// FORCE DEFAULT = ALL for super_admin
if ($role === "super_admin" && ($selected === "" || $selected === null)) {
    $selected = "all";
}

/*
|--------------------------------------------------------------------------
| BUILD QUERY BASED ON ROLE & BRANCH
|--------------------------------------------------------------------------
*/

// CASE 1: super_admin → ALL branches
if ($role === "super_admin" && $selected === "all") {

    $query = "
        SELECT 
            t.id,
            t.reference_no,
            t.type,
            t.amount,
            t.charge,
            t.total,
            t.tendered_amount,
            t.change_amount,
            t.payment_thru,
            t.created_at,
            w.account_name AS wallet_name
        FROM transactions t
        LEFT JOIN wallet_accounts w ON w.id = t.wallet_id
        WHERE t.is_deleted = 0
        ORDER BY t.created_at DESC
    ";

    $stmt = $con->prepare($query);

// CASE 2: super_admin → specific branch
} elseif ($role === "super_admin" && is_numeric($selected)) {

    $query = "
        SELECT 
            t.id,
            t.reference_no,
            t.type,
            t.amount,
            t.charge,
            t.total,
            t.tendered_amount,
            t.change_amount,
            t.payment_thru,
            t.created_at,
            w.account_name AS wallet_name
        FROM transactions t
        LEFT JOIN wallet_accounts w ON w.id = t.wallet_id
        WHERE 
            t.is_deleted = 0
            AND t.branch_id = ?
        ORDER BY t.created_at DESC
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected);

// CASE 3: normal user → own branch only
} elseif (!empty($session_branch)) {

    $query = "
        SELECT 
            t.id,
            t.reference_no,
            t.type,
            t.amount,
            t.charge,
            t.total,
            t.tendered_amount,
            t.change_amount,
            t.payment_thru,
            t.created_at,
            w.account_name AS wallet_name
        FROM transactions t
        LEFT JOIN wallet_accounts w ON w.id = t.wallet_id
        WHERE 
            t.is_deleted = 0
            AND t.branch_id = ?
        ORDER BY t.created_at DESC
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $session_branch);

// CASE 4: no branch context
} else {
    echo json_encode(["data" => []]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

/*
|--------------------------------------------------------------------------
| FORMAT RESPONSE FOR DATATABLE
|--------------------------------------------------------------------------
*/

$transactions = [];
$no = 1;

while ($row = $result->fetch_assoc()) {

    $transactions[] = [
        "no"              => $no++,
        "id"              => $row['id'],
        "reference_no"    => $row['reference_no'],
        "wallet_name"     => $row['wallet_name'],
        "type"            => $row['type'],
        "amount"          => $row['amount'],
        "charge"          => $row['charge'],
        "total"           => $row['total'],
        "payment_thru"    => $row['payment_thru'],
        "tendered_amount" => $row['tendered_amount'],
        "change_amount"   => $row['change_amount'],
        "created_at"      => $row['created_at']
    ];
}

echo json_encode([
    "data" => $transactions
]);
