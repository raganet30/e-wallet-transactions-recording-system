<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

/**
 * SECURITY & SESSION CHECK
 */
if (!isset($_SESSION['user_id'], $_SESSION['branch_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit;
}

$user_id   = (int) $_SESSION['user_id'];
$branch_id = (int) $_SESSION['branch_id'];

/**
 * INPUT SANITIZATION
 */
$wallet_id      = isset($_POST['e_wallet_account']) ? (int) $_POST['e_wallet_account'] : 0;
$reference_no   = trim($_POST['reference_no'] ?? '');
$payment_thru   = trim($_POST['transaction_fee_thru'] ?? '');
$type           = trim($_POST['transaction_type'] ?? '');
$amount         = (float) ($_POST['amount'] ?? 0);
$charge         = (float) ($_POST['transaction_charge'] ?? 0);
$tendered       = (float) ($_POST['tendered_amount'] ?? 0);

/**
 * BASIC VALIDATIONS
 */
if (
    !$wallet_id ||
    !in_array($type, ['Cash-in', 'Cash-out']) ||
    !in_array($payment_thru, ['GCash', 'Maya', 'Cash']) ||
    $amount <= 0 ||
    $charge < 0
) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid transaction input."
    ]);
    exit;
}

/**
 * COMPUTE TOTAL & CHANGE (BACKEND AUTHORITATIVE)
 */
if ($type === 'Cash-in') {
    $total  = $amount + $charge;
    $change = $tendered - $total;

    if (in_array($payment_thru, ['GCash', 'Maya'])) {
        // For e-wallet payments, tendered amount can be 0
        if ($tendered < 0) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid tendered amount."
            ]);
            exit;
        }
    } else {
        // For cash payments, tendered must cover total
        if ($tendered < $total) {
            echo json_encode([
                "success" => false,
                "message" => "Insufficient tendered amount."
            ]);
            exit;
        }
    }
} else { // Cash-out
    $total  = $amount + $charge;
    $change = $tendered - $charge;

    if (in_array($payment_thru, ['GCash', 'Maya'])) {
        // For e-wallet payments, tendered amount can be 0
        if ($tendered < 0) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid tendered amount."
            ]);
            exit;
        }
    } else {
        // For cash payments, tendered must cover fee
        if ($tendered < $charge) {
            echo json_encode([
                "success" => false,
                "message" => "Tendered amount must cover transaction fee."
            ]);
            exit;
        }
    }
}

/**
 * START DATABASE TRANSACTION
 */
$con->begin_transaction();

try {

    /**
     * LOCK WALLET ROW
     */
    $stmt = $con->prepare("
        SELECT current_balance, branch_id, status, account_name
        FROM wallet_accounts
        WHERE id = ?
        FOR UPDATE
    ");
    $stmt->bind_param("i", $wallet_id);
    $stmt->execute();
    $wallet = $stmt->get_result()->fetch_assoc();

    if (!$wallet || $wallet['branch_id'] != $branch_id || $wallet['status'] != 1) {
        throw new Exception("Invalid or inactive wallet account.");
    }

    $wallet_balance = (float) $wallet['current_balance'];
    $wallet_name    = strtolower($wallet['account_name']);

    /**
     * LOCK BRANCH ROW (COH)
     */
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

    $coh = (float) $branch['current_coh'];

    /**
     * BUSINESS RULES COMPUTATION
     */
    $new_wallet_balance = $wallet_balance;
    $new_coh            = $coh;

    if ($type === 'Cash-in') {

        if ($payment_thru === 'Cash') {
            // e-wallet - amount
            // coh + amount + fee
            if ($wallet_balance < $amount) {
                throw new Exception("Insufficient e-wallet balance.");
            }
            $new_wallet_balance = $wallet_balance - $amount;
            $new_coh            = $coh + $amount + $charge;

        } else {
            // payment thru same e-wallet
            // e-wallet - amount + fee
            // coh + amount
            if ($wallet_balance < ($amount - $charge)) {
                throw new Exception("Insufficient e-wallet balance.");
            }
            $new_wallet_balance = $wallet_balance - $amount + $charge;
            $new_coh            = $coh + $amount;
        }

    } else { // Cash-out

        if ($payment_thru === 'Cash') {
            // e-wallet + amount
            // coh - amount + fee
            if ($coh < $amount) {
                throw new Exception("Insufficient cash on hand.");
            }
            $new_wallet_balance = $wallet_balance + $amount;
            $new_coh            = $coh - $amount + $charge;

        } else {
            // payment thru e-wallet
            // e-wallet + amount + fee
            // coh - amount
            if ($coh < $amount) {
                throw new Exception("Insufficient cash on hand.");
            }
            $new_wallet_balance = $wallet_balance + $amount + $charge;
            $new_coh            = $coh - $amount;
        }
    }

    /**
     * INSERT TRANSACTION RECORD
     */
    $stmt = $con->prepare("
        INSERT INTO transactions
        (branch_id, user_id, wallet_id, type, amount, charge, total,
         tendered_amount, change_amount, payment_thru, reference_no, is_deleted, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())
    ");
    $stmt->bind_param(
        "iiisdddddds",
        $branch_id,
        $user_id,
        $wallet_id,
        $type,
        $amount,
        $charge,
        $total,
        $tendered,
        $change,
        $payment_thru,
        $reference_no
    );
    $stmt->execute();

    /**
     * UPDATE WALLET BALANCE
     */
    $stmt = $con->prepare("
        UPDATE wallet_accounts
        SET current_balance = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("di", $new_wallet_balance, $wallet_id);
    $stmt->execute();

    /**
     * UPDATE BRANCH COH
     */
    $stmt = $con->prepare("
        UPDATE branches
        SET current_coh = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("di", $new_coh, $branch_id);
    $stmt->execute();

    /**
     * COMMIT EVERYTHING
     */
    $con->commit();

    echo json_encode([
        "success" => true,
        "message" => "Transaction saved successfully."
    ]);

} catch (Exception $e) {

    $con->rollback();

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
