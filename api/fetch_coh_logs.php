<?php
session_start();
require  '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode(["data" => []]);
    exit;
}

$branch_id = $_SESSION['branch_id'];

// Fetch all logs for the branch
$stmt = $con->prepare("
    SELECT c.id, c.created_at, c.type, c.amount as amount_adjustment, c.previous_balance, c.new_balance, c.note, u.name AS updated_by
    FROM coh_logs c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.branch_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
$no = 1;
while ($row = $result->fetch_assoc()) {
    $logs[] = [
        "no" => $no++,
        "datetime" => (new DateTime($row['created_at']))->format("M j, Y, g:i A"),
        "type" => ucfirst(str_replace("_", " ", $row['type'])),
        "amount_adjustment" => "₱" . number_format($row['amount_adjustment'], 2),
        "previous_balance" => "₱" . number_format($row['previous_balance'], 2),
        "new_balance" => "₱" . number_format($row['new_balance'], 2),
        "updated_by" => $row['updated_by'] ?? "Unknown",
        "remarks" => $row['note'] ?? ""
    ];
}

echo json_encode(["data" => $logs]);
