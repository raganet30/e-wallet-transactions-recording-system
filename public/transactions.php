<?php
require '../config/session_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../views/head.php'; ?>

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <div id="globalAlertArea"></div>
            <!-- <h1 class="mb-4">Transactions</h1> -->
            <!-- add new transactions button,  transactions datatable with actions : 'view, delete'  -->

            <div class="container-fluid">
                <!-- Page Header with Add Button -->

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Transactions </h1>
                    <?php if (currentRole() !== 'super_admin'): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                            <i class="bi bi-plus-circle me-2"></i>Add New Transaction
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Filter Section -->

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
                            <!-- apply filter -->
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-info w-100" id="applyFilters">
                                    <i class="bi bi-filter me-2"></i>Apply Filters
                                </button>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="card">
                    <div class="card-body">
                        <!-- add excel & pdf import and  print option buttons -->
                         
                        <div class="table-responsive">
                            <table id="transactionsTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Reference No.</th>
                                        <th>E-wallet</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Transaction Fee</th>
                                        <th>Total</th>
                                        <th>Transaction Fee thru</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data populated by AJAX -->
                                </tbody>
                                <tfoot>
                                    <!-- footer totals -->
                                    <tr>
                                        <th colspan="5" class="text-end">TOTAL:</th>
                                        <th id="totalAmount">₱0.00</th>
                                        <th id="totalCharge">₱0.00</th>
                                        <th id="grandTotal">₱0.00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>


            </div>


            <!-- Add Transaction Modal -->
            <div class="modal fade" id="addTransactionModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addTransactionForm">
                                <div class="row g-3">
                                    <!-- E-wallet Account -->
                                    <div class="col-md-6">
                                        <label class="form-label">E-wallet Account</label>
                                        <select class="form-select" name="e_wallet_account" required>
                                            <option value="">Select E-wallet Account</option>
                                            <!-- fetch the e-wallets based on users branch_id -->
                                        </select>
                                    </div>

                                    <!-- Reference Number -->
                                    <div class="col-md-6">
                                        <label class="form-label">Reference Number</label>
                                        <input type="text" class="form-control" name="reference_no">
                                    </div>

                                    <!-- Transaction Fee Thru -->
                                    <div class="col-md-6">
                                        <label class="form-label">Transaction Fee Thru</label>
                                        <select class="form-select" name="transaction_fee_thru" required>
                                            <option value="">Select Type</option>
                                            <option value="Cash">Cash</option>
                                            <option value="GCash">GCash</option>
                                            <option value="Maya">Maya</option>

                                        </select>
                                    </div>

                                    <!-- Transaction Type -->
                                    <div class="col-md-6">
                                        <label class="form-label d-block mb-2">Transaction Type</label>

                                        <div class="row g-2">

                                            <!-- CASH IN -->
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="transaction_type"
                                                    id="cash-in" value="Cash-in" required>
                                                <label class="card text-center cursor-pointer transaction-card h-100"
                                                    for="cash-in">
                                                    <div
                                                        class="card-body d-flex flex-column justify-content-center align-items-center">
                                                        <div class="fw-bold fs-5 text-success">
                                                            <i class="bi bi-arrow-down-circle"></i> Cash-in
                                                        </div>
                                                        <!-- <div class="mt-1">Cash In</div> -->
                                                    </div>
                                                </label>

                                            </div>

                                            <!-- CASH OUT -->
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="transaction_type"
                                                    id="cash-out" value="Cash-out" required>
                                                <label class="card text-center p-3 cursor-pointer transaction-card"
                                                    for="cash-out">
                                                    <div class="fw-bold fs-5 text-danger">
                                                        <i class="bi bi-arrow-up-circle"></i> Cash Out
                                                    </div>
                                                    <!-- <div class="mt-1">Cash Out</div> -->
                                                </label>
                                            </div>

                                        </div>
                                    </div>


                                    <!-- Amount -->
                                    <div class="col-md-6">
                                        <label class="form-label">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Transaction Fee -->
                                    <div class="col-md-6">
                                        <label class="form-label">Transaction Fee</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="transaction_charge"
                                                step="0.01" min="0" required>
                                        </div>
                                    </div>

                                    <!-- Tendered Amount -->
                                    <div class="col-md-6">
                                        <label class="form-label">Tendered Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="tendered_amount" step="0.01"
                                                min="0" required>
                                        </div>
                                    </div>

                                    <!-- Change -->
                                    <div class="col-md-6">
                                        <label class="form-label">Change</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="change_amount" step="0.01"
                                                min="0" readonly>
                                        </div>
                                    </div>



                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="addTransactionForm" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Add Transaction Confirmation Modal -->
            <div class="modal fade" id="confirmAddModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Transaction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-2">

                                <!-- LEFT COLUMN -->
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>E-wallet Type:</strong>
                                            <span class="float-end" id="c_eWallet"></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Reference No:</strong>
                                            <span class="float-end" id="c_reference"></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Transaction Type:</strong>
                                            <span class="float-end" id="c_transType"></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Transaction Fee thru:</strong>
                                            <span class="float-end" id="c_feeThru"></span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- RIGHT COLUMN -->
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>Amount:</strong>
                                            <span class="float-end">₱<span id="c_amount"></span></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Transaction Fee:</strong>
                                            <span class="float-end">₱<span id="c_charge"></span></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Total:</strong>
                                            <span class="float-end fw-bold">₱<span id="c_total"></span></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Tendered Amount:</strong>
                                            <span class="float-end">₱<span id="c_tendered"></span></span>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Change:</strong>
                                            <span class="float-end">₱<span id="c_change"></span></span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" id="confirmAddBtn">Save</button>
                        </div>

                    </div>
                </div>
            </div>





            <!-- View Transaction Modal -->
            <div class="modal fade" id="viewTransactionModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Transaction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <ul class="list-group">
                                <li class="list-group-item"><strong>Date:</strong> <span id="viewDate"></span></li>
                                <li class="list-group-item"><strong>Reference No:</strong> <span id="viewRef"></span>
                                </li>
                                <li class="list-group-item"><strong>E-wallet:</strong> <span id="viewEwallet"></span>
                                </li>
                                <li class="list-group-item"><strong>Type:</strong> <span id="viewType"></span></li>
                                <li class="list-group-item"><strong>Amount:</strong> ₱<span id="viewAmount"></span></li>
                                <li class="list-group-item"><strong>Transaction Fee:</strong> ₱<span
                                        id="viewCharge"></span></li>
                                <li class="list-group-item"><strong>Total:</strong> ₱<span id="viewTotal"></span></li>
                                <li class="list-group-item"><strong>Tendered:</strong> ₱<span id="viewTendered"></span>
                                </li>
                                <li class="list-group-item"><strong>Change:</strong> ₱<span id="viewChange"></span></li>
                                <li class="list-group-item"><strong>Transaction Fee Thru:</strong> <span
                                        id="viewPaymentMode"></span></li>
                            </ul>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this transaction?
                        <br>
                        This process will reverse all affected balances.
                        <br>
                        This action cannot be undone.</p>
                        <input type="hidden" id="deleteTransactionId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    <?php include '../views/footer.php'; ?>
    <?php include '../views/scripts.php'; ?>


    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/transactions.js"></script>
    <script>
        window.CURRENT_BRANCH_NAME = <?= json_encode(currentBranchName()); ?>;
        window.CURRENT_ROLE = <?= json_encode($_SESSION['role'] ?? ''); ?>;
    </script>


</body>

</html>