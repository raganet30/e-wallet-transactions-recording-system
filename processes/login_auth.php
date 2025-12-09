<?php
session_start();
require __DIR__ . '/../config/db.php'; 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // only accept POST
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
    $stmt = $con->prepare('SELECT id, username, password, name, role FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // stored password must be hashed using password_hash() when user created
        if (password_verify($password, $user['password'])) {
            // regenerate session id to prevent fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // redirect to your dashboard - adjust path
            header('Location: ../public/dashboard');
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
    $_SESSION['error'] = 'An error occurred. Please try again later.';
    header('Location: ../public/login');
    exit;
} finally {
    if (isset($stmt) && $stmt instanceof mysqli_stmt)
        $stmt->close();
    if (isset($con) && $con instanceof mysqli)
        $con->close();
}
