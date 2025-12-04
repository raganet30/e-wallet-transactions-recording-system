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
                                    <label class="form-label">Type</label>
                                    <select class="form-select" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Failed</option>
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
                                            <th>ID</th>
                                            <th>Date & Time</th>
                                            <th>Customer Name</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Reference No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample data - replace with PHP/DB data -->
                                        <tr>
                                            <td>TRX001</td>
                                            <td>2023-12-01 14:30</td>
                                            <td>Juan Dela Cruz</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td class="fw-bold">₱1,500.00</td>
                                            <td><span class="status-completed"><i
                                                        class="bi bi-check-circle me-1"></i>Completed</span></td>
                                            <td>GC123456789</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-btn"
                                                    data-id="TRX001">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="TRX001">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>TRX002</td>
                                            <td>2023-12-01 15:45</td>
                                            <td>Maria Santos</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                            <td class="fw-bold">₱2,800.00</td>
                                            <td><span class="status-pending"><i
                                                        class="bi bi-clock me-1"></i>Pending</span></td>
                                            <td>MA987654321</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-btn"
                                                    data-id="TRX002">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="TRX002">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>TRX003</td>
                                            <td>2023-12-02 09:15</td>
                                            <td>Pedro Reyes</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                            <td class="fw-bold">₱500.00</td>
                                            <td><span class="status-completed"><i
                                                        class="bi bi-check-circle me-1"></i>Completed</span></td>
                                            <td>CASH001</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-btn"
                                                    data-id="TRX003">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="TRX003">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total:</th>
                                            <th class="text-primary">₱4,800.00</th>
                                            <th colspan="3"></th>
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
                                    <label class="form-label">Transaction Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" name="customer_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" step="0.01" min="0"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" name="reference_no">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="3"></textarea>
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

            <!-- View Transaction Modal -->
            <div class="modal fade" id="viewTransactionModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Transaction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="transactionDetails">
                            <!-- Details will be loaded here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <script>
        ///demo script for datatable
// Initialize DataTable
        $(document).ready(function() {
            var table = $('#transactionsTable').DataTable({
                "pageLength": 10,
                "order": [[1, 'desc']], // Sort by date descending
                "language": {
                    "search": "Search transactions:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });

            // Filter functionality
            $('#typeFilter, #statusFilter').on('change', function() {
                table.draw();
            });

            $('#dateFilter').on('change', function() {
                table.draw();
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var type = $('#typeFilter').val();
                    var status = $('#statusFilter').val();
                    var date = $('#dateFilter').val();
                    
                    var rowType = data[3].toLowerCase().replace(/<[^>]*>/g, '');
                    var rowStatus = data[5].toLowerCase().replace(/<[^>]*>/g, '');
                    var rowDate = data[1].split(' ')[0]; // Get date part only
                    
                    if (type && rowType.indexOf(type.toLowerCase()) === -1) return false;
                    if (status && rowStatus.indexOf(status.toLowerCase()) === -1) return false;
                    if (date && rowDate !== date) return false;
                    
                    return true;
                }
            );

            // Reset filters
            $('#resetFilters').click(function() {
                $('#typeFilter, #statusFilter').val('');
                $('#dateFilter').val('');
                table.search('').draw();
            });

            // View transaction
            $('.view-btn').click(function() {
                var transactionId = $(this).data('id');
                // In real app, fetch from server via AJAX
                var details = `
                    <div class="row">
                        <div class="col-6 mb-3">
                            <strong>Transaction ID:</strong><br>
                            ${transactionId}
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Date:</strong><br>
                            2023-12-01 14:30
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Customer:</strong><br>
                            Juan Dela Cruz
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Type:</strong><br>
                            <span class="badge badge-gcash">GCash</span>
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Amount:</strong><br>
                            ₱1,500.00
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Status:</strong><br>
                            <span class="status-completed">Completed</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Reference No:</strong><br>
                            GC123456789
                        </div>
                    </div>
                `;
                $('#transactionDetails').html(details);
                $('#viewTransactionModal').modal('show');
            });

            // Delete transaction
            $('.delete-btn').click(function() {
                var transactionId = $(this).data('id');
                $('#deleteTransactionId').val(transactionId);
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').click(function() {
                var transactionId = $('#deleteTransactionId').val();
                // In real app, delete via AJAX
                console.log('Deleting transaction:', transactionId);
                
                // Remove row from table
                table.row($('button[data-id="' + transactionId + '"]').closest('tr')).remove().draw();
                
                $('#deleteModal').modal('hide');
                alert('Transaction deleted successfully!');
            });

            // Form submission
            $('#addTransactionForm').submit(function(e) {
                e.preventDefault();
                // In real app, submit via AJAX
                alert('Transaction added successfully!');
                $('#addTransactionModal').modal('hide');
                this.reset();
                
                // Reload or add new row to table
                location.reload(); // Simple reload for demo
            });
        });
    </script>

    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>