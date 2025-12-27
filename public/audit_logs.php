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
            <h1 class="mb-4">Audit Logs </h1>

            <!-- Filters Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card filter-card">
                        <div class="card-body">

                            <div class="row g-2">
                                <?php include '../config/branch_filtering.php'; ?>
                                <div class="col-md-2">
                                    <label for="dateFrom" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="dateFrom">
                                </div>
                                <div class="col-md-2">
                                    <label for="dateTo" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="dateTo">
                                </div>
                                <div class="col-md-2">
                                    <label for="actionType" class="form-label">Action Type</label>
                                    <select class="form-select" id="actionType">
                                        <option value="">All Types</option>
                                        <option value="add_transaction">Add Transaction</option>
                                        <option value="delete_transaction">Delete Transaction</option>
                                        <option value="update_profile">Update Profile</option>
                                        <option value="update_wallet">Update E-Wallet</option>
                                        <!-- other action types -->
                                        <!-- action types based on database -->
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100 me-2" id="applyFilters">
                                        <i class="bi bi-filter me-1"></i> Apply Filters
                                    </button>

                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-outline-secondary" id="resetFilters" title="Reset Filters">
                                        <i class="bi bi-arrow-clockwise"></i>Reset Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Logs Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-check me-2"></i>Audit Logs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="auditLogsTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Action Type</th>
                                            <th>Description</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- Sample Audit Log : Transaction -->
                                        <!-- <tr>
                                            <td>1</td>
                                            <td>
                                                12/25/2025 08:30
                                            </td>
                                            <td>
                                                <span class="badge badge-transaction">Add Transaction</span>
                                            </td>
                                            <td>
                                                Added new transaction REF#123456789. GCash | Cashin : 1,000.00 , Charge:
                                                10.00
                                            </td>

                                            <td>
                                                Cashier
                                            </td>

                                        </tr> -->

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </div>
    <?php include '../views/scripts.php'; ?>
    <script>
        $(document).ready(function () {

            const table = $('#auditLogsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [[1, 'desc']], // default sort by date
                ajax: {
                    url: '../api/fetch_audit_logs.php',
                    type: 'POST',
                    data: function (d) {
                        d.branch_id = $('#branchFilter').length ? $('#branchFilter').val() : '';
                        d.date_from = $('#dateFrom').val();
                        d.date_to = $('#dateTo').val();
                        d.action_type = $('#actionType').val();
                    }
                },
                columns: [
                    { data: 'row_num', orderable: false },
                    { data: 'created_at' },
                    { data: 'action_type' },
                    { data: 'description' },
                    { data: 'name' }
                ]
            });

            // Apply filters
            $('#applyFilters').on('click', function () {
                table.ajax.reload();
            });

            // Reset filters
            $('#resetFilters').on('click', function () {
                $('#branchFilter').val('all');
                $('#dateFrom').val('');
                $('#dateTo').val('');
                $('#actionType').val('');
                table.ajax.reload();
            });

            // Refresh button
            $('#refreshLogs').on('click', function () {
                table.ajax.reload(null, false);
            });

        });
    </script>



    <?php include '../views/footer.php'; ?>
</body>

</html>