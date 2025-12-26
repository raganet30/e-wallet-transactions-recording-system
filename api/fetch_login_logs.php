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

// Base conditions
$where  = [];
$params = [];
$types  = "";

// Role-based branch restriction
if ($userRole !== 'super_admin') {
    $where[]  = "u.branch_id = ?";
    $params[] = $userBranch;
    $types   .= "i";
} elseif (!empty($branch_id) && $branch_id !== 'all') {
    $where[]  = "u.branch_id = ?";
    $params[] = $branch_id;
    $types   .= "i";
}

// DataTables built-in search
if (!empty($search)) {
    $where[]  = "(u.name LIKE ? OR ll.ip_address LIKE ? OR ll.login_type LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types   .= "sss";
}

// Date filters
if (!empty($date_from)) {
    $where[]  = "DATE(ll.created_at) >= ?";
    $params[] = $date_from;
    $types   .= "s";
}

if (!empty($date_to)) {
    $where[]  = "DATE(ll.created_at) <= ?";
    $params[] = $date_to;
    $types   .= "s";
}

// Login / Logout filter
if (!empty($actionType)) {
    $where[]  = "ll.login_type = ?";
    $params[] = $actionType;
    $types   .= "s";
}

$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Total records
$totalQuery = $con->query("SELECT COUNT(*) AS total FROM login_logs");
$totalRecords = $totalQuery->fetch_assoc()['total'];

// Filtered records
$countSql = "
    SELECT COUNT(*) AS total
    FROM login_logs ll
    LEFT JOIN users u ON u.id = ll.user_id
    $whereSQL
";
$countStmt = $con->prepare($countSql);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$filteredRecords = $countStmt->get_result()->fetch_assoc()['total'];

// Fetch data
$dataSql = "
    SELECT
        ll.created_at,
        ll.login_type,
        ll.ip_address,
        u.name
    FROM login_logs ll
    LEFT JOIN users u ON u.id = ll.user_id
    $whereSQL
    ORDER BY ll.created_at DESC
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

    // Badge styling
    $badgeClass = ($row['login_type'] === 'login')
        ? 'bg-success'
        : 'bg-danger';

    $description = ucfirst($row['login_type']) . " | IP: " . $row['ip_address'];

    $data[] = [
        'row_num' => $rowNum++,
        'created_at' => date('m/d/Y H:i', strtotime($row['created_at'])),
        'login_type' => sprintf(
            '<span class="badge %s">%s</span>',
            $badgeClass,
            ucfirst($row['login_type'])
        ),
        'description' => htmlspecialchars($description),
        'name' => htmlspecialchars($row['name'])
    ];
}

// Response
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data"            => $data
]);
