<?php
session_start();
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$name             = trim($_POST['name']);
$branch_id = trim($_POST['branch_id']);
$username         = trim($_POST['username']);
$role             = trim($_POST['role']);
$password         = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);


if (!$name || !$username || !$role || !$password || !$confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit;
}

// Check username exists
$stmt = $con->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
    exit;
}

// Insert user
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $con->prepare("
    INSERT INTO users (branch_id, name, username, role, password, status, created_at)
    VALUES (?, ?, ?, ?, ?, '1', NOW())
");

$stmt->bind_param('issss', $branch_id, $name, $username, $role, $hashed);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save user.']);
}

$stmt->close();
$con->close();
