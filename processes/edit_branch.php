<?php
header('Content-Type: application/json');
session_start();
require '../config/db.php';

$response = ["success" => false];

// Get posted values
$branch_id      = intval($_POST['branch_id'] ?? 0);
$name           = trim($_POST['edit_branch_name'] ?? '');
$address        = trim($_POST['edit_branch_address'] ?? '');
$current_coh    = trim($_POST['edit_current_coh'] ?? '');
$status_text    = trim($_POST['edit_branch_status'] ?? '');

// Convert text → numeric
$status = ($status_text === "Active") ? 1 : 0;

// Validate
if ($branch_id <= 0 || $name === '' || $address === '' || $current_coh === '') {
    $response['message'] = "Invalid or incomplete data.";
    echo json_encode($response);
    exit;
}

$stmt = $con->prepare("
    UPDATE branches
    SET branch_name = ?, address = ?, current_coh = ?, status = ?
    WHERE id = ?
");

$stmt->bind_param("ssiii", $name, $address, $current_coh, $status, $branch_id);

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['message'] = "Database error: " . $con->error;
}

echo json_encode($response);
exit;
