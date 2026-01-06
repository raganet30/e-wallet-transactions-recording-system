<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';
header('Content-Type: application/json');

$role = $_SESSION['role'] ?? null;
$session_branch = $_SESSION['branch_id'] ?? null;

// Filters from GET
$branch_id = $_GET['branch_id'] ?? '';
$date      = $_GET['date'] ?? '';       // daily
$month     = $_GET['month'] ?? '';      // monthly
$date_from = $_GET['date_from'] ?? '';  // custom
$date_to   = $_GET['date_to'] ?? '';
$wallet_id = $_GET['wallet_id'] ?? '';
$type      = $_GET['transaction_type'] ?? '';
$user_id   = $_GET['user_id'] ?? '';



$where = ["t.is_deleted = 0"];
$params = [];
$types = "";

/* =========================
   BRANCH FILTER
========================= */
if ($role === "super_admin") {
    if ($branch_id !== "" && $branch_id !== "all") {
        $where[] = "t.branch_id = ?";
        $params[] = $branch_id;
        $types .= "i";
    }
} elseif (!empty($session_branch)) {
    $where[] = "t.branch_id = ?";
    $params[] = $session_branch;
    $types .= "i";
}

/* =========================
   DATE FILTERS
========================= */
if (!empty($date)) {
    $where[] = "DATE(t.created_at) = ?";
    $params[] = $date;
    $types .= "s";
}

if (!empty($month)) {
    $where[] = "DATE_FORMAT(t.created_at, '%Y-%m') = ?";
    $params[] = $month;
    $types .= "s";
}

if (!empty($date_from)) {
    $where[] = "DATE(t.created_at) >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if (!empty($date_to)) {
    $where[] = "DATE(t.created_at) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

/* =========================
   E-WALLET FILTER
========================= */
if (!empty($wallet_id)) {
    $where[] = "t.wallet_id = ?";
    $params[] = $wallet_id;
    $types .= "i";
}

/* =========================
   TRANSACTION TYPE FILTER
========================= */
if (!empty($type)) {
    $where[] = "t.type = ?";
    $params[] = $type;
    $types .= "s";
}

/* =========================
   USER FILTER
========================= */
if (!empty($user_id)) {
    $where[] = "t.user_id = ?";
    $params[] = $user_id;
    $types .= "i";
}




/* =========================
   FINAL QUERY
========================= */
$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

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
    $whereSQL
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

echo json_encode(['data' => $data]);
