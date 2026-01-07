<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = intval($_POST['id']);
$walletName = trim($_POST['walletName']);
$accountNumber = trim($_POST['accountNumber']);
$accountLabel = trim($_POST['accountLabel']) || null;
$balance = floatval($_POST['balance']);
$status = intval($_POST['status']);


$changeReason = $_POST['changeReason'] ?? '';
$remarks = trim($_POST['remarks'] ?? '');
$user_id = $_SESSION['user_id'] ?? null;
$branch_id = $_SESSION['branch_id'] ?? null;


if (empty($walletName) || empty($accountNumber)) {
    echo json_encode(["success" => false, "message" => "Required fields missing"]);
    exit;
}

// Fetch old balance for audit log
$stmt = $con->prepare("SELECT current_balance FROM wallet_accounts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$old_balance = 0;
if ($row = $result->fetch_assoc()) {
    $old_balance = $row['current_balance'];
}

$stmt = $con->prepare("
    UPDATE wallet_accounts 
    SET account_name = ?, 
        account_number = ?, 
        label = ?, 
        current_balance = ?, 
        status = ?, 
        updated_at = NOW()
    WHERE id = ?
");

$stmt->bind_param(
    "sssddi",
    $walletName,
    $accountNumber,
    $accountLabel,
    $balance,
    $status,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);

    addAuditLog(
        null,
        "Edited e-wallet account: ({$walletName} {$accountNumber}) Old Balance: {$old_balance} | New Balance: {$balance}",
        "update_wallet"
    );


    // insert into e-wallet logs as well, id	branch_id	user_id	type	amount	previous_balance	new_balance	note	created_at	
    // Determine transaction type & amount
    $amount = abs($balance - $old_balance);
    $type = '';

    switch ($changeReason) {
        case 'initial_balance':
            $type = 'initial_balance';
            break;
        case 'add_e-wallet':
            $type = 'add_e-wallet';
            break;
        case 'deduct_e-wallet':
            $type = 'deduct_e-wallet';
            break;
        default:
            $type = 'adjustment';
            break;
    }

    // Insert into e_wallet_logs
    $logStmt = $con->prepare("
    INSERT INTO e_wallet_logs
    (branch_id, user_id, type, amount, previous_balance, new_balance, note, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");

    $logStmt->bind_param(
        "iissdds",
        $branch_id,
        $user_id,
        $type,
        $amount,
        $old_balance,
        $balance,
        $remarks
    );

    $logStmt->execute();



} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
