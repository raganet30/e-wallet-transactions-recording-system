<?php
session_start();
require '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['branch_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

$branch_id = (int) $_SESSION['branch_id'];

/*
|--------------------------------------------------------------------------
| DAILY INCOME (TODAY ONLY)
|--------------------------------------------------------------------------
| - Based on transaction charge
| - Excludes deleted transactions
| - Grouped by payment_thru
*/

$query = "
    SELECT 
        payment_thru,
        SUM(charge) AS total_income
    FROM transactions
    WHERE 
        is_deleted = 0
        AND branch_id = ?
        AND DATE(created_at) = CURDATE()
    GROUP BY payment_thru
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();

$income = [
    'gcash' => 0,
    'maya'  => 0,
    'cash'  => 0,
    'others' => 0
];

while ($row = $result->fetch_assoc()) {

    $amount = (float) $row['total_income'];

    switch (strtolower($row['payment_thru'])) {
        case 'gcash':
            $income['gcash'] += $amount;
            break;
        case 'maya':
            $income['maya'] += $amount;
            break;
        case 'cash':
            $income['cash'] += $amount;
            break;
        default:
            $income['others'] += $amount;
            break;
    }
}

$ewallet_total = $income['gcash'] + $income['maya'] + $income['others'];
$grand_total   = $ewallet_total + $income['cash'];

echo json_encode([
    'success' => true,
    'data' => [
        'gcash'         => $income['gcash'],
        'maya'          => $income['maya'],
        'others'        => $income['others'],
        'cash'          => $income['cash'],
        'ewallet_total' => $ewallet_total,
        'grand_total'   => $grand_total
    ]
]);
