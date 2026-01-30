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
                            <!-- <h5 class="mb-0">Manage your e-wallet accounts</h5> -->
                            <p class="text-muted mb-0">Edit wallet balance for transactions</p>
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


            <!-- <div class="row" id="walletCards"></div> -->

            <div class="row">
                <!-- LEFT: Wallet Cards -->
                <div class="col-lg-6">
                    <div class="row">
                        <!-- Wallet Card Template -->
                        <div id="walletCardTemplate" class="col-md-6 mb-3 d-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">

                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar-title rounded-circle me-3 wallet-icon">
                                            <i class="bi"></i>
                                        </span>
                                        <div>
                                            <h6 class="mb-0 wallet-name"></h6>
                                            <small class="text-muted wallet-number"></small>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="fw-bold text-success fs-5 wallet-balance"></div>
                                        <small class="text-muted wallet-updated"></small>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-sm btn-outline-warning edit-wallet-btn">
                                            <i class="bi bi-pencil"></i> Edit balance
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row" id="walletCards"></div>



                    </div>
                </div>

                <!-- RIGHT: Recent Updates -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">

                            <h6 class="card-title mb-3">
                                <i class="bi bi-clock-history me-2"></i> Recent wallet balance updates
                            </h6>

                            <!-- Scrollable container -->
                            <div class="list-group list-group-flush flex-grow-1 overflow-auto" id="updateHistory"
                                style="max-height: 260px;">

                                <!-- Update Item -->

                                <!-- more items -->
                            </div>
                            <!-- Recent Update Template -->
                            <div id="updateItemTemplate" class="list-group-item px-0 d-none">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted update-type"></small>
                                        <div class="fw-semibold update-balance"></div>
                                    </div>
                                    <small class="text-muted text-end update-datetime"></small>
                                </div>

                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="bi bi-person-circle me-1"></i>
                                        <span class="update-user"></span>
                                    </small>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>

            <!-- Total Balance -->
            <div class="row mt-3">
                <div class="col-12">
                    <h5>
                        Total Balance:
                        <span id="totalWalletBalance" class="fw-bold text-success">₱0.00</span>
                    </h5>
                </div>
            </div>

            <hr>

            <!-- E-wallet Logs expandale table which will show the wallet logs such as No.	Date & Time	Transaction Type.	Previous Balance	New Balance	Updated By	Remarks   -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">

                        <!-- Header (Toggle) -->
                        <div class="card-header d-flex justify-content-between align-items-center" role="button"
                            data-bs-toggle="collapse" data-bs-target="#walletLogsCollapse" aria-expanded="false">

                            <h6 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                E-Wallet Logs
                            </h6>

                            <i class="bi bi-chevron-down toggle-icon"></i>
                        </div>

                        <!-- Collapsible Body -->
                        <div id="walletLogsCollapse" class="collapse">
                            <div class="card-body">

                                <table id="walletLogsTable" class="table table-striped table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date & Time</th>
                                            <th>Transaction Type</th>
                                            <th>Previous Balance</th>
                                            <th>New Balance</th>
                                            <th>Updated By</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
                                <input type="number" class="form-control" id="currentBalance" step="0.01" min="0"
                                    readonly>
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
        let walletLogsTable;

        // populate wallet accounts
        $(document).ready(function () {

            const userRole = $("#userRole").val();
            const sessionBranch = $("#sessionBranch").val();
            const initialBranchID = (userRole === "super_admin") ? "all" : sessionBranch;

            function loadWalletCards(branchId) {

                $.getJSON("../api/fetch_e-wallets.php", { branch_id: branchId }, function (res) {

                    const $container = $("#walletCards");
                    const $template = $("#walletCardTemplate");
                    let totalBalance = 0;

                    $container.empty();

                    res.data.forEach(w => {

                        const balance = parseFloat(
                            (w.current_balance || "0").toString().replace(/,/g, "")
                        );

                        totalBalance += balance;

                        const isGcash = w.account_name.toLowerCase() === "gcash";
                        const iconClass = isGcash ? "bi-wallet2" : "bi-credit-card";
                        const colorClass = isGcash
                            ? "bg-primary bg-opacity-10 text-primary"
                            : "bg-info bg-opacity-10 text-info";

                        const $card = $template.clone().removeAttr("id").removeClass("d-none");

                        // Populate fields
                        $card.find(".wallet-name").text(w.account_name);
                        $card.find(".wallet-number").text(w.account_number);
                        $card.find(".wallet-balance").text(
                            "₱" + balance.toLocaleString("en-PH", { minimumFractionDigits: 2 })
                        );
                        $card.find(".wallet-updated").text(
                            "Last updated: " +
                            new Date(w.updated_at)
                                .toLocaleString("en-US", { hour12: true })
                                .replace(",", "")
                        );

                        // Icon
                        $card.find(".wallet-icon")
                            .addClass(colorClass)
                            .find("i")
                            .addClass(iconClass);

                        // Button
                        $card.find(".edit-wallet-btn").attr("data-id", w.id);

                        $container.append($card);
                    });

                    $("#totalWalletBalance").text(
                        "₱" + totalBalance.toLocaleString("en-PH", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                    );
                });
            }

            function loadRecentUpdates() {

                $.getJSON("../api/fetch_e-wallet_logs.php", function (res) {

                    const $container = $("#updateHistory");
                    const $template = $("#updateItemTemplate");

                    $container.empty();

                    if (!res.data || res.data.length === 0) {
                        $container.append(`
                <div class="text-center text-muted py-3">
                    No recent updates
                </div>
            `);
                        return;
                    }

                    // Show latest 10 only
                    res.data.slice(0, 10).forEach(log => {

                        const $item = $template.clone().removeAttr("id").removeClass("d-none");

                        const prev = log.previous_balance;
                        const next = log.new_balance;

                        // Detect increase / decrease
                        const prevVal = parseFloat(prev.replace(/[₱,]/g, ""));
                        const nextVal = parseFloat(next.replace(/[₱,]/g, ""));

                        let balanceClass = "";
                        if (nextVal > prevVal) balanceClass = "text-secondary";
                        else if (nextVal < prevVal) balanceClass = "text-danger";

                        // Populate fields
                        $item.find(".update-type").text(log.wallet_type);
                        $item.find(".update-balance")
                            .addClass(balanceClass)
                            .html(`
                    ${prev}
                    <i class="bi bi-arrow-right mx-1"></i>
                    ${next}
                `);

                        const d = new Date(log.datetime);

                        const datePart = d.toLocaleDateString("en-US", {
                            month: "2-digit",
                            day: "2-digit",
                            year: "numeric"
                        });

                        const timePart = d.toLocaleTimeString("en-US", {
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true
                        });

                        $item.find(".update-datetime").html(`${datePart}<br>${timePart}`);


                        $item.find(".update-user").text(log.updated_by);

                        $container.append($item);
                    });
                });
            }



            let walletLogsTableInitialized = false;

            $("#walletLogsCollapse").on("shown.bs.collapse", function () {

                if (walletLogsTableInitialized) return;

                $("#walletLogsTable").DataTable({
                    ajax: {
                        url: "../api/fetch_e-wallet_logs.php",
                        dataSrc: "data"
                    },
                    order: [[1, "desc"]],
                    pageLength: 10,
                    responsive: true,
                    columns: [
                        { data: "no" },
                        { data: "datetime" },
                        { data: "type" },
                        { data: "previous_balance" },
                        { data: "new_balance" },
                        { data: "updated_by" },
                        {
                            data: "remarks",
                            defaultContent: "",
                            render: data => data || "-"
                        }
                    ]
                });

                walletLogsTableInitialized = true;
            });



            // load recent updates
            loadRecentUpdates();

            // Initial load
            loadWalletCards(initialBranchID);

            // Branch filter
            $("#branchFilter").on("change", function () {
                loadWalletCards($(this).val());
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

                        setTimeout(() => $(".alert").alert('close'), 1000);

                        // reload page after 3 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 1000);

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