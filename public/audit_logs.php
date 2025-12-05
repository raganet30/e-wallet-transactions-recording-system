<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

   <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Audit Logs</h1>

            <!-- Filters Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card filter-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-funnel me-2"></i>Filter Logs
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="dateFrom" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="dateFrom">
                                </div>
                                <div class="col-md-3">
                                    <label for="dateTo" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="dateTo">
                                </div>
                                <div class="col-md-3">
                                    <label for="actionType" class="form-label">Action Type</label>
                                    <select class="form-select" id="actionType">
                                        <option value="">All Types</option>
                                        <option value="login">Login</option>
                                        <option value="adjustment">Cash Adjustment</option>
                                        <option value="transaction">Transaction</option>
                                        <option value="wallet">Wallet</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100 me-2" id="applyFilters">
                                        <i class="bi bi-filter me-1"></i> Apply Filters
                                    </button>
                                    <button class="btn btn-outline-secondary" id="resetFilters" title="Reset Filters">
                                        <i class="bi bi-arrow-clockwise"></i>
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
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary" id="refreshLogs">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="exportLogs">
                                    <i class="bi bi-download me-1"></i> Export
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="auditLogsTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Action Type</th>
                                            <th>Description</th>
                                            <th>Date & Time</th>
                                            <th>User</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample Audit Log 1: Login -->
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <span class="badge badge-login">Login</span>
                                            </td>
                                            <td class="action-cell">
                                                <div class="d-flex align-items-center">
                                                    <div class="log-icon bg-primary bg-opacity-10">
                                                        <i class="bi bi-box-arrow-in-right text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">User login successful</div>
                                                        <small class="text-muted">IP: 192.168.1.100 • Browser: Chrome</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-order="2023-12-05 14:30:00">
                                                <div class="fw-medium">Dec 5, 2023</div>
                                                <small class="text-muted">2:30 PM</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary">
                                                            AU
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">Admin User</div>
                                                        <small class="text-muted">Administrator</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-log-btn" data-id="1">
                                                    <i class="bi bi-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Sample Audit Log 2: Cash Adjustment -->
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <span class="badge badge-adjustment">Cash Adjustment</span>
                                            </td>
                                            <td class="action-cell">
                                                <div class="d-flex align-items-center">
                                                    <div class="log-icon bg-success bg-opacity-10">
                                                        <i class="bi bi-cash-coin text-success"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">Cash on hand updated</div>
                                                        <small class="text-muted">From ₱14,000.00 to ₱15,250.00 (+₱1,250.00)</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-order="2023-12-05 14:45:00">
                                                <div class="fw-medium">Dec 5, 2023</div>
                                                <small class="text-muted">2:45 PM</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle bg-success bg-opacity-10 text-success">
                                                            AU
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">Admin User</div>
                                                        <small class="text-muted">Administrator</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-log-btn" data-id="2">
                                                    <i class="bi bi-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Sample Audit Log 3: Transaction -->
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <span class="badge badge-transaction">Transaction</span>
                                            </td>
                                            <td class="action-cell">
                                                <div class="d-flex align-items-center">
                                                    <div class="log-icon bg-info bg-opacity-10">
                                                        <i class="bi bi-arrow-left-right text-info"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">New transaction created</div>
                                                        <small class="text-muted">GCash • ₱1,500.00 • Ref: 0123456</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-order="2023-12-05 15:00:00">
                                                <div class="fw-medium">Dec 5, 2023</div>
                                                <small class="text-muted">3:00 PM</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle bg-info bg-opacity-10 text-info">
                                                            CU
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">Cashier User</div>
                                                        <small class="text-muted">Cashier</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-log-btn" data-id="3">
                                                    <i class="bi bi-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Sample Audit Log 4: Wallet -->
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <span class="badge badge-wallet">Wallet</span>
                                            </td>
                                            <td class="action-cell">
                                                <div class="d-flex align-items-center">
                                                    <div class="log-icon bg-purple bg-opacity-10" style="background-color: rgba(111, 66, 193, 0.1);">
                                                        <i class="bi bi-wallet2" style="color: #6f42c1;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">New wallet account added</div>
                                                        <small class="text-muted">Maya • 0985-987-6543 • Secondary Account</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-order="2023-12-04 11:20:00">
                                                <div class="fw-medium">Dec 4, 2023</div>
                                                <small class="text-muted">11:20 AM</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle" style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                                                            AU
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">Admin User</div>
                                                        <small class="text-muted">Administrator</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-log-btn" data-id="4">
                                                    <i class="bi bi-eye"></i> View Details
                                                </button>
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

    


    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>