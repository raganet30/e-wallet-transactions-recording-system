<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Dashboard</h1>


            <div class="row mt-4">
                <!-- E-wallet balance (Gcash, Maya) -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi bi-phone me-2"></i>E-wallet Balance</h5>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>GCash</span>
                                <span class="fw-bold">₱15,250.75</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Maya</span>
                                <span class="fw-bold">₱8,430.20</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CASH ON HAND -->
                <div class="col-md-4 mb-3">
                    <div class="demo-content">
                        <h5><i class="bi-cash-coin me-2"></i>Cash on Hand</h5>
                        <div class="mt-3 text-center">
                            <div class="h3 fw-bold text-primary">₱42,850.00</div>
                            <small class="text-muted">Last count: Today</small>
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

    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>