<?php
//  session_start();
require 'db.php'; // adjust path if needed

/**
 * Get current user's branch ID
 */
function currentBranchId()
{
    return $_SESSION['branch_id'] ?? null;
}

/**
 * Get current user's role
 */
function currentRole()
{
    return $_SESSION['role'] ?? null;
}

/**
 * Get branch name based on session branch_id
 */
function currentBranchName()
{
    $branchId = currentBranchId();

    // If super admin (no branch)
    // if ($branchId === 0 || $branchId === null) {
    //     return "All Branches";
    // }

    // Use global DB connection
    global $con;

    $stmt = $con->prepare("SELECT branch_name FROM branches WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        return $row['branch_name'];
    }

    return "Unknown Branch"; // fallback
}


/**
 * Store an audit log entry
 * 
 * @param int|null $transactionId  - related transaction, or null
 * @param string   $description    - details of what happened
 * @param string   $actionType     - e.g. "create", "update", "delete", "login"
 */
function addAuditLog($transactionId, $description, $actionType) {
    global $con;

    $userId   = $_SESSION['user_id'];
    $branchId = $_SESSION['branch_id'] ?? 0;

    if (!$userId) {
        return false; // user not logged in
    }

    $stmt = $con->prepare("
        INSERT INTO audit_logs (user_id, branch_id, transaction_id, description, action_type, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("iiiss",
        $userId,
        $branchId,
        $transactionId,
        $description,
        $actionType
    );

    return $stmt->execute();
}


function addLoginLogs($type = 'login') {
    global $con;

    $userId = $_SESSION['user_id'] ?? null;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    
    // Ensure only allowed types
    $loginType = ($type === 'logout') ? 'logout' : 'login';

    if (!$userId) {
        return false;
    }

    $stmt = $con->prepare("
        INSERT INTO login_logs (user_id, ip_address, login_type, created_at)
        VALUES (?, ?, ?, NOW())
    ");

    $stmt->bind_param("iss", $userId, $ipAddress, $loginType);

    return $stmt->execute();
}


//function to fetch current branch coh from branches.coh
function fetchCurrentBranchCoh($branchId) {
    global $con;

    $stmt = $con->prepare("SELECT current_coh FROM branches WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        return $row['current_coh'];
    }

    return 0; // fallback
}

// get all branches
