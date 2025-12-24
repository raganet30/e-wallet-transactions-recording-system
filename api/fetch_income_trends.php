<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$branch_id = (int) $_SESSION['branch_id'];

/*
|--------------------------------------------------------------------------
| DAILY TREND (LAST 7 DAYS)
|--------------------------------------------------------------------------
*/
$daily = [];
$dailyQuery = "
    SELECT 
        DATE(created_at) as day,
        SUM(charge) as total
    FROM transactions
    WHERE 
        is_deleted = 0
        AND branch_id = ?
        AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
";

$stmt = $con->prepare($dailyQuery);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $daily[$row['day']] = (float) $row['total'];
}

/*
|--------------------------------------------------------------------------
| MONTHLY TREND (JAN–DEC CURRENT YEAR)
|--------------------------------------------------------------------------
*/

// Initialize all months with 0
$monthly = [];
for ($m = 1; $m <= 12; $m++) {
    $key = date('Y') . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
    $monthly[$key] = 0;
}

$monthlyQuery = "
    SELECT 
        MONTH(created_at) as month_num,
        SUM(charge) as total
    FROM transactions
    WHERE 
        is_deleted = 0
        AND branch_id = ?
        AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
";

$stmt = $con->prepare($monthlyQuery);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {

    $monthKey = date('Y') . '-' . str_pad($row['month_num'], 2, '0', STR_PAD_LEFT);
    $monthly[$monthKey] = (float) $row['total'];
}


echo json_encode([
    'success' => true,
    'daily'   => $daily,
    'monthly' => $monthly
]);
