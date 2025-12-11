<?php
session_start();
require '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['data' => []]);
    exit;
}

$role = $_SESSION['role'];
$branchId = $_SESSION['branch_id'] ?? null;  // super_admin has no branch

try {

    $query = "
    SELECT 
        u.id,
        u.branch_id,
        b.branch_name,
        u.name,
        u.username,
        u.role,
        u.status,
        u.created_at
    FROM users u
    LEFT JOIN branches b ON u.branch_id = b.id
    WHERE u.role != 'super_admin'
    ORDER BY u.id DESC
";
    $stmt = $con->prepare($query);


    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    $count = 1;

    while ($row = $result->fetch_assoc()) {

        $statusBadge = $row['status'] == 1
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';

        $actionBtn = ($row['status'] == 1)
            ? '<button class="btn btn-sm btn-outline-warning toggle-status-btn" data-id="' . $row['id'] . '">
                <i class="bi bi-toggle-on"></i> Deactivate
               </button>'
            : '<button class="btn btn-sm btn-outline-success toggle-status-btn" data-id="' . $row['id'] . '">
                <i class="bi bi-toggle-off"></i> Activate
               </button>';

        $editBtn = '<button class="btn btn-sm btn-outline-primary edit-btn" data-id="' . $row['id'] . '">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>';

        $users[] = [
            $count++,
            htmlspecialchars($row['name']),
            // branch_name display
            htmlspecialchars($row['branch_name'] ?? 'N/A'),
            htmlspecialchars($row['username']),
            htmlspecialchars(ucfirst($row['role'])),
            $statusBadge,
            $row['created_at'],
            $editBtn . " " . $actionBtn
        ];
    }

    echo json_encode(['data' => $users]);
} catch (Throwable $e) {
    error_log("Fetch users error: " . $e->getMessage());
    echo json_encode(['data' => []]);
}

