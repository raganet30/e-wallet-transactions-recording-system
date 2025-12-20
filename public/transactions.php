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
                    <h1 class="mb-0">Transactions <span class="badge bg-info me-2">data from database</span></h1>
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
                                    <!-- <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>₱8,000.00</th> 
                                        <th>₱55.00</th> 
                                        <th>₱8,055.00</th>
                                        <th colspan="2"></th> 
                                    </tr> -->
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
                                <li class="list-group-item"><strong>Transaction Fee:</strong> ₱<span id="viewCharge"></span></li>
                                <li class="list-group-item"><strong>Total:</strong> ₱<span id="viewTotal"></span></li>
                                <li class="list-group-item"><strong>Tendered:</strong> ₱<span id="viewTendered"></span></li>                            
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
                    url: "../api/fetch_active_e-wallet.php",
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
                    $feeThru.append('<option value="Cash">Cash</option>');
                    $feeThru.append('<option value="GCash">GCash</option>');

                } else if (walletName.toLowerCase() === 'maya') {
                    $feeThru.append('<option value="Cash">Cash</option>');
                    $feeThru.append('<option value="Maya">Maya</option>');

                } else if (walletName.toLowerCase() === 'others') {
                    $feeThru.append('<option value="Cash">Cash</option>');
                    $feeThru.append('<option value="Others">Others</option>');
                } 
                else {
                    $feeThru.append('<option value="Cash">Cash</option>');
                }

                $feeThru.val('');
                handleTenderedState();
            });

            //  NEW: Handle tendered amount behavior
            function handleTenderedState() {
                const transType = $('input[name="transaction_type"]:checked').val();
                const feeThru = $('select[name="transaction_fee_thru"]').val();
                const $tendered = $('input[name="tendered_amount"]');

                if (
                    (transType === 'Cash-out') &&
                    feeThru &&
                    feeThru !== 'Cash'
                ) {
                    $tendered.val(0).prop('readonly', true);
                } else {
                    $tendered.prop('readonly', false);
                }

                calculateChange();
            }

            // Function to calculate change based on transaction type
            function calculateChange() {
                const amount = parseFloat($('input[name="amount"]').val()) || 0;
                const charge = parseFloat($('input[name="transaction_charge"]').val()) || 0;
                const tendered = parseFloat($('input[name="tendered_amount"]').val()) || 0;
                const transType = $('input[name="transaction_type"]:checked').val();
                const transFeeThru = $('select[name="transaction_fee_thru"]').val();

                let change = 0;

                if (transType === "Cash-in") {
                    // Cash-in: total = amount + transaction fee
                    const total = amount + charge;
                    change = tendered - total;

                    if (transFeeThru && transFeeThru !== 'Cash') {
                        // If fee is thru e-wallet, tendered must cover only amount
                        change = tendered - amount;

                    }

                } else if (transType === "Cash-out") {
                    // Cash-out: customer only pays transaction fee
                    change = tendered - charge;

                    if (transFeeThru && transFeeThru !== 'Cash') {
                        // If fee is thru e-wallet, tendered must cover only amount
                        change = tendered - amount;

                    }
                }

                $('input[name="change_amount"]').val(change >= 0 ? change.toFixed(2) : '0.00');
            }

            // Trigger calculation & tendered logic
            $('input[name="amount"], input[name="transaction_charge"], input[name="tendered_amount"]')
                .on('input', calculateChange);

            $('input[name="transaction_type"]').on('change', handleTenderedState);
            $('select[name="transaction_fee_thru"]').on('change', handleTenderedState);

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



                // add validation before showing confirmation modal
                // if feThru is e-wallet and tendered is less than amount for cash-in
                if (
                    (transType === 'Cash-in') &&
                    (feeThru && feeThru !== 'Cash') &&
                    (parseFloat(tendered) < parseFloat(amount))
                ) {
                    let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${"Insufficient tendered amount."}
                                    </div>
                                `;

                    $("#addTransactionModal .modal-body").prepend(alertHtml);
                    setTimeout(() => $(".alert").alert('close'), 3000);
                    return;
                }

                if (
                    (transType === 'Cash-in') &&
                    (feeThru && feeThru === 'Cash') &&
                    (parseFloat(tendered) < parseFloat(total))
                ) {
                    let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${"Insufficient tendered amount."}
                                    </div>
                                `;

                    $("#addTransactionModal .modal-body").prepend(alertHtml);
                    setTimeout(() => $(".alert").alert('close'), 3000);
                    return;
                }



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
                            // alert("Transaction saved successfully.");

                            // Show global alert
                            let alertHtml = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message || "Transaction added successfully."}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                            $("#globalAlertArea").html(alertHtml);

                            setTimeout(() => $(".alert").alert('close'), 3000);

                            // Optionally reload table or update dashboard
                            // reset form modals
                            $('#addTransactionForm')[0].reset();
                            $('#addTransactionModal').modal('hide');

                            // reload datatable
                            $('#transactionsTable').DataTable().ajax.reload();
                        } else {
                            // alert(response.message || "Failed to save transaction.");
                            //show the add transaction modal again with error
                            // the div alert must show inside add modal

                            let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${response.message || "Failed to add transaction."}
                                    </div>
                                `;

                            $("#addTransactionModal .modal-body").prepend(alertHtml);
                            setTimeout(() => $(".alert").alert('close'), 3000);
                            $('#confirmAddModal').modal('hide');
                            $('#addTransactionModal').modal('show');

                        }
                    },
                    error: function () {
                        // alert("An error occurred while saving transaction.");
                        let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${response.message || "An error occurred while saving transaction"}
                                    </div>
                                `;

                        $("#addTransactionModal .modal-body").prepend(alertHtml);
                        setTimeout(() => $(".alert").alert('close'), 3000);
                        $('#confirmAddModal').modal('hide');
                        $('#addTransactionModal').modal('show');

                    }
                });
            });

        });



        // populate transactions datatable
        $(document).ready(function () {

            const table = $('#transactionsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '../api/fetch_transactions.php',
                    type: 'GET',
                    dataSrc: 'data' //  REQUIRED
                },
                order: [[1, 'desc']],
                columns: [
                    { data: null, render: (d, t, r, m) => m.row + 1 },
                    { data: 'created_at' },
                    { data: 'reference_no' },
                    {

                        data: 'wallet_name',
                        render: d => {
                            if (!d) return '';
                            const badgeClass = d.toLowerCase() === 'gcash' ? 'bg-info' : d.toLowerCase() === 'maya' ? 'bg-success' : 'bg-warning';
                            return `<span class="badge ${badgeClass}">${d}</span>`;
                        }

                    },
                    {
                        data: 'type',
                        render: d => {
                            if (!d) return '';
                            const badgeClass = d === 'Cash-in' ? 'bg-secondary' : d === 'Cash-out' ? 'bg-danger' : 'bg-secondary';
                            return `<span class="badge ${badgeClass}">${d}</span>`;
                        }
                    },
                    {
                        data: 'amount',
                        render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
                    },
                    {
                        data: 'charge',
                        render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
                    },
                    {
                        data: 'total',
                        render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
                    },
                    { data: 'payment_thru' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: row => `
                <button class="btn btn-sm btn-outline-primary view-btn" data-id="${row.id}">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}">
                    <i class="bi bi-trash"></i>
                </button>
            `
                    }
                ]
            });


            //  View Transaction
            $('#transactionsTable').on('click', '.view-btn', function () {
                const rowData = table.row($(this).parents('tr')).data();

                $('#viewDate').text(rowData.created_at);
                $('#viewRef').text(rowData.reference_no);
                $('#viewEwallet').text(rowData.wallet_name);
                $('#viewType').text(rowData.type);
                $('#viewAmount').text(Number(rowData.amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#viewCharge').text(Number(rowData.charge).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#viewTotal').text(Number(rowData.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#viewTendered').text(Number(rowData.tendered_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#viewChange').text(Number(rowData.change_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#viewPaymentMode').text(rowData.payment_thru);

                $('#viewTransactionModal').modal('show');
            });

        });




    </script>


    <script src="../assets/js/script.js"></script>


</body>

</html>