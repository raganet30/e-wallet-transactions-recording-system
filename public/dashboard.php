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

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Dashboard</h1>


            <div class="row mt-4">
                <!-- DAILY INCOME IN E-WALLETS (GCash, Maya) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content h-100">
                        <h5><i class="bi bi-graph-up me-2"></i>Daily E-wallet Income</h5>

                        <div class="row mt-3">

                            <!-- GCASH INCOME -->
                            <div class="col-6">
                                <div class="text-center p-3 rounded bg-light shadow-sm h-100">
                                    <div class="fw-semibold">
                                        <i class="bi bi-wallet"></i>
                                        GCash
                                    </div>
                                    <div class="h4 fw-bold text-secondary mt-2" id="gcashIncome">
                                        ₱0.00
                                    </div>
                                    <small class="text-muted">Today</small>
                                </div>
                            </div>

                            <!-- MAYA INCOME -->
                            <div class="col-6">
                                <div class="text-center p-3 rounded bg-light shadow-sm h-100">
                                    <div class="fw-semibold">
                                        <i class="bi bi-wallet2"></i>
                                        Maya
                                    </div>
                                    <div class="h4 fw-bold text-secondary mt-2" id="mayaIncome">
                                        ₱0.00
                                    </div>
                                    <small class="text-muted">Today</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- E-wallet balance (Gcash, Maya) -->
                <!-- Dynamic E-wallet Balances -->
                <!-- E-wallet balance (Dynamic) -->
                <!-- DAILY INCOME IN CASH -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <div class="mt-3 text-center">
                            <h5><i class="bi bi-cash-stack me-2"></i>Daily Cash Income </h5>
                            <div class="h3 fw-bold text-secondary" id="cashIncome">₱0.00</div>
                            <small class="text-muted">Cash income today</small>
                        </div>
                    </div>
                </div>

                <!-- TOTAL DAILY INCOME (Cash + E-wallets income) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <div class="mt-3 text-center">
                            <h5><i class="bi bi-bar-chart me-2"></i>Total Daily Income </h5>
                            <div class="h3 fw-bold text-secondary" id="totalIncome">₱0.00</div>
                            <div class="mt-2">
                                <span class="badge bg-success me-2" id="cashBadge">Cash: ₱0.00</span>
                                <span class="badge bg-info" id="ewalletBadge">E-wallets: ₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3">

                    <!-- GCASH -->
                    <div class="col-md-4 mb-3">
                        <div class="demo-content text-center h-100">
                            <h5>
                                <i class="bi bi-wallet"></i>
                                GCash Balance
                            </h5>
                            <div class="mt-3">
                                <div class="h3 fw-bold text-secondary" id="gcashAmount">₱0.00</div>
                                <small class="text-muted">E-wallet</small>
                            </div>
                        </div>
                    </div>

                    <!-- MAYA -->
                    <div class="col-md-4 mb-3">
                        <div class="demo-content text-center h-100">
                            <h5>
                                <i class="bi bi-wallet2"></i>
                                Maya Balance
                            </h5>
                            <div class="mt-3">
                                <div class="h3 fw-bold text-secondary" id="mayaAmount">₱0.00</div>
                                <small class="text-muted">E-wallet</small>
                            </div>
                        </div>
                    </div>

                    <!-- CASH ON HAND -->
                    <div class="col-md-4 mb-3">
                        <div class="demo-content text-center h-100">
                            <h5><i class="bi bi-cash-coin me-2"></i>Cash on Hand</h5>
                            <div class="mt-3">
                                <div class="h3 fw-bold text-secondary" id="cohAmount">₱0.00</div>
                                <small class="text-muted" id="cohLastCount">Last count: Today</small>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- grand total income (e-wallet income + cash income) -->
                <!-- <div class="col-md-4 mb-3">
                        <div class="demo-content">
                            <h5><i class="bi bi-calculator me-2"></i>Grand Total Income </h5>
                            <div class="mt-3 text-center">
                                <div class="h3 fw-bold text-secondary" id="grandTotalIncome">₱0.00</div>
                                <small class="text-muted">Total income from all sources</small>
                            </div>
                        </div>
                    </div> -->

                <hr>
                <!-- add trendS chart for monthy income -->
                <div class="row mt-4">

                    <!-- DAILY INCOME TREND -->
                    <div class="col-md-6 mb-3">
                        <div class="demo-content">
                            <h5><i class="bi bi-graph-up-arrow me-2"></i>Daily Income Trends (Last 7 days)</h5>
                            <canvas id="dailyIncomeChart" height="120"></canvas>
                        </div>
                    </div>

                    <!-- MONTHLY INCOME TREND -->
                    <div class="col-md-6 mb-3">
                        <div class="demo-content">
                            <h5><i class="bi bi-bar-chart-line me-2"></i>Monthly Income Trends (Jan-Dec)</h5>
                            <canvas id="monthlyIncomeChart" height="120"></canvas>
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

            function loadEwalletBalances() {
                $.ajax({
                    url: "../api/fetch_e-wallets.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {

                        let gcash = 0;
                        let maya = 0;

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(wallet => {
                                const balance = parseFloat(wallet.current_balance.replace(/,/g, '')) || 0;
                                const name = wallet.account_name.toLowerCase();

                                if (name.includes("gcash")) gcash += balance;
                                if (name.includes("maya")) maya += balance;
                            });
                        }

                        $("#gcashAmount").text(
                            "₱" + gcash.toLocaleString("en-PH", { minimumFractionDigits: 2 })
                        );

                        $("#mayaAmount").text(
                            "₱" + maya.toLocaleString("en-PH", { minimumFractionDigits: 2 })
                        );
                    },
                    error: function () {
                        $("#gcashAmount, #mayaAmount").text("₱0.00");
                    }
                });
            }

            function loadCoh() {
                $.ajax({
                    url: "../api/fetch_current_coh.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        let coh = parseFloat(response.current_coh) || 0;
                        $("#cohAmount").text("₱" + coh.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $("#cohLastCount").text(response.last_count || "Today");
                    },
                    error: function () {
                        $("#cohAmount").text("₱0.00");
                        $("#cohLastCount").text("-");
                    }
                });
            }

            // Load data on page ready
            loadEwalletBalances();
            loadCoh();

        });



        // load daily income
        function formatPeso(value) {
            return '₱' + Number(value).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function loadDailyIncome() {

            $.getJSON('../api/fetch_daily_income.php', function (res) {

                if (!res.success) return;

                const d = res.data;

                $('#gcashIncome').text(formatPeso(d.gcash));
                $('#mayaIncome').text(formatPeso(d.maya));
                $('#otherEwalletIncome').text(formatPeso(d.others));
                $('#cashIncome').text(formatPeso(d.cash));

                $('#totalIncome').text(formatPeso(d.grand_total));
                $('#cashBadge').text('Cash: ' + formatPeso(d.cash));
                $('#ewalletBadge').text('E-wallets: ' + formatPeso(d.ewallet_total));
            });
        }

        // Load on page ready
        $(document).ready(function () {
            loadDailyIncome();
        });

        function loadGrandTotalIncome() {
            $.getJSON('../api/fetch_grand_total_income.php', function (res) {
                if (!res.success) return;
                $('#grandTotalIncome').text(formatPeso(res.grand_total_income));
            });
        }

        $(document).ready(function () {
            loadGrandTotalIncome();
        });



        // load income trends charts
        function peso(n) {
            return '₱' + Number(n).toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
        }


        let dailyChart, monthlyChart;

        function loadIncomeTrends() {

            $.getJSON('../api/fetch_income_trends.php', function (res) {

                if (!res.success) return;

                /* =========================
                   DAILY CHART
                ========================== */
                const dailyLabels = Object.keys(res.daily);
                const dailyData = Object.values(res.daily);

                if (dailyChart) dailyChart.destroy();

                dailyChart = new Chart(
                    document.getElementById('dailyIncomeChart'),
                    {
                        type: 'line',
                        data: {
                            labels: dailyLabels,
                            datasets: [{
                                label: 'Daily Income',
                                data: dailyData,
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: ctx => peso(ctx.raw)
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    ticks: {
                                        callback: value => peso(value)
                                    }
                                }
                            }
                        }
                    }
                );

                /* =========================
                   MONTHLY CHART
                ========================== */
                const monthNames = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                const monthlyKeys = Object.keys(res.monthly);
                const monthlyData = Object.values(res.monthly);

                const monthlyLabels = monthlyKeys.map(k => {
                    const m = parseInt(k.split('-')[1], 10);
                    return monthNames[m - 1];
                });


                if (monthlyChart) monthlyChart.destroy();

                monthlyChart = new Chart(
                    document.getElementById('monthlyIncomeChart'),
                    {
                        type: 'bar',
                        data: {
                            labels: monthlyLabels,
                            datasets: [{
                                label: 'Monthly Income',
                                data: monthlyData
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: ctx => peso(ctx.raw)
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    ticks: {
                                        callback: value => peso(value)
                                    }
                                }
                            }
                        }
                    }
                );

            });
        }

        $(document).ready(function () {
            loadIncomeTrends();
        });



    </script>


    <?php include '../views/footer.php'; ?>

</body>

</html>