<?php
session_start();
require '../config/db.php';

header('Content-Type: application/json');

/**
 * SESSION CHECK
 */
if (!isset($_SESSION['branch_id'], $_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.'
    ]);
    exit;
}

$branch_id = (int) $_SESSION['branch_id'];

/**
 * CHECK TODAY'S COH INITIAL BALANCE
 */
$stmt = $con->prepare("
    SELECT id
    FROM coh_logs
    WHERE branch_id = ?
      AND DATE(created_at) = CURDATE()
    LIMIT 1
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$has_coh = $stmt->get_result()->num_rows;

/**
 * CHECK TODAY'S E-WALLET INITIAL BALANCE
 */
$stmt = $con->prepare("
    SELECT id
    FROM e_wallet_logs
    WHERE branch_id = ?
      AND DATE(created_at) = CURDATE()
    LIMIT 1
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$has_wallet = $stmt->get_result()->num_rows;

/**
 * IF EITHER IS MISSING → BLOCK TRANSACTIONS
 */
if (!$has_coh && !$has_wallet) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial COH and e-wallet balance for today first before adding transaction.'
    ]);
    exit;
}


if (!$has_coh) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial COH for today first before adding transaction.'
    ]);
    exit;
}
if (!$has_wallet) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial e-wallet balance for today first before adding transaction.'
    ]);
    exit;
}

/**
 * FETCH CURRENT COH
 */
$stmt = $con->prepare("
    SELECT current_coh
    FROM branches
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Branch not found.'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'coh' => (float) $result['current_coh']
]);
