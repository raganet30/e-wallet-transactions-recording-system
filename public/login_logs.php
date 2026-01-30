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
        <div class="container-fluid">
            <h1 class="mb-4">Login Logs </h1>

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
                                        <option value="login">Login</option>
                                        <option value="logout">Logout</option>
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
                                <i class="bi bi-list-check me-2"></i>Login Logs
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
                                        <!-- Sample Audit Log 1: Login -->
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                12-24-2025 08:00
                                            </td>
                                            <td>
                                                <span class="badge badge-login">Login</span>
                                            </td>
                                            <td>
                                                Login successful | IP: 001
                                            </td>

                                            <td>
                                                Admin
                                            </td>

                                        </tr>

                                        <!-- Sample Audit Log 2: Logout -->
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                12-24-2025 09:00
                                            </td>
                                            <td>
                                                <span class="badge badge-logout">Logout</span>
                                            </td>
                                            <td>
                                                Logout | IP: 001
                                            </td>

                                            <td>
                                                Admin
                                            </td>
                                        </tr>

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
                order: [[1, 'desc']], // sort by date
                ajax: {
                    url: '../api/fetch_login_logs.php',
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
                    {
                        data: 'created_at',
                        render: d => new Date(d)
                            .toLocaleString('en-US', { hour12: true })
                            .replace(',', '')
                    },
                    { data: 'login_type' },
                    { data: 'description' },
                    { data: 'name' }
                ]
            });

            $('#applyFilters').on('click', function () {
                table.ajax.reload();
            });

            $('#resetFilters').on('click', function () {
                $('#branchFilter').val('all');
                $('#dateFrom').val('');
                $('#dateTo').val('');
                $('#actionType').val('');
                table.ajax.reload();
            });

        });
    </script>


    <?php include '../views/footer.php'; ?>
</body>

</html>