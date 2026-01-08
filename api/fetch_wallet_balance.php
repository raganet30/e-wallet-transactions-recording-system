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
$wallet_id = isset($_GET['wallet_id']) ? (int) $_GET['wallet_id'] : 0;

/**
 * BASIC VALIDATION
 */
if ($wallet_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid wallet ID.'
    ]);
    exit;
}

/**
 * FETCH WALLET BALANCE (BRANCH-SCOPED)
 */
$stmt = $con->prepare("
    SELECT current_balance
    FROM wallet_accounts
    WHERE id = ?
      AND branch_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $wallet_id, $branch_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Wallet not found or access denied.'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'balance' => (float) $result['current_balance']
]);
