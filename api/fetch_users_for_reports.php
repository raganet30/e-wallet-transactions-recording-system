<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';
header('Content-Type: application/json');

$role = $_SESSION['role'] ?? '';
$session_branch = $_SESSION['branch_id'] ?? null;
$branch_id = $_GET['branch_id'] ?? null;

$where = [];
$params = [];
$types = '';

if ($role === 'super_admin') {
    if (!empty($branch_id) && $branch_id !== 'all') {
        $where[] = "branch_id = ?";
        $params[] = $branch_id;
        $types .= 'i';
    }
} else {
    $where[] = "branch_id = ?";
    $params[] = $session_branch;
    $types .= 'i';
}

$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT id, name FROM users $whereSQL ORDER BY name ASC";
$stmt = $con->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
