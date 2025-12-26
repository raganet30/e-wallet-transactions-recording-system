<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

$branch_id = (int) $_SESSION['branch_id'];

$query = "SELECT SUM(charge) AS grand_total_income FROM transactions WHERE is_deleted = 0 AND branch_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$grand_total_income = (float) ($row['grand_total_income'] ?? 0);

echo json_encode([
    'success' => true,
    'grand_total_income' => $grand_total_income
]);
