<?php
require_once '../config/db.php';
require_once '../config/helpers.php';

session_start();

$userRole   = currentRole();
$userBranch = $_SESSION['branch_id'] ?? null;

// DataTables variables
$draw   = intval($_POST['draw']);
$start  = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';

// Filters
$branch_id   = $_POST['branch_id'] ?? '';
$date_from  = $_POST['date_from'] ?? '';
$date_to    = $_POST['date_to'] ?? '';
$actionType = $_POST['action_type'] ?? '';

// Base query
$where  = [];
$params = [];
$types  = "";

// Role-based filtering
if ($userRole !== 'super_admin') {
    $where[]  = "al.branch_id = ?";
    $params[] = $userBranch;
    $types   .= "i";
} elseif (!empty($branch_id) && $branch_id !== 'all') {
    $where[]  = "al.branch_id = ?";
    $params[] = $branch_id;
    $types   .= "i";
}

// DataTables built-in search
if (!empty($search)) {
    $where[]  = "(al.description LIKE ? OR al.action_type LIKE ? OR u.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types   .= "sss";
}

// Date filters
if (!empty($date_from)) {
    $where[]  = "DATE(al.created_at) >= ?";
    $params[] = $date_from;
    $types   .= "s";
}

if (!empty($date_to)) {
    $where[]  = "DATE(al.created_at) <= ?";
    $params[] = $date_to;
    $types   .= "s";
}

// Action type filter
if (!empty($actionType)) {
    $where[]  = "al.action_type = ?";
    $params[] = $actionType;
    $types   .= "s";
}

$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Total records
$totalQuery = $con->query("SELECT COUNT(*) AS total FROM audit_logs");
$totalRecords = $totalQuery->fetch_assoc()['total'];

// Filtered records
$countSql = "
    SELECT COUNT(*) AS total
    FROM audit_logs al
    LEFT JOIN users u ON u.id = al.user_id
    $whereSQL
";
$countStmt = $con->prepare($countSql);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$filteredRecords = $countStmt->get_result()->fetch_assoc()['total'];

// Fetch paginated data
$dataSql = "
    SELECT
        al.created_at,
        al.action_type,
        al.description,
        u.name AS name
    FROM audit_logs al
    LEFT JOIN users u ON u.id = al.user_id
    $whereSQL
    ORDER BY al.created_at DESC
    LIMIT ?, ?
";

$params[] = $start;
$params[] = $length;
$types   .= "ii";

$dataStmt = $con->prepare($dataSql);
$dataStmt->bind_param($types, ...$params);
$dataStmt->execute();
$result = $dataStmt->get_result();

$data   = [];
$rowNum = $start + 1;

while ($row = $result->fetch_assoc()) {

    // Badge color logic
    switch ($row['action_type']) {
        case 'add_transaction':
            $badgeClass = 'bg-success';
            break;

        case 'delete_transaction':
            $badgeClass = 'bg-danger';
            break;

        default:
            $badgeClass = 'bg-warning text-dark';
            break;
    }

    $data[] = [
        'row_num' => $rowNum++,
        'created_at' => date('m/d/Y H:i', strtotime($row['created_at'])),
        'action_type' => sprintf(
            '<span class="badge %s">%s</span>',
            $badgeClass,
            ucwords(str_replace('_', ' ', $row['action_type']))
        ),
        'description' => htmlspecialchars($row['description']),
        'name' => htmlspecialchars($row['name'])
    ];
}

// DataTables response
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data"            => $data
]);
