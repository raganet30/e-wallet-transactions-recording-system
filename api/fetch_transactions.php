<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

$role           = $_SESSION['role'] ?? null;
$session_branch = $_SESSION['branch_id'] ?? null;

$branch_id   = $_GET['branch_id'] ?? '';
$date_from   = $_GET['date_from'] ?? '';
$date_to     = $_GET['date_to'] ?? '';
$wallet_id   = $_GET['wallet_id'] ?? '';
$type        = $_GET['transaction_type'] ?? '';

$params = [];
$types  = "";
$where  = "WHERE t.is_deleted = 0";

/* =========================
   BRANCH CONTROL
========================= */
if ($role === "super_admin") {
    if ($branch_id !== "" && $branch_id !== "all") {
        $where .= " AND t.branch_id = ?";
        $params[] = $branch_id;
        $types .= "i";
    }
} elseif (!empty($session_branch)) {
    $where .= " AND t.branch_id = ?";
    $params[] = $session_branch;
    $types .= "i";
}

/* =========================
   DATE FILTER
========================= */
if (!empty($date_from)) {
    $where .= " AND DATE(t.created_at) >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if (!empty($date_to)) {
    $where .= " AND DATE(t.created_at) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

/* =========================
   E-WALLET FILTER
========================= */
if (!empty($wallet_id)) {
    $where .= " AND t.wallet_id = ?";
    $params[] = $wallet_id;
    $types .= "i";
}

/* =========================
   TRANSACTION TYPE
========================= */
if (!empty($type)) {
    $where .= " AND t.type = ?";
    $params[] = $type;
    $types .= "s";
}

/* =========================
   FINAL QUERY
========================= */
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
    $where
    ORDER BY t.created_at DESC
";

$stmt = $con->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
