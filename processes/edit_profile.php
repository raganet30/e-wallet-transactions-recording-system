<?php
session_start();
require '../config/db.php';
require '../config/helpers.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$current_password = $_POST['current_password'] ?? '';

if ($name === '' || $username === '') {
    echo json_encode(['success' => false, 'message' => 'Name and username are required.']);
    exit;
}

if ($current_password === '') {
    echo json_encode(['success' => false, 'message' => 'Current password is required.']);
    exit;
}

// Fetch current password hash
$stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($current_password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

// Prevent same password reuse
if ($password !== '' && password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'New password cannot be the same as current password.']);
    exit;
}

// ===============================
// UPDATE QUERY
// ===============================
if ($password !== '') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("
        UPDATE users
        SET name = ?, username = ?, password = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("sssi", $name, $username, $hashed, $user_id);

    $action = 'Updated profile and password';
} else {
    $stmt = $con->prepare("
        UPDATE users
        SET name = ?, username = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ssi", $name, $username, $user_id);

    $action = 'Updated profile information';
}

$success = $stmt->execute();

if ($success && !empty($_SESSION['branch_id'])) {
    saveProfileAuditLog($_SESSION['user_id'], $_SESSION['branch_id'], $action);
}

echo json_encode(['success' => $success]);
