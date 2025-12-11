<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}


$id = $_POST['user_id'];
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$role = isset($_POST['role']) ? trim(strtolower($_POST['role'])) : '';
$branch_id = $_POST['edit_branch_id'];
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

if ($password !== '' && $password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
    exit;
}

// Check if username exists (other than this user)
$stmt = $con->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
$stmt->bind_param("si", $username, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username already taken']);
    exit;
}

// Update user
if ($password !== '') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $con->prepare("UPDATE users SET name=?, username=?, role=?, branch_id=?, password=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $username, $role, $branch_id, $hashed, $id);
} else {
    $stmt = $con->prepare("UPDATE users SET name=?, username=?, role=?, branch_id=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $username, $role, $branch_id, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
}
