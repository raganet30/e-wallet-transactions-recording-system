<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../config/db.php"; // adjust path if needed



// Convert to seconds
$timeout = 15 * 60; // 30 minutes

// Check if user has last activity recorded
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        session_start(); // restart session to set expired message
        $_SESSION['expired'] = "Your session has expired due to inactivity. Please log in again.";
        header("Location: login");
        exit();
    }
}

// Always update activity timestamp
$_SESSION['last_activity'] = time();

