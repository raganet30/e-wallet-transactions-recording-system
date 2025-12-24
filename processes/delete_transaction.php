<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'], $_SESSION['branch_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$user_id   = (int) $_SESSION['user_id'];
$branch_id = (int) $_SESSION['branch_id'];
$tx_id     = (int) ($_POST['id'] ?? 0);

if (!$tx_id) {
    echo json_encode(["success" => false, "message" => "Invalid transaction ID"]);
    exit;
}

$con->begin_transaction();

try {

    /* =========================
       LOCK TRANSACTION
    ========================== */
    $stmt = $con->prepare("
        SELECT *
        FROM transactions
        WHERE id = ? AND is_deleted = 0
        FOR UPDATE
    ");
    $stmt->bind_param("i", $tx_id);
    $stmt->execute();
    $tx = $stmt->get_result()->fetch_assoc();

    if (!$tx || $tx['branch_id'] != $branch_id) {
        throw new Exception("Transaction not found or access denied.");
    }

    /* =========================
       LOCK WALLET
    ========================== */
    $stmt = $con->prepare("
        SELECT current_balance, account_name
        FROM wallet_accounts
        WHERE id = ?
        FOR UPDATE
    ");
    $stmt->bind_param("i", $tx['wallet_id']);
    $stmt->execute();
    $wallet = $stmt->get_result()->fetch_assoc();

    if (!$wallet) {
        throw new Exception("Wallet not found.");
    }

    /* =========================
       LOCK BRANCH
    ========================== */
    $stmt = $con->prepare("
        SELECT current_coh
        FROM branches
        WHERE id = ?
        FOR UPDATE
    ");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $branch = $stmt->get_result()->fetch_assoc();

    if (!$branch) {
        throw new Exception("Branch not found.");
    }

    $wallet_balance = (float) $wallet['current_balance'];
    $coh            = (float) $branch['current_coh'];

    $amount   = (float) $tx['amount'];
    $charge   = (float) $tx['charge'];
    $type     = $tx['type'];
    $thru     = $tx['payment_thru'];

    $new_wallet_balance = $wallet_balance;
    $new_coh            = $coh;

    /* =========================
        REVERSE BUSINESS LOGIC
    ========================== */
    if ($type === 'Cash-in') {

        if ($thru === 'Cash') {
            // undo: wallet + amount, coh - (amount + fee)
            $new_wallet_balance = $wallet_balance + $amount;
            $new_coh            = $coh - ($amount + $charge);
        } else {
            // undo: wallet + amount - fee, coh - amount
            $new_wallet_balance = $wallet_balance + $amount - $charge;
            $new_coh            = $coh - $amount;
        }

    } else { // Cash-out

        if ($thru === 'Cash') {
            // undo: wallet - amount, coh + amount - fee
            $new_wallet_balance = $wallet_balance - $amount;
            $new_coh            = $coh + $amount - $charge;
        } else {
            // undo: wallet - (amount + fee), coh + amount
            $new_wallet_balance = $wallet_balance - ($amount + $charge);
            $new_coh            = $coh + $amount;
        }
    }

    if ($new_wallet_balance < 0 || $new_coh < 0) {
        throw new Exception("Reversal would cause negative balance.");
    }

    /* =========================
       SOFT DELETE
    ========================== */
    $stmt = $con->prepare("
        UPDATE transactions
        SET is_deleted = 1, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("i", $tx_id);
    $stmt->execute();

    /* =========================
       UPDATE WALLET
    ========================== */
    $stmt = $con->prepare("
        UPDATE wallet_accounts
        SET current_balance = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("di", $new_wallet_balance, $tx['wallet_id']);
    $stmt->execute();

    /* =========================
       UPDATE BRANCH COH
    ========================== */
    $stmt = $con->prepare("
        UPDATE branches
        SET current_coh = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("di", $new_coh, $branch_id);
    $stmt->execute();

    /* =========================
       AUDIT LOG
    ========================== */
    saveAuditLog(
        $user_id,
        $branch_id,
        $tx_id,
        "Deleted $type transaction of " . number_format($amount, 2) .
        " via $thru | Reversed balances",
        'delete_transaction'
    );

    $con->commit();

    echo json_encode(["success" => true]);

} catch (Exception $e) {

    $con->rollback();
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
