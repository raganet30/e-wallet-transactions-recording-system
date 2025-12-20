<?php
// Detect environment
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if (
    $host === 'localhost' ||
    $host === '127.0.0.1' ||
    str_contains($host, '.local')
) {
    // ===== LOCALHOST CONFIG =====
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "transaction_recording_system";
} else {
    // ===== INFINITYFREE CONFIG =====
    $servername = "sql209.infinityfree.com"; // replace with your actual DB host
    $username = "if0_40612873";
    $password = "0494trIt064UTy";
    $dbname = "if0_40612873_transaction_recording_system";
}

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}
?>