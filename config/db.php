<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "transaction_recording_system";

$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>