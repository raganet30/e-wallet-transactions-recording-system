<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';

header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['user_id'], $_SESSION['branch_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$branch_id = $_SESSION['branch_id'];
$user_id = $_SESSION['user_id'];

$newAmount = isset($_POST['newAmount']) ? floatval($_POST['newAmount']) : null;
$reason = trim($_POST['reason'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if ($newAmount === null || $newAmount < 0 || empty($reason)) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

// Get previous balance
$stmt = $con->prepare("SELECT current_coh FROM branches WHERE id = ?");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode(["success" => false, "message" => "Branch not found"]);
    exit;
}

$row = $result->fetch_assoc();
$previous_balance = floatval($row['current_coh']);



$new_balance = $previous_balance;

switch ($reason) {

    case 'initial_balance':
        // Set new COH
        $new_balance = $newAmount;
        break;

    case 'add_cash':
        // Add to existing COH
        $new_balance = $previous_balance + $newAmount;
        break;

    case 'deduct_cash':
        // Validate sufficient funds
        if ($newAmount > $previous_balance) {
            echo json_encode([
                "success" => false,
                "message" => "Insufficient cash on hand. Update COH failed."
            ]);
            exit;
        }
        $new_balance = $previous_balance - $newAmount;
        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Invalid reason selected."
        ]);
        exit;
}


// Start transaction
$con->begin_transaction();

try {

    // Update branches.current_coh
    $update = $con->prepare("UPDATE branches SET current_coh = ? WHERE id = ?");
    $update->bind_param("di", $new_balance, $branch_id);
    $update->execute();

    $log = $con->prepare("
    INSERT INTO coh_logs 
    (branch_id, user_id, transaction_id, type, amount, previous_balance, new_balance, note, created_at)
    VALUES (?, ?, NULL, ?, ?, ?, ?, ?, NOW())
");

    $log->bind_param(
        "iisddds",
        $branch_id,
        $user_id,
        $reason,
        $newAmount,          // adjustment amount
        $previous_balance,
        $new_balance,
        $remarks
    );
    $log->execute();// execute only once


    // Commit
    $con->commit();

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
