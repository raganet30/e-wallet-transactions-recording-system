<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$branch_id = $_SESSION['branch_id'];

// Fetch last COH log for this branch
$stmt = $con->prepare("
    SELECT c.created_at, c.note, u.name AS updated_by
    FROM coh_logs c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.branch_id = ?
    ORDER BY c.created_at DESC
    LIMIT 1
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $dateTime = new DateTime($row['created_at']);
    echo json_encode([
        "success" => true,
        "date" => $dateTime->format("F j, Y"),
        "time" => $dateTime->format("g:i A"),
        "updated_by" => $row['updated_by'] ?? "Unknown",
        "note" => $row['note'] ?? ""
    ]);
} else {
    echo json_encode([
        "success" => false,
        "date" => "",
        "time" => "",
        "updated_by" => "",
        "note" => ""
    ]);
}
