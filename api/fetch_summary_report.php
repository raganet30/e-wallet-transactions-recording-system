<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';
header('Content-Type: application/json');

$role = $_SESSION['role'] ?? null;
$session_branch = $_SESSION['branch_id'] ?? null;

$branch_id = $_GET['branch_id'] ?? '';
$wallet_id = $_GET['wallet_id'] ?? '';
$type      = $_GET['transaction_type'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';

$where = ["t.is_deleted = 0"];
$params = [];
$types = "";

// Branch filter
if($role==='super_admin'){
    if($branch_id !== "" && $branch_id !== "all"){
        $where[] = "t.branch_id = ?";
        $params[] = $branch_id;
        $types .= "i";
    }
} elseif(!empty($session_branch)){
    $where[] = "t.branch_id = ?";
    $params[] = $session_branch;
    $types .= "i";
}

// E-wallet filter
if(!empty($wallet_id)){
    $where[] = "t.wallet_id = ?";
    $params[] = $wallet_id;
    $types .= "i";
}

// Transaction type filter
if(!empty($type)){
    $where[] = "t.type = ?";
    $params[] = $type;
    $types .= "s";
}

// Date filter
if(!empty($date_from)){
    $where[] = "DATE(t.created_at) >= ?";
    $params[] = $date_from;
    $types .= "s";
}
if(!empty($date_to)){
    $where[] = "DATE(t.created_at) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

$whereSQL = count($where) ? "WHERE ".implode(" AND ", $where) : "";

// Aggregate totals by branch, wallet, type
$query = "
SELECT 
    b.branch_name,
    w.account_name AS wallet_name,
    t.type,
    SUM(t.amount) AS amount,
    SUM(t.charge) AS charge,
    SUM(t.total) AS total
FROM transactions t
LEFT JOIN branches b ON b.id = t.branch_id
LEFT JOIN wallet_accounts w ON w.id = t.wallet_id
$whereSQL
GROUP BY b.branch_name, w.account_name, t.type
ORDER BY b.branch_name ASC, w.account_name ASC, t.type ASC
";

$stmt = $con->prepare($query);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode(['data'=>$data]);
