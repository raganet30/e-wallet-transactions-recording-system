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
    $stmt = $con->prepare("
    SELECT 
        u.id, 
        u.branch_id,
        u.username, 
        u.password, 
        u.name, 
        u.role, 
        u.status AS user_status,
        b.status AS branch_status
    FROM users u
    LEFT JOIN branches b ON u.branch_id = b.id
    WHERE u.username = ?
    LIMIT 1
");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

        //    if user is not super_admin
            if ($user['role'] !== 'super_admin') {
                // Check if user is active
                if ($user['user_status'] != 1) {
                    $_SESSION['error'] = 'Your account is inactive. Please contact the administrator.';
                    header('Location: ../public/login');
                    exit;
                }

                // Check if branch is active
                if ($user['branch_status'] != 1) {
                    $_SESSION['error'] = 'Your branch is inactive. Please contact the administrator.';
                    header('Location: ../public/login');
                    exit;
                }
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['branch_id'] = $user['branch_id'] ?? null; // FIXED
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Add login log
            addLoginLogs('login');


            // Initialize last activity timestamp for session timeout
            $_SESSION['last_activity'] = time();

            // if user is super_admin, redirect to super_admin dashboard
            if ($user['role'] === 'super_admin') {
                header('Location: ../public/super_admin_dashboard');
                exit;
            }
            else{
                // normal user redirect to normal dashbboard
                header('Location: ../public/dashboard');
            }
            
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
