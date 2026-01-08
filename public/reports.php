<?php
require '../config/session_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../views/head.php'; ?>
<?php include '../processes/session_checker.php'; ?>

<body>
    <?php include '../views/sidebar.php'; ?>
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Reports</h1>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#dailyReport"
                        type="button" role="tab">Daily Report</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthlyReport"
                        type="button" role="tab">Monthly Report</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#customReport"
                        type="button" role="tab">Custom Report</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summaryReport"
                        type="button" role="tab">Summary</button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabsContent">

                <!-- DAILY REPORT -->
                <div class="tab-pane fade show active" id="dailyReport" role="tabpanel">

                    <div class="row g-2 mb-3">
                        <?php include '../config/branch_filtering.php'; ?>
                        <div class="col-md-2">
                            <label class="form-label">User:</label>
                            <select class="form-select userFilter" id="dailyUser">
                                <option value="">All Users</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Date:</label>
                            <!-- set default date is today -->
                            <input type="date" class="form-control" id="dailyDate" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">E-wallet:</label>
                            <select class="form-select" id="dailyEwallet">
                                <option value="">All E-wallets</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Transaction Type:</label>
                            <select class="form-select" id="dailyTransactionType">
                                <option value="">All Types</option>
                                <option value="Cash-in">Cash-in</option>
                                <option value="Cash-out">Cash-out</option>
                            </select>
                        </div>
                        <!-- users -->

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="dailyApplyFilters"><i
                                    class="bi bi-filter me-2"></i>Apply Filters</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" id="dailyResetFilters"><i
                                    class="bi bi-arrow-clockwise me-2"></i>Reset Filters</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dailyReportTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Reference No.</th>
                                            <th>E-wallet</th>
                                            <th>Type</th>
                                            <th>Fee thru</th>
                                            <th>Amount</th>
                                            <th>Transaction Fee</th>
                                            <th>Total</th>
                                            <th>Tendered</th>
                                            <th>Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Populated by AJAX -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Totals:</th>
                                            <th id="dailyTotalAmount">0.00</th>
                                            <th id="dailyTotalCharge">0.00</th>
                                            <th id="dailyGrandTotal">0.00</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MONTHLY REPORT -->
                <div class="tab-pane fade" id="monthlyReport" role="tabpanel">
                    <div class="row g-2 mb-3">
                        <?php include '../config/branch_filtering.php'; ?>
                        <div class="col-md-2">
                            <label class="form-label">User:</label>
                            <select class="form-select userFilter" id="monthlyUser">
                                <option value="">All Users</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Month:</label>
                            <input type="month" class="form-control" id="monthlyMonth" value="<?php echo date('Y-m'); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">E-wallet:</label>
                            <select class="form-select" id="monthlyEwallet">
                                <option value="">All E-wallets</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Transaction Type:</label>
                            <select class="form-select" id="monthlyTransactionType">
                                <option value="">All Types</option>
                                <option value="Cash-in">Cash-in</option>
                                <option value="Cash-out">Cash-out</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="monthlyApplyFilters"><i
                                    class="bi bi-filter me-2"></i>Apply Filters</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" id="monthlyResetFilters"><i
                                    class="bi bi-arrow-clockwise me-2"></i>Reset Filters</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="monthlyReportTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Reference No.</th>
                                            <th>E-wallet</th>
                                            <th>Type</th>
                                            <th>Fee thru</th>
                                            <th>Amount</th>
                                            <th>Transaction Fee</th>
                                            <th>Total</th>
                                            <th>Tendered</th>
                                            <th>Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Totals:</th>
                                            <th id="monthlyTotalAmount">0.00</th>
                                            <th id="monthlyTotalCharge">0.00</th>
                                            <th id="monthlyGrandTotal">0.00</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CUSTOM REPORT -->
                <div class="tab-pane fade" id="customReport" role="tabpanel">
                    <div class="row g-2 mb-3">
                        <?php include '../config/branch_filtering.php'; ?>
                        <div class="col-md-2">
                            <label class="form-label">User:</label>
                            <select class="form-select userFilter" id="customUser">
                                <option value="">All Users</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">From Date:</label>
                            <input type="date" class="form-control" id="customDateFrom">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date:</label>
                            <input type="date" class="form-control" id="customDateTo">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">E-wallet:</label>
                            <select class="form-select" id="customEwallet">
                                <option value="">All E-wallets</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Transaction Type:</label>
                            <select class="form-select" id="customTransactionType">
                                <option value="">All Types</option>
                                <option value="Cash-in">Cash-in</option>
                                <option value="Cash-out">Cash-out</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="customApplyFilters"><i
                                    class="bi bi-filter me-2"></i>Apply Filters</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" id="customResetFilters"><i
                                    class="bi bi-arrow-clockwise me-2"></i>Reset Filters</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="customReportTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Reference No.</th>
                                            <th>E-wallet</th>
                                            <th>Type</th>
                                            <th>Fee thru</th>
                                            <th>Amount</th>
                                            <th>Transaction Fee</th>
                                            <th>Total</th>
                                            <th>Tendered</th>
                                            <th>Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Totals:</th>
                                            <th id="customTotalAmount">0.00</th>
                                            <th id="customTotalCharge">0.00</th>
                                            <th id="customGrandTotal">0.00</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>

                            </div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY REPORT -->
            <div class="tab-pane fade" id="summaryReport" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <?php include '../config/branch_filtering.php'; ?>
                            <div class="col-md-2">
                                <label class="form-label">User:</label>
                                <select class="form-select userFilter" id="summaryUser">
                                    <option value="">All Users</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">E-wallet:</label>
                                <select class="form-select" id="summaryEwallet">
                                    <option value="">All E-wallets</option>
                                    <!-- Populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Transaction Type:</label>
                                <select class="form-select" id="summaryTransactionType">
                                    <option value="">All Types</option>
                                    <option value="Cash-in">Cash-in</option>
                                    <option value="Cash-out">Cash-out</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date From:</label>
                                <input type="date" class="form-control" id="summaryDateFrom">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date To:</label>
                                <input type="date" class="form-control" id="summaryDateTo">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-info w-100" id="summaryApplyFilters">
                                    <i class="bi bi-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-secondary w-100" id="summaryResetFilters">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="summaryReportTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>E-wallet</th>
                                        <th>Transaction Type</th>
                                        <th>Total Amount</th>
                                        <th>Total Accumulated Fee</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via AJAX -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Totals:</th>
                                        <th id="summaryTotalAmount">0.00</th>
                                        <th id="summaryTotalCharge">0.00</th>
                                        <th id="summaryGrandTotal">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    </div>

    <?php include '../views/footer.php'; ?>
    <?php include '../views/scripts.php'; ?>
    <script src="../assets/js/reports.js"></script>
    <script>
        window.CURRENT_BRANCH_NAME = <?= json_encode(currentBranchName()); ?>;
        window.CURRENT_ROLE = <?= json_encode($_SESSION['role'] ?? ''); ?>;
    </script>
</body>

</html>