<?php
header('Content-Type: application/json');
session_start();
require '../config/db.php';

$response = ["success" => false];

// Validate inputs
$branch_name    = trim($_POST['branch_name'] ?? '');
$branch_address = trim($_POST['branch_address'] ?? '');
$current_coh    = trim($_POST['current_coh'] ?? '');
$status_text    = trim($_POST['branch_status'] ?? '');

// Convert status text → numeric (1 = Active, 0 = Inactive)
$status = ($status_text === "Active") ? 1 : 0;

// Check empty values
if ($branch_name === '' || $branch_address === '' || $current_coh === '') {
    $response['message'] = "All fields are required.";
    echo json_encode($response);
    exit;
}

// Prepare query
$stmt = $con->prepare("
    INSERT INTO branches (branch_name, address, current_coh, status, created_at)
    VALUES (?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssii", $branch_name, $branch_address, $current_coh, $status);

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['message'] = "Database error: " . $con->error;
}

echo json_encode($response);
exit;
