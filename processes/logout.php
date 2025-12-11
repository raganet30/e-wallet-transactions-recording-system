<?php
require '../config/helpers.php';

session_start();
    // addAuditLog(null, "User logged out", "logout");
session_unset();
session_destroy();

header("Location: ../public/login");


exit;
?>