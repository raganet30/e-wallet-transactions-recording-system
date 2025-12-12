<?php
require '../config/helpers.php';

session_start();
addLoginLogs('logout');

session_unset();
session_destroy();

header("Location: ../public/login");


exit;
?>