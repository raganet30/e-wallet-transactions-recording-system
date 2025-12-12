<?php
require '../config/session_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../views/head.php'; ?>
<?php include '../views/head.php'; ?>

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Dashboard</h1>


            <div class="row mt-4">
                <!-- E-wallet balance (Gcash, Maya) -->
                <!-- Dynamic E-wallet Balances -->
                <!-- E-wallet balance (Dynamic) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi bi-phone me-2"></i>E-wallet Balance</h5>
                        <div class="mt-3" id="ewalletBalances">
                            <!-- Wallet balances will be dynamically injected here -->
                        </div>
                    </div>
                </div>

                <!-- CASH ON HAND (remains static structure) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi-cash-coin me-2"></i>Cash on Hand</h5>
                        <div class="mt-3 text-center">
                            <div class="h3 fw-bold text-primary" id="cohAmount">₱0.00</div>
                            <small class="text-muted" id="cohLastCount">Last count: Today</small>
                        </div>
                    </div>
                </div>



                <!-- DAILY INCOME IN E-WALLETS (GCash, Maya) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi bi-graph-up me-2"></i>Daily E-wallet Income</h5>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>GCash Income</span>
                                <span class="fw-bold text-success">₱8,750.50</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Maya Income</span>
                                <span class="fw-bold text-success">₱4,230.75</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- DAILY INCOME IN CASH -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi bi-cash-stack me-2"></i>Daily Cash Income</h5>
                        <div class="mt-3 text-center">
                            <div class="h3 fw-bold text-success">₱18,450.00</div>
                            <small class="text-muted">Cash transactions today</small>
                        </div>
                    </div>
                </div>

                <!-- TOTAL DAILY INCOME (Cash + E-wallets income) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi bi-bar-chart me-2"></i>Total Daily Income</h5>
                        <div class="mt-3 text-center">
                            <div class="h2 fw-bold" style="color: #3f8cff;">₱31,431.25</div>
                            <div class="mt-2">
                                <span class="badge bg-primary me-2">Cash: ₱18,450.00</span>
                                <span class="badge bg-success">E-wallets: ₱12,981.25</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../views/scripts.php'; ?>

    <script>
        $(document).ready(function () {

            function loadEwalletBalances() {
                $.ajax({
                    url: "../api/fetch_e-wallets.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        const container = $("#ewalletBalances");
                        container.empty();

                        let totalBalance = 0;

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(wallet => {
                                const balance = parseFloat(wallet.current_balance.replace(/,/g, '')) || 0;
                                totalBalance += balance;

                                const row = `
                                  <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded bg-light shadow-sm wallet-row">
                                    <div>
                                        <span class="fw-semibold">${wallet.account_name}</span>
                                        <small class="text-muted ms-1">${wallet.label ? `(${wallet.label})` : ''}</small>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-success">₱${balance.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                                    </div>
                                </div>

                                `;
                                container.append(row);
                            });

                            // Add total row at the bottom
                            const totalRow = `
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span>₱${totalBalance.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                                </div>
                            `;
                            container.append(totalRow);

                        } else {
                            container.append(`<div class="text-muted">No e-wallet accounts available.</div>`);
                        }
                    },
                    error: function () {
                        $("#ewalletBalances").html(`<div class="text-danger">Failed to load e-wallet balances.</div>`);
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

    </script>


    <?php include '../views/footer.php'; ?>

</body>

</html>