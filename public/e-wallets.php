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
        <div id="globalAlertArea"></div>
        <div class="container-fluid">
            <h1 class="mb-4">Wallet Accounts</h1>

            <!-- Add Wallet Button -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Manage your e-wallet accounts</h5>
                            <p class="text-muted mb-0">Add or edit wallet accounts for transactions</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWalletModal">
                            <i class="bi bi-plus-circle"></i> Add New Wallet
                        </button>
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

                    <div class="mb-3">
                        <label class="form-label">Wallet Name</label>
                        <select class="form-select" id="editWalletName" required>
                            <option value="">Select wallet type</option>
                            <option value="GCash">GCash</option>
                            <option value="Maya">Maya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Number / Label</label>
                        <input type="text" class="form-control" id="editAccountNumber" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Label (Optional)</label>
                        <input type="text" class="form-control" id="editAccountLabel">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Balance</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="editBalance" step="0.01" min="0">
                        </div>
                    </div>

                    <!-- Toggle Switch for Status -->
                    <div class="mb-3">
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
                <button class="btn btn-primary" id="updateWalletBtn">Update Wallet</button>
            </div>

        </div>
    </div>
</div>




    <?php include '../views/scripts.php'; ?>
    <?php include '../views/footer.php'; ?>
    <script>
        //    populate e-wallet datatable
        $(document).ready(function () {

            const walletTable = $("#walletTable").DataTable({
                ajax: {
                    url: "../api/fetch_e-wallets.php",
                    dataSrc: "data"
                },
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
                        </div>
                    `;
                        }
                    },
                    {
                        data: "account_number",
                        render: function (data, type, row) {
                            return `
                        <div>
                            <div class="fw-medium">${row.account_number}</div>
                            <small class="text-muted">${row.label}</small>
                        </div>
                    `;
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
                        render: function (data, type, row) {
                            return `
                        <button class="btn btn-sm btn-outline-warning edit-wallet-btn" data-id="${data}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                      
                    `;
                        },
                        orderable: false
                    }
                ],
                order: [[0, "asc"]],
                responsive: true,
                autoWidth: false,
                scrollX: true,
                footerCallback: function (row, data, start, end, display) {
                    let api = this.api();
                    // Sum total balance
                    let total = api.column(3, { page: 'current' }).data().reduce(function (a, b) {
                        return parseFloat(a.toString().replace(/,/g, '')) + parseFloat(b.toString().replace(/,/g, ''));
                    }, 0);

                    $(api.column(3).footer()).html(`₱${total.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
                }
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
                $("#editBalance").val(w.current_balance);
                $("#editWalletStatus").prop("checked", w.status == 1);

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


$("#updateWalletBtn").click(function () {

    let id = $("#editWalletId").val();
    let walletName = $("#editWalletName").val();
    let accountNumber = $("#editAccountNumber").val();
    let accountLabel = $("#editAccountLabel").val();
    let balance = $("#editBalance").val();
    let status = $("#editWalletStatus").is(":checked") ? 1 : 0;

    if (!walletName || !accountNumber) {
        alert("Please fill out all required fields.");
        return;
    }

    $.ajax({
        url: "../processes/edit_e-wallet.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            walletName: walletName,
            accountNumber: accountNumber,
            accountLabel: accountLabel,
            balance: balance,
            status: status
        },

        success: function (response) {
            if (response.success) {
                $("#editWalletModal").modal("hide");

                // Reload DataTable
                $("#walletTable").DataTable().ajax.reload(null, false);

               // Show global alert
                            let alertHtml = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message || "Wallet updated successfully."}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                            $("#globalAlertArea").html(alertHtml);

                            setTimeout(() => $(".alert").alert('close'), 3000);
            } else {
                alert(response.message || "Failed to update wallet.");
            }
        },

        error: function () {
            alert("An error occurred while saving changes.");
        }
    });
});



    </script>
</body>

</html>