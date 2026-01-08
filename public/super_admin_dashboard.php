<?php
require '../config/session_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../views/head.php'; ?>
<?php include '../processes/session_checker.php'; ?>

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="superAdminDashboard">
        <div class="container-fluid">
            <h1 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Super Admin Dashboard</h1>

            <!-- Top Metrics Cards: Aggregated Data Across All Branches -->
            <div class="row g-3 mb-4">
                <small class="text-muted">All branches</small>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-cash-stack display-6 text-success mb-2"></i>
                            <h6>Total Cash Income</h6>
                            <h3 id="totalCashIncome">₱0.00</h3>

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-wallet2 display-6 text-info mb-2"></i>
                            <h6>Total E-wallet Income</h6>
                            <h3 id="totalEwalletIncome">₱0.00</h3>

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-calculator display-6 text-primary mb-2"></i>
                            <h6>Grand Total Income</h6>
                            <h3 id="grandTotalIncome">₱0.00</h3>

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-coin display-6 text-warning mb-2"></i>
                            <h6>Total Cash on Hand</h6>
                            <h3 id="totalCashOnHand">₱0.00</h3>

                        </div>
                    </div>
                </div>


            </div>

            <hr>

            <!-- Branch-wise Overview Table -->
            <div class="row g-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-building me-2"></i>Branch Overview</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="branchOverviewTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-hash me-1"></i></th>
                                            <th><i class="bi bi-house-door me-1"></i>Branch</th>
                                            <th><i class="bi bi-cash-stack me-1"></i>Cash Income</th>
                                            <th><i class="bi bi-wallet2 me-1"></i>E-wallet Income</th>
                                            <th><i class="bi bi-calculator me-1"></i>Grand Total Income</th>
                                            <th><i class="bi bi-coin me-1"></i>Cash on Hand</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic rows for each branch -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Income Trends Charts -->
            <div class="row g-3 mt-4">
                 <small class="text-muted">All branches</small>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-graph-up-arrow me-2"></i>Daily Total Income Trends</h5>
                            <canvas id="dailyIncomeTrendChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-bar-chart-line me-2"></i>Monthly Total Income Trends</h5>
                            <canvas id="monthlyIncomeTrendChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <!-- income distribution based on cash or e-wallet -->
                <div class="col-md-6 mt-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-pie-chart me-2"></i>Total Income Distribution</h5>
                            <canvas id="distributionIncomeChart" height="50"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-trophy me-2"></i>Top 3 Branches</h5>
                            <ul id="topBranches" class="list-group list-group-flush">
                                <!-- Dynamic top 3 -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include '../views/scripts.php'; ?>

    <script>
        $(document).ready(function () {

            function formatPeso(amount) {
                return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                    minimumFractionDigits: 2
                });
            }

            $.getJSON('../api/fetch_super_admin_dashboard.php', function (res) {
                if (!res.success) return;

                // ================================
                // STACK CARDS
                // ================================
                $('#totalCashIncome').text(formatPeso(res.totals.cash));
                $('#totalEwalletIncome').text(formatPeso(res.totals.ewallet));
                $('#totalCashOnHand').text(formatPeso(res.totals.cash_on_hand));
                $('#grandTotalIncome').text(formatPeso(res.totals.grand));

                // ================================
                // BRANCH TABLE
                // ================================
                let rows = '';
                res.branches.forEach((b, i) => {
                    rows += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${b.branch_name}</td>
                    <td>${formatPeso(b.cash_income)}</td>
                    <td>${formatPeso(b.ewallet_income)}</td>
                    <td class="fw-bold">${formatPeso(b.total_income)}</td>
                    <td>${formatPeso(b.current_coh)}</td>
                </tr>
            `;
                });
                $('#branchOverviewTable tbody').html(rows);

                // ================================
                // TOP 3 BRANCHES
                // ================================
                let topHtml = '';
                res.top_branches.forEach((b, i) => {
                    topHtml += `
                <li class="list-group-item d-flex justify-content-between">
                    <span><i class="bi bi-award me-2"></i>${b.branch_name}</span>
                    <span class="fw-bold">${formatPeso(b.total_income)}</span>
                </li>
            `;
                });
                $('#topBranches').html(topHtml);

                let dailyChart, monthlyChart, distributionChart;
                if (dailyChart) dailyChart.destroy();

                dailyChart = new Chart(
                    document.getElementById('dailyIncomeTrendChart'),
                    {
                        type: 'line',
                        data: {
                            labels: res.daily_trend.map(d => d.day),
                            datasets: [{
                                label: 'Daily Income',
                                data: res.daily_trend.map(d => d.total),
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `₱${ctx.raw.toLocaleString()}`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    ticks: {
                                        callback: v => '₱' + v.toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                );
                const months = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                const monthlyData = Array(12).fill(0);
                res.monthly_trend.forEach(m => {
                    monthlyData[m.month - 1] = m.total;
                });

                if (monthlyChart) monthlyChart.destroy();

                monthlyChart = new Chart(
                    document.getElementById('monthlyIncomeTrendChart'),
                    {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Monthly Income',
                                data: monthlyData
                            }]
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `₱${ctx.raw.toLocaleString()}`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    ticks: {
                                        callback: v => '₱' + v.toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                );
                if (distributionChart) distributionChart.destroy();

                distributionChart = new Chart(
                    document.getElementById('distributionIncomeChart'),
                    {
                        type: 'bar',
                        data: {
                            labels: res.wallet_distribution.map(w => w.channel),
                            datasets: [{
                                label: 'Income Distribution',
                                data: res.wallet_distribution.map(w => w.total),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', // horizontal bar
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx =>
                                            ` ₱${ctx.raw.toLocaleString()}`
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        callback: v => '₱' + v.toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                );




            });
        });
    </script>

    <?php include '../views/footer.php'; ?>
</body>

</html>