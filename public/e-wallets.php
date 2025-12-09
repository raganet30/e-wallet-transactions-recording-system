<?php
    require '../config/session_checker.php';
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

   <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Wallet Accounts</h1>

            <!-- Add Wallet Button -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Manage your e-wallet accounts</h5>
                            <p class="text-muted mb-0">Add, edit, or remove wallet accounts for transactions</p>
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
                                        <!-- Sample Data - GCash Account -->
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary">
                                                            <i class="bi bi-wallet2"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">GCash</h6>
                                                        <small class="text-muted">Mobile Wallet</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">0917-123-4567</div>
                                                    <small class="text-muted">Primary Account</small>
                                                </div>
                                            </td>
                                            <td data-order="12500.50">
                                                <div class="fw-bold text-success">₱12,500.50</div>
                                                <small class="text-muted">Last updated: Today</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-active">Active</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-wallet-btn" data-id="1">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning edit-wallet-btn" data-id="1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-wallet-btn" data-id="1">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Sample Data - Maya Account -->
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title rounded-circle bg-info bg-opacity-10 text-info">
                                                            <i class="bi bi-credit-card"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Maya</h6>
                                                        <small class="text-muted">Digital Bank</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">0985-987-6543</div>
                                                    <small class="text-muted">Secondary Account</small>
                                                </div>
                                            </td>
                                            <td data-order="8500.75">
                                                <div class="fw-bold text-success">₱8,500.75</div>
                                                <small class="text-muted">Last updated: Yesterday</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-active">Active</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-wallet-btn" data-id="2">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning edit-wallet-btn" data-id="2">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-wallet-btn" data-id="2">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
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
                            <label for="walletName" class="form-label">Wallet Name</label>
                            <select class="form-select" id="walletName" required>
                                <option value="">Select wallet type</option>
                                <option value="GCash">GCash</option>
                                <option value="Maya">Maya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Account Number / Label</label>
                            <input type="text" class="form-control" id="accountNumber" placeholder="e.g., 0917-123-4567" required>
                        </div>
                        <div class="mb-3">
                            <label for="accountLabel" class="form-label">Account Label (Optional)</label>
                            <input type="text" class="form-control" id="accountLabel" placeholder="e.g., Primary Account, Savings">
                        </div>
                        <div class="mb-3">
                            <label for="initialBalance" class="form-label">Initial Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="initialBalance" placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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
 
   
    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
     <script>
        $(document).ready(function () {
            // Initialize DataTable with search and sort
            var table = $('#walletTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                columnDefs: [
                    { targets: [3], className: 'dt-body-right' },
                    { targets: [5], orderable: false }
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var totalBalance = 0;

                    // Calculate total balance for current filtered data
                    api.rows({ search: 'applied' }).every(function () {
                        var rowData = this.data();
                        var balance = $(this.node()).find('td:eq(3)').data('order') || 0;
                        totalBalance += parseFloat(balance);
                    });

                    // Update the footer
                    $(api.column(3).footer()).html(
                        '<div class="fw-bold text-success">₱' + 
                        totalBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + 
                        '</div>'
                    );
                }
            });

            // Save Wallet Button Handler
            $('#saveWalletBtn').on('click', function() {
                var walletName = $('#walletName').val();
                var accountNumber = $('#accountNumber').val();
                var accountLabel = $('#accountLabel').val();
                var initialBalance = $('#initialBalance').val();
                var status = $('#status').val();

                if (!walletName || !accountNumber) {
                    alert('Please fill in all required fields');
                    return;
                }

                // Add new row to the table
                table.row.add([
                    table.data().count() + 1,
                    '<div class="d-flex align-items-center">' +
                        '<div class="avatar-sm me-3">' +
                            '<span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary">' +
                                '<i class="bi bi-wallet2"></i>' +
                            '</span>' +
                        '</div>' +
                        '<div>' +
                            '<h6 class="mb-0">' + walletName + '</h6>' +
                            '<small class="text-muted">Mobile Wallet</small>' +
                        '</div>' +
                    '</div>',
                    '<div>' +
                        '<div class="fw-medium">' + accountNumber + '</div>' +
                        '<small class="text-muted">' + (accountLabel || 'New Account') + '</small>' +
                    '</div>',
                    '<div class="fw-bold text-success">₱' + 
                        parseFloat(initialBalance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + 
                    '</div>' +
                    '<small class="text-muted">Last updated: Just now</small>',
                    '<span class="badge badge-' + (status === 'active' ? 'active' : 'inactive') + '">' + 
                        (status === 'active' ? 'Active' : 'Inactive') + 
                    '</span>',
                    '<button class="btn btn-sm btn-outline-primary view-wallet-btn" data-id="' + (table.data().count() + 1) + '">' +
                        '<i class="bi bi-eye"></i> View' +
                    '</button>' +
                    '<button class="btn btn-sm btn-outline-warning edit-wallet-btn" data-id="' + (table.data().count() + 1) + '">' +
                        '<i class="bi bi-pencil"></i> Edit' +
                    '</button>' +
                    '<button class="btn btn-sm btn-outline-danger delete-wallet-btn" data-id="' + (table.data().count() + 1) + '">' +
                        '<i class="bi bi-trash"></i>' +
                    '</button>'
                ]).draw();

                // Close modal and reset form
                $('#addWalletModal').modal('hide');
                $('#addWalletForm')[0].reset();
                
                alert('Wallet added successfully!');
            });

            // Action button handlers
            $(document).on('click', '.view-wallet-btn', function() {
                var walletId = $(this).data('id');
                alert('View wallet ID: ' + walletId);
                // Implement view functionality here
            });

            $(document).on('click', '.edit-wallet-btn', function() {
                var walletId = $(this).data('id');
                alert('Edit wallet ID: ' + walletId);
                // Implement edit functionality here
            });

            $(document).on('click', '.delete-wallet-btn', function() {
                var walletId = $(this).data('id');
                if (confirm('Are you sure you want to delete this wallet?')) {
                    var row = $(this).closest('tr');
                    table.row(row).remove().draw();
                    alert('Wallet deleted successfully!');
                }
            });
        });
    </script>
</body>

</html>