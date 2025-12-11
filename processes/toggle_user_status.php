<?php
require '../config/db.php'; // adjust path to your DB connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'No user ID provided.']);
        exit;
    }

    $id = intval($_POST['id']);

    // Get current status
    $stmt = $con->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;
    }

    $user = $result->fetch_assoc();
    $newStatus = $user['status'] == 1 ? 0 : 1;

    // Update status
    $update = $con->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
    $update->bind_param("ii", $newStatus, $id);

    if ($update->execute()) {
        echo json_encode(['success' => true, 'new_status' => $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }
}
?>
