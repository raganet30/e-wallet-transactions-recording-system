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
            <!-- <h1 class="mb-4">Transactions</h1> -->
            <!-- add new transactions button,  transactions datatable with actions : 'view, delete'  -->

            <div class="container-fluid">
                <!-- Page Header with Add Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Transactions</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-circle me-2"></i>Add New Transaction
                    </button>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Date Range</label>
                                <input type="date" class="form-control" id="dateFilter">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Transaction Type</label>
                                <select class="form-select" id="typeFilter">
                                    <option value="">All Types</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Maya">Maya</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
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
                        <div class="table-responsive">
                            <table id="transactionsTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Reference No.</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Transaction Fee</th>
                                        <th>Total</th>
                                        <th>Transaction Fee thru</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample data - replace with PHP/DB data -->
                                    <tr>
                                        <td>1</td>
                                        <td>2023-12-01 14:30</td>
                                        <td>0123456</td>
                                        <td><span class="badge badge-gcash">GCash</span></td>
                                        <td data-order="1500.00">₱1,500.00</td>
                                        <td data-order="15.00">₱15.00</td>
                                        <td data-order="1515.00">₱1,515.00</td>
                                        <td>Cash</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="0123456">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="0123456">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>2023-12-01 11:30</td>
                                        <td>0123547</td>
                                        <td><span class="badge badge-gcash">GCash</span></td>
                                        <td data-order="500.00">₱500.00</td>
                                        <td data-order="10.00">₱10.00</td>
                                        <td data-order="510.00">₱510.00</td>
                                        <td>GCash</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="123547">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="123547">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>2023-12-02 15:30</td>
                                        <td>ABCD123</td>
                                        <td><span class="badge badge-maya">Maya</span></td>
                                        <td data-order="2500.00">₱2,500.00</td>
                                        <td data-order="15.00">₱15.00</td>
                                        <td data-order="2515.00">₱2,515.00</td>
                                        <td>Cash</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="ABCD123">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="ABCD123">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>2023-12-02 15:30</td>
                                        <td>ABCD124</td>
                                        <td><span class="badge badge-maya">Maya</span></td>
                                        <td data-order="3500.00">₱3,500.00</td>
                                        <td data-order="15.00">₱15.00</td>
                                        <td data-order="3515.00">₱3,515.00</td>
                                        <td>Maya</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="ABCD124">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="ABCD124">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>₱8,000.00</th> <!-- Amount Total -->
                                        <th>₱55.00</th> <!-- Fee Total -->
                                        <th>₱8,055.00</th> <!-- Overall Total -->
                                        <th colspan="2"></th> <!-- Spans the last 2 columns -->
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
                                            <option value="GCash">GCash</option>
                                            <option value="Maya">Maya</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>

                                    <!-- Transaction Type -->
                                    <div class="col-md-6">
                                        <label class="form-label d-block">Transaction Type</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="transaction_type"
                                                id="cash-in" value="Cash-in" required>
                                            <label class="form-check-label" for="cash-in">Cash In</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="transaction_type"
                                                id="cash-out" value="Cash-out" required>
                                            <label class="form-check-label" for="cash-out">Cash Out</label>
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Transaction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <ul class="list-group">
                                <li class="list-group-item"><strong>E-wallet Type:</strong> <span id="c_eWallet"></span>
                                </li>
                                <li class="list-group-item"><strong>Reference No:</strong> <span
                                        id="c_reference"></span></li>
                                <li class="list-group-item"><strong>Amount:</strong> ₱<span id="c_amount"></span></li>
                                <li class="list-group-item"><strong>Transaction Fee:</strong> ₱<span
                                        id="c_charge"></span></li>
                                <!-- total -->
                                <li class="list-group-item"><strong>Total:</strong> ₱<span id="c_total"></span></li>
                                <li class="list-group-item"><strong>Tendered Amount:</strong> ₱<span
                                        id="c_tendered"></span></li>
                                <li class="list-group-item"><strong>Change:</strong> ₱<span id="c_change"></span></li>

                                <li class="list-group-item"><strong>Transaction Fee thru:</strong> <span
                                        id="c_feeThru"></span></li>
                                <li class="list-group-item"><strong>Transaction Type:</strong> <span
                                        id="c_transType"></span></li>
                            </ul>
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
                                <li class="list-group-item"><strong>Type:</strong> <span id="viewEwallet"></span></li>
                                <li class="list-group-item"><strong>Amount:</strong> ₱<span id="viewAmount"></span></li>
                                <li class="list-group-item"><strong>Charge:</strong> ₱<span id="viewCharge"></span></li>

                                <li class="list-group-item"><strong>Change:</strong> ₱<span id="c_change"></span></li>
                                <li class="list-group-item"><strong>Payment Thru:</strong> <span
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
                        <p>Are you sure you want to delete this transaction? This action cannot be undone.</p>
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
    <script>
        $(document).ready(function () {

            // Fetch e-wallet accounts for this branch and populate selector
            function loadEwalletAccounts() {
                $.ajax({
                    url: "../api/fetch_e-wallets.php",
                    dataType: "json",
                    success: function (response) {
                        const $selector = $('select[name="e_wallet_account"]');
                        $selector.empty().append('<option value="">Select E-wallet Account</option>');
                        if (response.data && response.data.length) {
                            response.data.forEach(wallet => {
                                $selector.append(`<option value="${wallet.id}" data-name="${wallet.account_name}">${wallet.account_name} (${wallet.account_number})</option>`);
                            });
                        }
                    },
                    error: function () {
                        alert("Failed to load e-wallet accounts.");
                    }
                });
            }

            loadEwalletAccounts(); // populate on modal open

            // Auto-adjust Transaction Fee Thru options based on selected wallet
            $('select[name="e_wallet_account"]').on('change', function () {
                const selectedOption = $(this).find('option:selected');
                const walletName = selectedOption.data('name');
                const $feeThru = $('select[name="transaction_fee_thru"]');
                $feeThru.empty().append('<option value="">Select Type</option>');

                if (!walletName) return;

                if (walletName.toLowerCase() === 'gcash') {
                    $feeThru.append('<option value="GCash">GCash</option>');
                    $feeThru.append('<option value="Cash">Cash</option>');
                } else if (walletName.toLowerCase() === 'maya') {
                    $feeThru.append('<option value="Maya">Maya</option>');
                    $feeThru.append('<option value="Cash">Cash</option>');
                } else {
                    $feeThru.append('<option value="Cash">Cash</option>');
                }

                $feeThru.val(''); // reset selection
            });

            // Function to calculate change based on transaction type
            function calculateChange() {
                const amount = parseFloat($('input[name="amount"]').val()) || 0;
                const charge = parseFloat($('input[name="transaction_charge"]').val()) || 0;
                const tendered = parseFloat($('input[name="tendered_amount"]').val()) || 0;
                const transType = $('input[name="transaction_type"]:checked').val();

                let change = 0;

                if (transType === "Cash-in") {
                    // Cash-in: total = amount + transaction fee
                    const total = amount + charge;
                    change = tendered - total;
                } else if (transType === "Cash-out") {
                    // Cash-out: customer only pays transaction fee
                    change = tendered - charge;
                }

                $('input[name="change_amount"]').val(change >= 0 ? change.toFixed(2) : 0);
            }

            // Trigger calculation when amount, charge, tendered amount, or transaction type changes
            $('input[name="amount"], input[name="transaction_charge"], input[name="tendered_amount"], input[name="transaction_type"]').on('input change', calculateChange);

            // Show confirmation modal on form submit
            $('#addTransactionForm').on('submit', function (e) {
                e.preventDefault();

                // Get form values
                const walletName = $('select[name="e_wallet_account"] option:selected').data('name') || '';
                const referenceNo = $('input[name="reference_no"]').val();
                const amount = parseFloat($('input[name="amount"]').val() || 0).toFixed(2);
                const charge = parseFloat($('input[name="transaction_charge"]').val() || 0).toFixed(2);
                const tendered = parseFloat($('input[name="tendered_amount"]').val() || 0).toFixed(2);
                const change = parseFloat($('input[name="change_amount"]').val() || 0).toFixed(2);
                const total = (parseFloat(amount) + parseFloat(charge)).toFixed(2);
                const feeThru = $('select[name="transaction_fee_thru"]').val();
                const transType = $('input[name="transaction_type"]:checked').val();

                // Populate confirmation modal
                $('#c_eWallet').text(walletName);
                $('#c_reference').text(referenceNo);
                $('#c_amount').text(amount);
                $('#c_charge').text(charge);
                $('#c_total').text(total);
                $('#c_feeThru').text(feeThru);
                $('#c_transType').text(transType);
                $('#c_tendered').text(tendered);
                $('#c_change').text(change);

                // close add modal
                $('#addTransactionModal').modal('hide');
                // Show modal
                $('#confirmAddModal').modal('show');
            });


            // Final confirm save transaction
            $('#confirmAddBtn').on('click', function () {
                const formData = $('#addTransactionForm').serialize();
                $.ajax({
                    url: "../processes/add_transaction.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $('#confirmAddModal, #addTransactionModal').modal('hide');
                            alert("Transaction saved successfully.");
                            // Optionally reload table or update dashboard
                        } else {
                            alert(response.message || "Failed to save transaction.");
                        }
                    },
                    error: function () {
                        alert("An error occurred while saving transaction.");
                    }
                });
            });

        });

    </script>


    <script src="../assets/js/script.js"></script>


</body>

</html>