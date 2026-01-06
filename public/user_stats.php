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
            <h1 class="mb-4">User Stats</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Statistics Overview</h5>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">

                                <?php include '../config/branch_filtering.php'; ?>

                                <div class="col-md-2">
                                    <label class="form-label">Date From:</label>
                                    <input type="date" class="form-control" id="dateFilterFrom">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date To:</label>
                                    <input type="date" class="form-control" id="dateFilterTo">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">E-wallet:</label>
                                    <select class="form-select" id="e-walletFilter">
                                        <option value="">All E-wallets</option>
                                        <!-- fetch from e-wallets -->
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Transaction Type:</label>
                                    <select class="form-select" id="transactionTypeFilter">
                                        <option value="">All Types</option>
                                        <option value="Cash-in">Cash-in</option>
                                        <option value="Cash-out">Cash-out</option>
                                    </select>
                                </div>
                                <!-- user -->
                                <div class="col-md-2">
                                    <label class="form-label">User:</label>
                                    <select class="form-select" id="userFilter">
                                        <option value="">All Users</option>
                                        <!-- fetch from users -->
                                    </select>
                                </div>
                                <!-- apply filter -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button class="btn btn-outline-info w-100" id="applyFilters">
                                        <i class="bi bi-filter me-2"></i>Filter
                                    </button>
                                </div>

                                <div class="col-md-1 d-flex align-items-end">
                                    <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="userStatsContent">
                        <!-- User stats content will be loaded here via AJAX -->
                        <div class="card">

                        </div>
                    </div>


                </div>
            </div>

            <?php include '../views/scripts.php'; ?>




            <?php include '../views/footer.php'; ?>

</body>

</html>