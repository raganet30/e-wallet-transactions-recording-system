<?php
session_start();
require __DIR__ . '/../config/db.php';
require '../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Please enter both username and password.';
    header('Location: ../login.php');
    exit;
}

try {
    // Add status in query ⬇⬇⬇
    $stmt = $con->prepare('SELECT id, branch_id, username, password, name, role, status FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Check account status
            if ((int) $user['status'] !== 1) {
                $_SESSION['error'] = 'Inactive account. Please contact administrator.';
                header('Location: ../public/login');
                exit;
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['branch_id'] = $user['branch_id'] || null;
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

          

            header('Location: ../public/dashboard');

              // add audit for login
            addAuditLog(null, "User logged in", "login");

            exit;

        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: ../public/login');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Username not found.';
        header('Location: ../public/login');
        exit;
    }

} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
    $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
    header('Location: ../public/login');
    exit;

} finally {
    if (isset($stmt) && $stmt instanceof mysqli_stmt)
        $stmt->close();
    if (isset($con) && $con instanceof mysqli)
        $con->close();
}
