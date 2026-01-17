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
        <div id="globalAlertArea"></div>
        <div class="container-fluid">
            <h1 class="mb-4">Wallet Accounts </h1>

            <!-- Add Wallet Button -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Manage your e-wallet accounts</h5>
                            <p class="text-muted mb-0">Add or edit wallet accounts for transactions</p>
                        </div>

                        <?php include '../config/branch_filtering.php'; ?>

                        <?php if (currentRole() !== 'super_admin'): ?>
                            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWalletModal">
                                <i class="bi bi-plus-circle"></i> Add New Wallet
                            </button> -->
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Wallet Accounts Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="walletTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Wallet Name</th>
                                            <th>Account No./Label</th>
                                            <th>Current Balance</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total Balance:</th>
                                            <th></th>
                                            <th colspan="2"></th>
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

    <!-- Add Wallet Modal -->
    <div class="modal fade" id="addWalletModal" tabindex="-1" aria-labelledby="addWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWalletModalLabel">Add New Wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="addWalletForm">
                        <div class="mb-3">
                            <label class="form-label">Wallet Name</label>
                            <select class="form-select" id="walletName" required>
                                <option value="">Select wallet type</option>
                                <option value="GCash">GCash</option>
                                <option value="Maya">Maya</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Number / Label</label>
                            <input type="text" class="form-control" id="accountNumber" placeholder="e.g., 0917-123-4567"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Label (Optional)</label>
                            <input type="text" class="form-control" id="accountLabel"
                                placeholder="e.g., Primary Account">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Initial Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="initialBalance" step="0.01" min="0"
                                    value="0">
                            </div>
                        </div>

                        <!-- Toggle Switch Instead of Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="walletStatus" checked>
                                <label class="form-check-label" for="walletStatus">Active</label>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveWalletBtn">Save Wallet</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Wallet Modal -->
    <div class="modal fade" id="editWalletModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="editWalletForm">

                        <input type="hidden" id="editWalletId">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Wallet Name</label>
                                    <input type="text" class="form-control" id="editWalletName" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Number / Label</label>
                                    <input type="text" class="form-control" id="editAccountNumber" readonly>
                                </div>
                            </div>
                        </div>
                        <!-- current amount -->
                         <div class="mb-3">
                            <label class="form-label">Current Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="currentBalance" step="0.01" min="0" readonly>
                            </div>
                        </div>




                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="editBalance" step="0.01" min="0"
                                    placeholder="Enter amount adjustment">
                            </div>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Reason for change</label>
                            <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip" data-bs-html="true"
                                title="
                                    <strong>Set Initial Balance</strong>: Replaces current balance<br>
                                    <strong>Add to E-wallet</strong>: Adds amount to current balance<br>
                                    <strong>Deduct from E-wallet</strong>: Deducts amount (must be sufficient)
                                    ">
                            </i>
                            <select class="form-select" id="changeReason" required>
                                <option value="">Select reason</option>
                                <option value="initial_balance">Set Initial Balance</option>
                                <option value="add_e-wallet">Add to Wallet</option>
                                <option value="deduct_e-wallet">Deduct from Wallet</option>
                            </select>
                        </div>



                        <input type="text" class="form-control" id="editAccountLabel" hidden>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks (Optional)</label>

                            <div class="remark-stack mb-2">
                                <div class="remark-card" data-value="Set new initial balance">
                                    Set new initial balance
                                </div>
                                <div class="remark-card" data-value="Add e-wallet balance">
                                    Add e-wallet balance
                                </div>
                                <div class="remark-card" data-value="Deduct for expenses">
                                    Deduct for expenses
                                </div>
                                <div class="remark-card" data-value="E-wallet reconciliation adjustment">
                                    E-wallet reconciliation adjustment
                                </div>
                                <div class="remark-card" data-value="Manual adjustment">
                                    Manual adjustment
                                </div>
                            </div>

                            <textarea class="form-control" id="remarks" rows="2"
                                placeholder="Add any additional notes..."></textarea>
                        </div>

                        <!-- Toggle Switch for Status -->
                        <div class="mb-3" hidden>
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editWalletStatus">
                                <label class="form-check-label" for="editWalletStatus">Active</label>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="updateWalletBtn">Save</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Confirm Wallet Update Modal -->
    <div class="modal fade" id="confirmWalletModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Confirm Wallet Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <strong>Action:</strong>
                        <div class="alert alert-primary py-2" id="confirmAction"></div>
                    </div>

                    <div class="mb-2">
                        <strong>Current Balance:</strong>
                        <div class="alert alert-secondary py-2">
                            ₱ <span id="confirmCurrentBalance"></span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <strong>Adjustment Amount:</strong>
                        <div class="alert alert-warning py-2">
                            ₱ <span id="confirmAdjustment"></span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <strong>New Balance:</strong>
                        <div class="alert alert-success py-2">
                            ₱ <span id="confirmNewBalance"></span>
                        </div>
                    </div>

                    <p class="text-muted small mb-0">
                        Please confirm that this wallet adjustment is correct.
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmUpdateWalletBtn">
                        Confirm & Save
                    </button>
                </div>

            </div>
        </div>
    </div>



    <input type="hidden" id="userRole" value="<?= $_SESSION['role'] ?>">
    <input type="hidden" id="sessionBranch" value="<?= $_SESSION['branch_id'] ?? '' ?>">



    <?php include '../views/scripts.php'; ?>
    <?php include '../views/footer.php'; ?>
    <script>
        // populate wallet accounts datatable
        $(document).ready(function () {

            let userRole = $("#userRole").val();
            let sessionBranch = $("#sessionBranch").val();

            //  DEFAULT BRANCH ID FOR INITIAL LOAD
            let initialBranchID = (userRole === "super_admin") ? "all" : sessionBranch;

            const walletTable = $("#walletTable").DataTable({
                ajax: {
                    url: "../api/fetch_e-wallets.php?branch_id=" + initialBranchID,
                    dataSrc: "data"
                },
                scrollX: true,
                autoWidth: false,
                responsive: true,
                order: [[0, "asc"]],

                columns: [
                    { data: "no" },
                    {
                        data: "account_name",
                        render: function (data, type, row) {
                            let iconClass = row.account_name.toLowerCase() === "gcash" ? "bi-wallet2" : "bi-credit-card";
                            let colorClass = row.account_name.toLowerCase() === "gcash" ? "bg-primary bg-opacity-10 text-primary" : "bg-info bg-opacity-10 text-info";

                            return `
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <span class="avatar-title rounded-circle ${colorClass}">
                                    <i class="bi ${iconClass}"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">${row.account_name}</h6>
                            </div>
                        </div>`;
                        }
                    },
                    {
                        data: "account_number",
                        render: function (data, type, row) {
                            return `
                        <div>
                            <div class="fw-medium">${row.account_number}</div>
                            <small class="text-muted">${row.label}</small>
                        </div>`;
                        }
                    },
                    {
                        data: "current_balance",
                        render: function (data, type, row) {
                            return `
                        <div class="fw-bold text-success">₱${data}</div>
                        <small class="text-muted">Last updated: ${row.updated_at}</small>
                    `;
                        },
                        className: "text-end"
                    },
                    { data: "status", orderable: false },
                    {
                        data: "id",
                        render: function (data) {
                            return `
                        <button class="btn btn-sm btn-outline-warning edit-wallet-btn" data-id="${data}">
                            <i class="bi bi-pencil"></i> Edit Balance
                        </button>`;
                        },
                        orderable: false
                    }
                ],
                footerCallback: function (row, data, start, end, display) {
                    let api = this.api();

                    // Column index of balance (0-based)
                    let balanceColumn = 3;

                    // Compute total balance on current page
                    let total = api.column(balanceColumn, { page: 'current' }).data().reduce(function (a, b) {
                        return parseFloat(a.toString().replace(/,/g, '')) + parseFloat(b.toString().replace(/,/g, ''));
                    }, 0);

                    // Update the footer cell (the <th> under balance column)
                    $(api.column(balanceColumn).footer()).html(
                        "₱" + total.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    );
                }


            });



            //  Branch filter change
            $("#branchFilter").on("change", function () {
                let branch = $(this).val();
                walletTable.ajax.url("../api/fetch_e-wallets.php?branch_id=" + branch).load();
            });

        });



        // add new wallet
        $(document).ready(function () {

            $("#saveWalletBtn").click(function () {

                let walletName = $("#walletName").val();
                let accountNumber = $("#accountNumber").val();
                let accountLabel = $("#accountLabel").val();
                let initialBalance = $("#initialBalance").val();
                let status = $("#walletStatus").is(":checked") ? 1 : 0;

                if (!walletName || !accountNumber) {
                    alert("Please fill out all required fields.");
                    return;
                }

                $.ajax({
                    url: "../processes/add_new_wallet.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        walletName: walletName,
                        accountNumber: accountNumber,
                        accountLabel: accountLabel,
                        initialBalance: initialBalance,
                        status: status
                    },

                    success: function (response) {
                        if (response.success) {
                            $("#addWalletModal").modal("hide");

                            // Clear form
                            $("#addWalletForm")[0].reset();
                            $("#walletStatus").prop("checked", true);

                            // Reload the datatable
                            $("#walletTable").DataTable().ajax.reload(null, false);


                            // Show global alert
                            let alertHtml = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message || "Branch added successfully."}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                            $("#globalAlertArea").html(alertHtml);

                            setTimeout(() => $(".alert").alert('close'), 3000);

                        } else {
                            alert(response.message || "Failed to add wallet.");
                        }
                    },

                    error: function () {
                        alert("An error occurred while saving.");
                    }
                });
            });
        });

        let pendingWalletData = null;

        // edit wallet
        $(document).on("click", ".edit-wallet-btn", function () {
            let walletId = $(this).data("id");

            $.ajax({
                url: "../api/fetch_single_wallet.php",
                type: "GET",
                dataType: "json",
                data: { id: walletId },

                success: function (response) {
                    if (response.success) {

                        let w = response.wallet;

                        $("#editWalletId").val(w.id);
                        $("#editWalletName").val(w.account_name);
                        $("#editAccountNumber").val(w.account_number);
                        $("#editAccountLabel").val(w.label);
                        $("#editWalletModal").data("current-balance", w.current_balance);
                        $("#editWalletStatus").prop("checked", w.status == 1);
                        $("#currentBalance").val(w.current_balance);
                        $("#editWalletModal").modal("show");

                    } else {
                        alert("Failed to fetch wallet info.");
                    }
                },

                error: function () {
                    alert("An error occurred while fetching wallet data.");
                }
            });
        });


        $("#updateWalletBtn").off('click').on('click', function () {

            const id = $("#editWalletId").val();
            const walletName = $("#editWalletName").val();
            const accountNumber = $("#editAccountNumber").val();
            const accountLabel = $("#editAccountLabel").val();
            const amount = parseFloat($("#editBalance").val());
            const status = $("#editWalletStatus").is(":checked") ? 1 : 0;
            const changeReason = $("#changeReason").val();
            const remarks = $("#remarks").val();

            // Current balance (must be available in dataset or fetched earlier)
            const currentBalance = parseFloat(
                $("#editWalletModal").data("current-balance")
            ) || 0;

            
            // if (!amount || amount <= 0) {
            //     alert("Please enter a valid amount greater than zero.");
            //     return;
            // }
            if (!changeReason) {
                alert("Please select a reason for the balance change.");
                return;
            }

            //  Deduct validation
            if (changeReason === "deduct_e-wallet" && amount > currentBalance) {
                alert("Insufficient wallet balance. Deduction cannot proceed.");
                return;
            }

            // Compute new balance
            let newBalance = currentBalance;

            if (changeReason === "initial_balance") {
                newBalance = amount;
            } else if (changeReason === "add_e-wallet") {
                newBalance = currentBalance + amount;
            } else if (changeReason === "deduct_e-wallet") {
                newBalance = currentBalance - amount;
            }

            // Human labels
            const reasonLabels = {
                "initial_balance": "Set Initial Balance",
                "add_e-wallet": "Add to Wallet",
                "deduct_e-wallet": "Deduct from Wallet"
            };

            // Store pending data
            pendingWalletData = {
                id,
                walletName,
                accountNumber,
                accountLabel,
                balance: newBalance,
                status,
                changeReason,
                remarks
            };

            // Populate confirmation modal
            $("#confirmAction").text(reasonLabels[changeReason]);
            $("#confirmCurrentBalance").text(currentBalance.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $("#confirmAdjustment").text(amount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $("#confirmNewBalance").text(newBalance.toLocaleString(undefined, { minimumFractionDigits: 2 }));

            // close edit modal
            $("#editWalletModal").modal("hide");
            // Show confirmation
            $("#confirmWalletModal").modal("show");
        });


        $("#confirmUpdateWalletBtn").off('click').on('click', function () {

            if (!pendingWalletData) return;

            $.ajax({
                url: "../processes/edit_e-wallet.php",
                type: "POST",
                dataType: "json",
                data: pendingWalletData,

                success: function (response) {
                    if (response.success) {

                        $("#confirmWalletModal").modal("hide");
                        $("#editWalletModal").modal("hide");

                        $("#walletTable").DataTable().ajax.reload(null, false);

                                $("#globalAlertArea").html(`
                            <div class="alert alert-success alert-dismissible fade show">
                                ${response.message || "Wallet updated successfully."}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);

                        setTimeout(() => $(".alert").alert('close'), 2000);
                     
                    // reload page after 3 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                    } else {
                        alert(response.message || "Failed to update wallet.");
                    }
                },

                error: function () {
                    alert("An error occurred while saving wallet changes.");
                }
            });
        });


        // Remarks card click is set when clicked
        document.querySelectorAll('.remark-card').forEach(card => {
            card.addEventListener('click', function () {
                const textarea = document.getElementById('remarks');

                // Remove active state from others
                document.querySelectorAll('.remark-card').forEach(c => {
                    c.classList.remove('active');
                });

                // Set active state
                this.classList.add('active');

                // Replace textarea value
                textarea.value = this.dataset.value;
                textarea.focus();
            });
        });

        // toolkit
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

    </script>
</body>

</html>