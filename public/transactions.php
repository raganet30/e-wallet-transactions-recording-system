<!DOCTYPE html>
<html lang="en">

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
                            <!-- <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div> -->
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addTransactionForm">
                                <div class="mb-3">
                                    <label class="form-label">E-wallet Type</label>
                                    <select class="form-select" name="e_wallet_type" required>
                                        <option value="">Select Type</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" name="reference_no">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Transaction Fee</label>
                                    <input type="number" class="form-control" name="transaction_charge" step="0.01"
                                        min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Transaction Fee thru</label>
                                    <select class="form-select" name="transaction_fee_thru" required>
                                        <option value="">Select Type</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <!-- cash-in / cash-out radio button  -->
                                <div class="mb-3">
                                    <label class="form-label">Transaction Type: </label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="transaction_type"
                                            id="cash-in" value="cash-in" required>
                                        <label class="form-check-label" for="cash-in">
                                            Cash In
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="transaction_type"
                                            id="cash-out" value="cash-out" required>
                                        <label class="form-check-label" for="cash-out">
                                            Cash Out
                                        </label>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="addTransactionForm" class="btn btn-primary">Save
                                Transaction</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Transaction Confirmation Modal -->
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
                                <li class="list-group-item"><strong>Charge:</strong> ₱<span id="c_charge"></span></li>
                                <li class="list-group-item"><strong>Transaction Fee Thru:</strong> <span
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
                                <li class="list-group-item"><strong>Total:</strong> ₱<span id="viewTotal"></span></li>
                                <li class="list-group-item"><strong>Payment Mode:</strong> <span
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script src="../assets/js/script.js"></script>
    <script>
        // =====================
        // ADD TRANSACTION CONFIRMATION
        // =====================

        // Intercept form submit
        document.getElementById("addTransactionForm").addEventListener("submit", function (e) {
            e.preventDefault(); // prevent auto submit
            let confirmModal = new bootstrap.Modal(document.getElementById("confirmAddModal"));
            confirmModal.show();
        });

        // If user confirms
        document.getElementById("confirmAddBtn").addEventListener("click", function () {
            document.getElementById("addTransactionForm").submit();  // now submit
        });


        // =====================
        // VIEW TRANSACTION
        // =====================
        document.querySelectorAll(".view-btn").forEach(btn => {
            btn.addEventListener("click", function () {

                let row = this.closest("tr");
                document.getElementById("viewDate").innerText = row.children[1].innerText;
                document.getElementById("viewRef").innerText = row.children[2].innerText;
                document.getElementById("viewEwallet").innerText = row.children[3].innerText;
                document.getElementById("viewAmount").innerText = row.children[4].innerText.replace("₱", "");
                document.getElementById("viewCharge").innerText = row.children[5].innerText.replace("₱", "");
                document.getElementById("viewTotal").innerText = row.children[6].innerText.replace("₱", "");
                document.getElementById("viewPaymentMode").innerText = row.children[7].innerText;

                let viewModal = new bootstrap.Modal(document.getElementById("viewTransactionModal"));
                viewModal.show();
            });
        });


        // =====================
        // DELETE CONFIRMATION
        // =====================
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                document.getElementById("deleteTransactionId").value = this.dataset.id;
                let deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
                deleteModal.show();
            });
        });

        document.getElementById("confirmDelete").addEventListener("click", function () {
            let id = document.getElementById("deleteTransactionId").value;
            alert("Deleting transaction ID: " + id);

            // TODO: AJAX or form submit here to delete record
        });




        // =====================
        // ADD TRANSACTION CONFIRMATION + HIDE ADD FORM
        // =====================

        const addModal = new bootstrap.Modal(document.getElementById("addTransactionModal"));
        const confirmModal = new bootstrap.Modal(document.getElementById("confirmAddModal"));

        document.getElementById("addTransactionForm").addEventListener("submit", function (e) {
            e.preventDefault(); // stop real submission

            // Get form values
            let ew = document.querySelector("[name='e_wallet_type']").value;
            let ref = document.querySelector("[name='reference_no']").value;
            let amt = document.querySelector("[name='amount']").value;
            let charge = document.querySelector("[name='transaction_charge']").value;
            let thru = document.querySelector("[name='transaction_fee_thru']").value;
            let type = document.querySelector("input[name='transaction_type']:checked")?.value;

            // Fill confirmation modal values
            document.getElementById("c_eWallet").innerText = ew;
            document.getElementById("c_reference").innerText = ref !== "" ? ref : "(none)";
            document.getElementById("c_amount").innerText = parseFloat(amt).toFixed(2);
            document.getElementById("c_charge").innerText = parseFloat(charge).toFixed(2);
            document.getElementById("c_feeThru").innerText = thru;
            document.getElementById("c_transType").innerText = type === "cash-in" ? "Cash In" : "Cash Out";

            // Hide add form modal → show confirmation modal
            addModal.hide();
            setTimeout(() => confirmModal.show(), 300); // slight delay for smooth animation
        });

        // Final confirmation → submit form
        document.getElementById("confirmAddBtn").addEventListener("click", function () {
            document.getElementById("addTransactionForm").submit();
        });

        // User clicks "Cancel" inside confirmation → go back to form
        document.querySelector("#confirmAddModal .btn-secondary").addEventListener("click", function () {
            confirmModal.hide();
            setTimeout(() => addModal.show(), 300);
        });




        // Initialize DataTable with search and auto-compute totals
        $(document).ready(function () {
            var table = $('#transactionsTable').DataTable({
                // DataTable already includes search functionality by default
                paging: true,
                searching: true, // This enables the search box
                ordering: true,
                info: true,
                // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                // // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                // columnDefs: [
                //     { targets: [4, 5, 6], className: 'dt-body-right' },
                //     { targets: [8], orderable: false }
                // ]
            });
        });

    </script>


</body>

</html>