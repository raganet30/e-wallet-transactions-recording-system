<?php
require '../config/db.php';
header('Content-Type: application/json');

// ================================
// TOTALS (ALL BRANCHES)
// ================================
$totalsQuery = "
    SELECT
        SUM(CASE WHEN payment_thru = 'cash' THEN charge ELSE 0 END) AS total_cash_income,
        SUM(CASE WHEN payment_thru != 'cash' THEN charge ELSE 0 END) AS total_ewallet_income,
        SUM(charge) AS grand_total_income
    FROM transactions
    WHERE is_deleted = 0
";
$totals = $con->query($totalsQuery)->fetch_assoc();

// ================================
// TOTAL CASH ON HAND
// ================================
$cohQuery = "SELECT SUM(current_coh) AS total_cash_on_hand FROM branches";
$cashOnHand = $con->query($cohQuery)->fetch_assoc()['total_cash_on_hand'] ?? 0;

// ================================
// BRANCH OVERVIEW
// ================================
$branchQuery = "
    SELECT
    b.id AS branch_id,
    b.branch_name,
    b.current_coh,
    COALESCE(SUM(CASE WHEN t.payment_thru = 'cash' THEN t.charge END), 0) AS cash_income,
    COALESCE(SUM(CASE WHEN t.payment_thru != 'cash' THEN t.charge END), 0) AS ewallet_income,
    COALESCE(SUM(t.charge), 0) AS total_income
FROM branches b
LEFT JOIN transactions t 
    ON t.branch_id = b.id AND t.is_deleted = 0
GROUP BY b.id
";
$branchResult = $con->query($branchQuery);

$branches = [];
while ($row = $branchResult->fetch_assoc()) {
    $branches[] = $row;
}

// ================================
// TOP 3 BRANCHES
// ================================
$branchesSorted = $branches;
usort($branchesSorted, fn($a, $b) => $b['total_income'] <=> $a['total_income']);
$topBranches = array_slice($branchesSorted, 0, 3);

// ================================
// DAILY TREND (LAST 7 DAYS)
// ================================
$dailyQuery = "
   SELECT 
    DATE(created_at) AS day,
    COALESCE(SUM(charge), 0) AS total
FROM transactions
WHERE 
    is_deleted = 0
    AND created_at >= CURDATE() - INTERVAL 6 DAY
GROUP BY DATE(created_at)
ORDER BY day ASC

";
$dailyTrend = $con->query($dailyQuery)->fetch_all(MYSQLI_ASSOC);

// ================================
// MONTHLY TREND
// ================================
$monthlyQuery = "
   SELECT 
    MONTH(created_at) AS month,
    COALESCE(SUM(charge), 0) AS total
FROM transactions
WHERE 
    is_deleted = 0
    AND YEAR(created_at) = YEAR(CURDATE())
GROUP BY MONTH(created_at)
ORDER BY month ASC

";
$monthlyTrend = $con->query($monthlyQuery)->fetch_all(MYSQLI_ASSOC);

// ================================
// E-WALLET DISTRIBUTION
// ================================
$walletQuery = "
    SELECT 
    CASE 
        WHEN payment_thru = 'cash' THEN 'Cash'
        ELSE 'E-wallet'
    END AS channel,
    SUM(charge) AS total
FROM transactions
WHERE is_deleted = 0
GROUP BY channel

";
$walletDistribution = $con->query($walletQuery)->fetch_all(MYSQLI_ASSOC);

// ================================
// RESPONSE
// ================================
echo json_encode([
    'success' => true,
    'totals' => [
        'cash' => (float) $totals['total_cash_income'],
        'ewallet' => (float) $totals['total_ewallet_income'],
        'grand' => (float) $totals['grand_total_income'],
        'cash_on_hand' => (float) $cashOnHand
    ],
    'branches' => $branches,
    'top_branches' => $topBranches,
    'daily_trend' => $dailyTrend,
    'monthly_trend' => $monthlyTrend,
    'wallet_distribution' => $walletDistribution
]);
