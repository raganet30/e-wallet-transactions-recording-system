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
            <h1 class="mb-4">Cash Logs </h1>

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
                                    <label for="actionType" class="form-label">Type</label>
                                    <select class="form-select" id="type">
                                        <option value="">All Types</option>
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
                                <i class="bi bi-list-check me-2"></i>Cash Logs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="cashLogsTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date & Time</th>
                                            <th>Transaction Type.</th>
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
    <?php include '../views/scripts.php'; ?>
    <script>
        $(document).ready(function () {

            // ===============================
            // DATATABLE INITIALIZATION
            // ===============================
            const table = $('#cashLogsTable').DataTable({
                processing: true,
                serverSide: false, // client-side processing
                searching: true,
                ordering: true,
                paging: true,
                lengthChange: true,
                pageLength: 10,
                ajax: {
                    url: '../api/fetch_all_coh_logs.php',
                    data: function (d) {
                        const branchVal = $('#branchFilter').val();

                        d.branch_id = (branchVal && branchVal !== 'all') ? branchVal : '';
                        d.date_from = $('#dateFrom').val();
                        d.date_to = $('#dateTo').val();
                        d.type = $('#type').val();
                    },
                    dataSrc: function (json) {
                        if (!json.success) return [];
                        return json.data;
                    }
                },
                columns: [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    { data: 'created_at' },
                    {
                        data: 'type',
                        render: type => `<span class="badge bg-secondary">${type}</span>`
                    },
                    { data: 'previous_balance' },
                    { data: 'new_balance' },
                    { data: 'updated_by' },
                    {
                        data: 'note',
                        render: note => note || '-'
                    }
                ],
                order: [[1, 'desc']],
                language: {
                    emptyTable: 'No cash logs found',
                    search: 'Search logs:',
                    lengthMenu: 'Show _MENU_ entries'
                }
            });

            // ===============================
            // LOAD TYPE FILTER OPTIONS
            // ===============================
            function loadTypes() {
                $.getJSON('../api/fetch_all_coh_logs.php?get_types=1', function (res) {
                    if (!res.success) return;

                    const typeSelect = $('#type');
                    typeSelect.html('<option value="">All Types</option>');

                    res.types.forEach(type => {
                        typeSelect.append(`<option value="${type}">${type}</option>`);
                    });
                });
            }

            // ===============================
            // FILTER EVENTS
            // ===============================
            $('#applyFilters').on('click', function () {
                table.ajax.reload();
            });

            $('#resetFilters').on('click', function () {
                $('#dateFrom, #dateTo, #type').val('');
                $('#branchFilter').val('all');
                table.ajax.reload();
            });


            // Initial load
            loadTypes();

        });
    </script>



    <?php include '../views/footer.php'; ?>
</body>

</html>