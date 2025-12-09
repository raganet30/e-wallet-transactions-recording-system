<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // User is not logged in, redirect to login
    header("Location: ../public/login");
    exit;
}

// Define pages restricted to super_admin only
$restricted_pages = ['branches.php', 'users.php']; // add more files as needed
$current_page = basename($_SERVER['PHP_SELF']);

// Check if current page is restricted
if (in_array($current_page, $restricted_pages)) {
    if ($_SESSION['role'] !== 'super_admin') {
        // Optional: store an error message in session
        $_SESSION['error'] = "You do not have permission to access this page.";
        header("Location: ../public/dashboard");
        exit;
    }
}
?>