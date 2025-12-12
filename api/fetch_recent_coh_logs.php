<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$branch_id = $_SESSION['branch_id'];
$limit = 5; // number of recent logs to show

$stmt = $con->prepare("
    SELECT c.previous_balance, c.new_balance, c.note, c.type, c.created_at, u.name AS updated_by
    FROM coh_logs c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.branch_id = ?
    ORDER BY c.created_at DESC
    LIMIT ?
");
$stmt->bind_param("ii", $branch_id, $limit);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $dateTime = new DateTime($row['created_at']);

    // Build description with previous → new balance
    $type = $row['type'] ? ucfirst(str_replace("_", " ", $row['type'])) : "Updated";
    $prev = number_format($row['previous_balance'], 2);
    $new = number_format($row['new_balance'], 2);

    $logs[] = [
        "description" => "$type: ₱$prev → ₱$new",
        "datetime" => $dateTime->format("M j, g:i A"),
        "updated_by" => $row['updated_by'] ?? "Unknown"
    ];
}

echo json_encode(["success" => true, "logs" => $logs]);
