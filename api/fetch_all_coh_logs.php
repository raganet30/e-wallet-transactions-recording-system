<?php
session_start();
require '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'admin';
$user_branch_id = $_SESSION['branch_id'] ?? null;

/**
 * ===============================
 * FETCH DISTINCT TYPES (FOR FILTER)
 * ===============================
 */
if (isset($_GET['get_types'])) {
    $res = $con->query("SELECT DISTINCT type FROM coh_logs ORDER BY type");
    $types = [];

    while ($row = $res->fetch_assoc()) {
        $types[] = $row['type'];
    }

    echo json_encode([
        'success' => true,
        'types' => $types
    ]);
    exit;
}

/**
 * ===============================
 * FILTER PARAMETERS
 * ===============================
 */
$branch_id = $_GET['branch_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$type = $_GET['type'] ?? '';

$where = [];
$params = [];
$types_param = "";

/**
 * ===============================
 * ROLE-BASED BRANCH FILTERING
 * ===============================
 */
if ($role !== 'super_admin') {
    // Admin: force own branch
    $where[] = "cl.branch_id = ?";
    $params[] = $user_branch_id;
    $types_param .= "i";
} elseif ($branch_id !== '') {
    // Super admin with branch filter
    $where[] = "cl.branch_id = ?";
    $params[] = $branch_id;
    $types_param .= "i";
}

/**
 * ===============================
 * DATE FILTERING
 * ===============================
 */
if ($date_from !== '') {
    $where[] = "DATE(cl.created_at) >= ?";
    $params[] = $date_from;
    $types_param .= "s";
}

if ($date_to !== '') {
    $where[] = "DATE(cl.created_at) <= ?";
    $params[] = $date_to;
    $types_param .= "s";
}

/**
 * ===============================
 * TYPE FILTER
 * ===============================
 */
if ($type !== '') {
    $where[] = "cl.type = ?";
    $params[] = $type;
    $types_param .= "s";
}

/**
 * ===============================
 * MAIN QUERY
 * ===============================
 */
$sql = "
    SELECT 
        cl.created_at,
        cl.type,
        cl.previous_balance,
        cl.new_balance,
        cl.note,
        u.name AS updated_by
    FROM coh_logs cl
    JOIN users u ON u.id = cl.user_id
";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY cl.created_at DESC";

$stmt = $con->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types_param, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $data
]);
