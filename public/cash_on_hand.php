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
            <h1 class="mb-4">Cash On Hand </h1>

            <!-- Cash On Hand Display -->
            <div class="row">
                <div class="col-lg-8 col-xl-6">
                    <div class="card cash-card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="bi bi-cash-stack me-2 text-primary"></i>
                                Current Cash On Hand
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="cash-amount mb-3" id="currentCashAmount">
                                <!-- php code to fetch current branch coh from branches.coh-->
                                <?php
                                echo '₱' . number_format(fetchCurrentBranchCoh($_SESSION['branch_id']), 2);
                                ?>
                            </div>

                            <div class="cash-info mb-4">
                                <div class="row">
                                    <div class="col-6 text-start">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <div class="fw-medium" id="lastUpdatedDate">-</div>
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            <span id="lastUpdatedTime">-</span>
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Updated By</small>
                                        <div class="fw-medium" id="updatedBy">-</div>
                                    </div>
                                </div>

                            </div>


                            <button class="btn btn-primary edit-btn" id="editCashBtn">
                                <i class="bi bi-pencil-square me-1"></i> Edit Cash
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="col-lg-4 col-xl-6 mt-4 mt-lg-0">
                    <div class="row">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-clock-history me-2"></i>Recent Updates
                                    </h6>
                                    <div class="list-group list-group-flush" id="updateHistory">
                                        <!-- fetch recent coh logs -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Update History (Optional Expanded View) -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-archive me-2"></i>Cash Logs
                            </h5>
                            <button class="btn btn-sm btn-outline-secondary" id="toggleHistory">
                                <i class="bi bi-chevron-down"></i> Expand
                            </button>
                        </div>
                        <div class="card-body" id="historyDetails" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date & Time</th>
                                            <th>Transaction Type.</th>
                                            <th>Amount Adjustment</th>
                                            <th>Previous Balance</th>
                                            <th>New Balance</th>
                                            <th>Updated By</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- populate coh logs -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Cash Modal -->
    <div class="modal fade" id="editCashModal" tabindex="-1" aria-labelledby="editCashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCashModalLabel">Update Cash On Hand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCashForm">
                        <div class="mb-4 text-center">
                            <div class="text-muted mb-2">Current Amount</div>
                            <div class="h3 text-success" id="currentAmountDisplay"></div>
                        </div>

                        <div class="mb-3">
                            <label for="newAmount" class="form-label">Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control form-control-lg" id="newAmount"
                                    placeholder="Enter amount for cash adjustment" step="0.01" min="0" required>
                            </div>
                            <div class="form-text">
                                Enter the exact physical cash count
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="changeReason" class="form-label">
                                Reason for Change
                                <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                                    data-bs-html="true" title="
                                    <strong>Set Initial Balance</strong>: Replaces current cash<br>
                                    <strong>Add to Cash</strong>: Adds amount to current cash<br>
                                    <strong>Deduct from Cash</strong>: Deducts amount (must be sufficient)
                                    ">
                                </i>
                            </label>

                            <select class="form-select" id="changeReason" required>
                                <option value="">Select reason</option>
                                <option value="initial_balance">Set Initial Balance</option>
                                <option value="add_cash">Add to Cash</option>
                                <option value="deduct_cash">Deduct from Cash</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks (Optional)</label>

                            <div class="remark-stack mb-2">
                                <div class="remark-card" data-value="Set new initial balance">
                                    Set new initial balance
                                </div>
                                <div class="remark-card" data-value="Add cash on hand">
                                    Add cash on hand
                                </div>
                                <div class="remark-card" data-value="Deduct for expenses">
                                    Deduct for expenses
                                </div>
                                <div class="remark-card" data-value="Cash reconciliation adjustment">
                                    Cash reconciliation adjustment
                                </div>
                                <div class="remark-card" data-value="Manual adjustment">
                                    Manual adjustment
                                </div>
                            </div>

                            <textarea class="form-control" id="remarks" rows="2"
                                placeholder="Add any additional notes..."></textarea>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCashBtn">Update Cash</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Confirm Cash Update Modal -->
    <div class="modal fade" id="confirmCashModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Cash Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2"><strong>Action:</strong></p>
                    <div class="alert alert-secondary py-2" id="confirmReason"></div>

                    <!-- current coh amount -->
                    <p class="mb-2"><strong>Current Amount:</strong></p>
                    <div class="alert alert-warning py-2">
                        ₱ <b><span id="confirmCurrentAmount"></span></b>
                    </div>

                    <p class="mb-2"><strong>Amount Adjustment:</strong></p>
                    <div class="alert alert-success py-2">
                        ₱ <b><span id="confirmAmount"></span></b>
                    </div>

                    <!-- new amount -->
                    <p class="mb-2"><strong>New Amount:</strong></p>
                    <div class="alert alert-info py-2">
                        ₱ <b><span id="confirmNewAmount"></span></b>
                    </div>

                    <p class="text-muted small mb-0">
                        Please confirm that this adjustment is correct before saving.
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmSaveCashBtn">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>



    <?php include '../views/scripts.php'; ?>
    <?php include '../views/footer.php'; ?>
    <script>
        $(document).ready(function () {

            // --- Initialize COH DataTable ---
            const cohTable = $("#historyDetails table").DataTable({
                ajax: { url: "../api/fetch_coh_logs.php", dataSrc: "data" },
                columns: [
                    { data: "no" }, 
                    { data: "datetime" }, 
                    { data: "type" },
                    {data: "amount_adjustment"},
                    { data: "previous_balance" }, 
                    { data: "new_balance" },
                    { data: "updated_by" }, 
                    { data: "remarks" }
                ],
                order: [[1, "desc"]],
                pageLength: 10,
                lengthChange: false,
                responsive: true,
                autoWidth: false,
                scrollX: true
            });

            // --- Toggle expand ---
            $("#toggleHistory").click(function () {
                $("#historyDetails").slideToggle(400, function () {
                    cohTable.columns.adjust().draw();
                });
                const icon = $(this).find("i");
                if ($("#historyDetails").is(":visible")) {
                    icon.removeClass("bi-chevron-down").addClass("bi-chevron-up");
                    $(this).contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(" Collapse");
                } else {
                    icon.removeClass("bi-chevron-up").addClass("bi-chevron-down");
                    $(this).contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(" Expand");
                }
            });

            // --- Open Edit Cash Modal ---
            $("#editCashBtn").click(function () {
                $("#editCashForm")[0].reset();
                $.ajax({
                    url: "../api/fetch_current_coh.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            let currentCoh = parseFloat(response.current_coh) || 0;
                            $("#currentAmountDisplay").text("₱" + currentCoh.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                            $("#editCashModal").modal("show");
                        } else {
                            alert(response.message || "Failed to fetch current COH.");
                        }
                    },
                    error: function () { alert("An error occurred while fetching current COH."); }
                });
            });

            // --- Save Cash On Hand ---
            // $("#saveCashBtn").off('click').on('click', function () { // .off() ensures no duplicate bindings
            //     const newAmount = parseFloat($("#newAmount").val());
            //     const reason = $("#changeReason").val();
            //     const remarks = $("#remarks").val();

            //     if (!newAmount || !reason) {
            //         alert("Please complete all required fields.");
            //         return;
            //     }

            //     if (reason === 'deduct_cash' && newAmount <= 0) {
            //         alert("Deduction amount must be greater than zero.");
            //         return;
            //     }


            //     $.ajax({
            //         url: "../processes/edit_current_coh.php",
            //         type: "POST",
            //         data: { newAmount, reason, remarks },
            //         dataType: "json",
            //         success: function (response) {
            //             if (response.success) {
            //                 $("#editCashModal").modal("hide");
            //                 location.reload(); // Reload to reflect changes
            //             } else {
            //                 alert(response.message || "Failed to update Cash On Hand.");
            //             }
            //         },
            //         error: function () { alert("An error occurred while saving."); }
            //     });
            // });

            let pendingCashData = null;

            $("#saveCashBtn").off('click').on('click', function () {

                const newAmount = parseFloat($("#newAmount").val());
                const reason = $("#changeReason").val();
                const remarks = $("#remarks").val();

                // Get current COH (numeric)
                const currentAmountText = $("#currentAmountDisplay").text()
                    .replace('₱', '')
                    .replace(/,/g, '');
                const currentAmount = parseFloat(currentAmountText) || 0;

                if (!newAmount || !reason) {
                    alert("Please complete all required fields.");
                    return;
                }

                //  PRE-CHECKS BEFORE CONFIRMATION
                if (reason === 'deduct_cash') {

                    if (newAmount <= 0) {
                        alert("Deduction amount must be greater than zero.");
                        return;
                    }

                    if (newAmount > currentAmount) {
                        alert("Insufficient cash on hand. Deduction cannot proceed.");
                        return;
                    }
                }

                // Human-readable labels
                const reasonLabels = {
                    initial_balance: "Set Initial Balance",
                    add_cash: "Add to Cash",
                    deduct_cash: "Deduct from Cash"
                };

                // Store temporarily
                pendingCashData = { newAmount, reason, remarks };

                // Populate confirmation modal
                $("#confirmReason").text(reasonLabels[reason]);
                $("#confirmCurrentAmount").text(
                    currentAmount.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );

                $("#confirmAmount").text(
                    newAmount.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );

                // Calculate projected new balance
                let newBalance = currentAmount;
                if (reason === 'initial_balance') {
                    newBalance = newAmount;
                } else if (reason === 'add_cash') {
                    newBalance = currentAmount + newAmount;
                } else if (reason === 'deduct_cash') {
                    newBalance = currentAmount - newAmount;
                }

                $("#confirmNewAmount").text(
                    newBalance.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );

                // Show confirmation modal ONLY if valid
                $("#confirmCashModal").modal("show");

                // Close edit modal AFTER validation
                $("#editCashModal").modal("hide");
            });



            $("#confirmSaveCashBtn").off('click').on('click', function () {

                if (!pendingCashData) return;

                $.ajax({
                    url: "../processes/edit_current_coh.php",
                    type: "POST",
                    data: pendingCashData,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $("#confirmCashModal").modal("hide");
                            $("#editCashModal").modal("hide");
                            location.reload();
                        } else {
                            alert(response.message || "Failed to update Cash On Hand.");
                        }
                    },
                    error: function () {
                        alert("An error occurred while saving.");
                    }
                });
            });




            // --- Last COH info ---
            function loadLastCohInfo() {
                $.ajax({
                    url: "../api/fetch_last_coh_log.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $("#lastUpdatedDate").text(response.date);
                            $("#lastUpdatedTime").text(response.time);
                            $("#updatedBy").text(response.updated_by);
                        } else {
                            $("#lastUpdatedDate, #lastUpdatedTime, #updatedBy").text("-");
                        }
                    },
                    error: function () {
                        $("#lastUpdatedDate, #lastUpdatedTime, #updatedBy").text("-");
                    }
                });
            }

            // --- Recent updates ---
            function loadRecentUpdates() {
                $.ajax({
                    url: "../api/fetch_recent_coh_logs.php",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        const $updateHistory = $("#updateHistory");
                        $updateHistory.empty();
                        if (response.success && response.logs.length > 0) {
                            response.logs.forEach(log => {
                                $updateHistory.append(`
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <small>${log.description}</small>
                                    <small class="text-muted">${log.datetime}</small>
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-person-circle me-1"></i>${log.updated_by}
                                </div>
                            </div>
                        `);
                            });
                        } else {
                            $updateHistory.append(`<div class="list-group-item border-0 px-0 py-2"><small class="text-muted">No recent updates.</small></div>`);
                        }
                    },
                    error: function () {
                        $("#updateHistory").html(`<div class="list-group-item border-0 px-0 py-2"><small class="text-muted">Failed to load updates.</small></div>`);
                    }
                });
            }

            // Call on page load
            loadLastCohInfo();
            loadRecentUpdates();

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

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });


    </script>

</body>

</html>