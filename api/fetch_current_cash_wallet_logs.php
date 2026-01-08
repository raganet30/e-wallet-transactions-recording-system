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
 * Both GCash and Maya must exist today
 */
$stmt = $con->prepare("
    SELECT COUNT(DISTINCT wallet_type) AS wallet_count
    FROM e_wallet_logs
    WHERE branch_id = ?
      AND wallet_type IN ('GCash', 'Maya')
      AND DATE(created_at) = CURDATE()
");
$stmt->bind_param("i", $branch_id);
$stmt->execute();

$row = $stmt->get_result()->fetch_assoc();
$has_wallet = ((int) $row['wallet_count'] === 2);


/**
 * IF EITHER IS MISSING → BLOCK TRANSACTIONS
 */
if (!$has_coh && !$has_wallet) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial COH and e-wallet balance for today before adding transaction.'
    ]);
    exit;
}


if (!$has_coh) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial COH for today before adding transaction.'
    ]);
    exit;
}
if (!$has_wallet) {
    echo json_encode([
        'success' => false,
        'message' => 'Please set initial e-wallet balance for all wallet types before adding transaction.'
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
