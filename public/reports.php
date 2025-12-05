<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Transaction Reports</h1>


            <!-- Filters Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card report-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-filter me-2"></i>Report Filters
                            </h5>

                            <div class="row g-3 align-items-end">

                                <!-- Report Type -->
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Report Type</label>
                                    <select class="form-select" id="reportType">
                                        <option value="daily" selected>Daily Report</option>
                                        <option value="monthly">Monthly Report</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>

                                <!-- Date / Month / Custom Range -->
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Date / Range</label>

                                    <!-- Daily -->
                                    <input type="date" class="form-control" id="reportDate"
                                        value="<?php echo date('Y-m-d'); ?>">

                                    <!-- Monthly -->
                                    <input type="month" class="form-control mt-2 d-none" id="reportMonth"
                                        value="<?php echo date('Y-m'); ?>">

                                    <!-- Custom Range -->
                                    <div id="customRangeContainer" class="d-none mt-2">
                                        <input type="date" class="form-control mb-2" id="dateFrom">
                                        <input type="date" class="form-control" id="dateTo">
                                    </div>
                                </div>

                                <!-- Transaction Type -->
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Transaction Type</label>
                                    <select class="form-select" id="transactionType">
                                        <option value="" selected>All Types</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>

                                <!-- Fee Method -->
                                <div class="col-md-2">
                                    <label for="feeThru" class="form-label fw-medium">Fee Collection</label>
                                    <select class="form-select" id="feeThru">
                                        <option value="">All Methods</option>
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                    </select>
                                </div>

                                <!-- Apply + Reset Buttons -->
                                <div class="col-md-2 d-flex gap-2 align-items-end">
                                    <button class="btn btn-primary w-100" id="applyFilters">
                                        <i class="bi bi-filter-circle me-1"></i> Apply
                                    </button>

                                    <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </button>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Reports Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-table me-2"></i>Transaction Report
                            </h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" id="printReport">
                                    <i class="bi bi-printer me-1"></i> Print
                                </button>
                                <button class="btn btn-sm btn-outline-success" id="exportPDF">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="exportExcel">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="reportsTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Reference No.</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Transaction Fee</th>
                                            <th>Total</th>
                                            <th>Fee Thru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample Data 1 -->
                                        <tr>
                                            <td>1</td>
                                            <td data-order="2023-12-05 09:15">Dec 5, 2023<br><small>09:15 AM</small>
                                            </td>
                                            <td>REF-00123</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="2500.00" class="dt-body-right">₱2,500.00</td>
                                            <td data-order="25.00" class="dt-body-right">₱25.00</td>
                                            <td data-order="2525.00" class="dt-body-right">₱2,525.00</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                        </tr>
                                        <!-- Sample Data 2 -->
                                        <tr>
                                            <td>2</td>
                                            <td data-order="2023-12-05 10:30">Dec 5, 2023<br><small>10:30 AM</small>
                                            </td>
                                            <td>REF-00124</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                            <td data-order="1500.00" class="dt-body-right">₱1,500.00</td>
                                            <td data-order="15.00" class="dt-body-right">₱15.00</td>
                                            <td data-order="1515.00" class="dt-body-right">₱1,515.00</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                        </tr>
                                        <!-- Sample Data 3 -->
                                        <tr>
                                            <td>3</td>
                                            <td data-order="2023-12-05 11:45">Dec 5, 2023<br><small>11:45 AM</small>
                                            </td>
                                            <td>REF-00125</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="5000.00" class="dt-body-right">₱5,000.00</td>
                                            <td data-order="50.00" class="dt-body-right">₱50.00</td>
                                            <td data-order="5050.00" class="dt-body-right">₱5,050.00</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                        </tr>
                                        <!-- Sample Data 4 -->
                                        <tr>
                                            <td>4</td>
                                            <td data-order="2023-12-05 13:20">Dec 5, 2023<br><small>01:20 PM</small>
                                            </td>
                                            <td>REF-00126</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="1200.00" class="dt-body-right">₱1,200.00</td>
                                            <td data-order="12.00" class="dt-body-right">₱12.00</td>
                                            <td data-order="1212.00" class="dt-body-right">₱1,212.00</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                        </tr>
                                        <!-- Sample Data 5 -->
                                        <tr>
                                            <td>5</td>
                                            <td data-order="2023-12-05 14:45">Dec 5, 2023<br><small>02:45 PM</small>
                                            </td>
                                            <td>REF-00127</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                            <td data-order="3000.00" class="dt-body-right">₱3,000.00</td>
                                            <td data-order="30.00" class="dt-body-right">₱30.00</td>
                                            <td data-order="3030.00" class="dt-body-right">₱3,030.00</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                        </tr>
                                        <!-- Sample Data 6 -->
                                        <tr>
                                            <td>6</td>
                                            <td data-order="2023-12-05 15:30">Dec 5, 2023<br><small>03:30 PM</small>
                                            </td>
                                            <td>REF-00128</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="800.00" class="dt-body-right">₱800.00</td>
                                            <td data-order="8.00" class="dt-body-right">₱8.00</td>
                                            <td data-order="808.00" class="dt-body-right">₱808.00</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                        </tr>
                                        <!-- Sample Data 7 -->
                                        <tr>
                                            <td>7</td>
                                            <td data-order="2023-12-05 16:15">Dec 5, 2023<br><small>04:15 PM</small>
                                            </td>
                                            <td>REF-00129</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                            <td data-order="4500.00" class="dt-body-right">₱4,500.00</td>
                                            <td data-order="45.00" class="dt-body-right">₱45.00</td>
                                            <td data-order="4545.00" class="dt-body-right">₱4,545.00</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                        </tr>
                                        <!-- Sample Data 8 -->
                                        <tr>
                                            <td>8</td>
                                            <td data-order="2023-12-04 10:00">Dec 4, 2023<br><small>10:00 AM</small>
                                            </td>
                                            <td>REF-00120</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="2200.00" class="dt-body-right">₱2,200.00</td>
                                            <td data-order="22.00" class="dt-body-right">₱22.00</td>
                                            <td data-order="2222.00" class="dt-body-right">₱2,222.00</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                        </tr>
                                        <!-- Sample Data 9 -->
                                        <tr>
                                            <td>9</td>
                                            <td data-order="2023-12-04 11:30">Dec 4, 2023<br><small>11:30 AM</small>
                                            </td>
                                            <td>REF-00121</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                            <td data-order="1750.00" class="dt-body-right">₱1,750.00</td>
                                            <td data-order="17.50" class="dt-body-right">₱17.50</td>
                                            <td data-order="1767.50" class="dt-body-right">₱1,767.50</td>
                                            <td><span class="badge badge-maya">Maya</span></td>
                                        </tr>
                                        <!-- Sample Data 10 -->
                                        <tr>
                                            <td>10</td>
                                            <td data-order="2023-12-04 14:00">Dec 4, 2023<br><small>02:00 PM</small>
                                            </td>
                                            <td>REF-00122</td>
                                            <td><span class="badge badge-gcash">GCash</span></td>
                                            <td data-order="3500.00" class="dt-body-right">₱3,500.00</td>
                                            <td data-order="35.00" class="dt-body-right">₱35.00</td>
                                            <td data-order="3535.00" class="dt-body-right">₱3,535.00</td>
                                            <td><span class="badge badge-cash">Cash</span></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total:</th>
                                            <th class="dt-body-right"></th>
                                            <th class="dt-body-right"></th>
                                            <th class="dt-body-right"></th>
                                            <th></th>
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

    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>