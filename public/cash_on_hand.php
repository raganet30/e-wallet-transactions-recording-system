<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Cash On Hand</h1>

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
                                ₱15,250.00
                            </div>

                            <div class="cash-info mb-4">
                                <div class="row">
                                    <div class="col-6 text-start">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <div class="fw-medium" id="lastUpdatedDate">December 5, 2023</div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Updated By</small>
                                        <div class="fw-medium" id="updatedBy">Admin User</div>
                                    </div>
                                </div>
                                <div class="mt-2 text-muted">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        <span id="lastUpdatedTime">2:45 PM</span>
                                    </small>
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
                        <!-- <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="text-muted mb-2">Today's Change</div>
                                    <div class="h4 text-success">+₱1,250.00</div>
                                    <small class="text-muted">From yesterday</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="text-muted mb-2">Weekly Average</div>
                                    <div class="h4 text-info">₱14,500.00</div>
                                    <small class="text-muted">Last 7 days</small>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-clock-history me-2"></i>Recent Updates
                                    </h6>
                                    <div class="list-group list-group-flush" id="updateHistory">
                                        <div class="list-group-item border-0 px-0 py-2">
                                            <div class="d-flex w-100 justify-content-between">
                                                <small>Adjusted to ₱15,250.00</small>
                                                <small class="text-muted">Today, 2:45 PM</small>
                                            </div>
                                        </div>
                                        <div class="list-group-item border-0 px-0 py-2">
                                            <div class="d-flex w-100 justify-content-between">
                                                <small>Adjusted to ₱14,000.00</small>
                                                <small class="text-muted">Dec 4, 10:30 AM</small>
                                            </div>
                                        </div>
                                        <div class="list-group-item border-0 px-0 py-2">
                                            <div class="d-flex w-100 justify-content-between">
                                                <small>Cash deposit ₱2,500.00</small>
                                                <small class="text-muted">Dec 3, 3:15 PM</small>
                                            </div>
                                        </div>
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
                                            <th>Previous Balance</th>
                                            <th>New Balance</th>
                                            <th>Updated By</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dec 5, 2023 2:45 PM</td>
                                            <td>Cash On Hand Ajustment</td>
                                            <td>₱14,000.00</td>
                                            <td>₱15,250.00</td>
                                            <td>Admin User</td>
                                            <td>Cash adjustment for reconciliation</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Dec 4, 2023 10:30 AM</td>
                                            <td>Cash On Hand Ajustment</td>
                                            <td>₱13,500.00</td>
                                            <td>₱14,000.00</td>
                                            <td>Admin User</td>
                                            <td>Daily cash count adjustment</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Dec 3, 2023 3:15 PM</td>
                                            <td>Cash Deposit</td>
                                            <td>₱11,000.00</td>
                                            <td>₱13,500.00</td>
                                            <td>Admin User</td>
                                            <td>Added cash from sales</td>
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
                            <div class="h3 text-primary" id="currentAmountDisplay">₱15,250.00</div>
                        </div>

                        <div class="mb-3">
                            <label for="newAmount" class="form-label">New Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control form-control-lg" id="newAmount"
                                    placeholder="Enter new amount" step="0.01" min="0" required>
                            </div>
                            <div class="form-text">
                                Enter the exact physical cash count
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="changeReason" class="form-label">Reason for Change</label>
                            <select class="form-select" id="changeReason" required>
                                <option value="">Select reason</option>
                                <option value="daily_adjustment">Daily Adjustment</option>
                                <option value="cash_deposit">Cash Deposit</option>
                                <option value="cash_withdrawal">Cash Withdrawal</option>
                                <option value="error_correction">Error Correction</option>
                                <option value="petty_cash">Petty Cash Replenishment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" rows="2"
                                placeholder="Add any additional notes..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="updateDate" class="form-label">Update Date & Time</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" id="updateDate"
                                    value="<?php echo date('Y-m-d\TH:i'); ?>">
                            </div>
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


    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
    <script>
        $(document).ready(function () {
            // Set current date and time
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                const formattedDate = now.toLocaleDateString('en-US', options);
                $('#lastUpdatedDate').text(formattedDate.split(',')[0] + ',' + formattedDate.split(',')[1]);
                $('#lastUpdatedTime').text(now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
            }

            // Initialize with current date/time
            updateDateTime();

            // Edit Cash Button Handler
            $('#editCashBtn').on('click', function () {
                const currentAmount = $('#currentCashAmount').text().replace('₱', '').replace(/,/g, '');
                $('#currentAmountDisplay').text('₱' + parseFloat(currentAmount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#newAmount').val(currentAmount);
                $('#editCashModal').modal('show');
            });

            // Save Cash Handler
            $('#saveCashBtn').on('click', function () {
                const newAmount = parseFloat($('#newAmount').val()).toFixed(2);
                const currentAmount = parseFloat($('#currentAmountDisplay').text().replace('₱', '').replace(/,/g, '')).toFixed(2);
                const reason = $('#changeReason').val();
                const remarks = $('#remarks').val();

                if (!newAmount || newAmount < 0) {
                    alert('Please enter a valid amount');
                    return;
                }

                if (!reason) {
                    alert('Please select a reason for the change');
                    return;
                }

                // Calculate change
                const change = parseFloat(newAmount) - parseFloat(currentAmount);

                // Update the display
                $('#currentCashAmount').text('₱' + parseFloat(newAmount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                // Update date and time
                updateDateTime();

                // Update history in the quick stats
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                const dateString = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });

                // Add to recent updates
                $('#updateHistory').prepend(`
                    <div class="list-group-item border-0 px-0 py-2">
                        <div class="d-flex w-100 justify-content-between">
                            <small>Adjusted to ₱${parseFloat(newAmount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</small>
                            <small class="text-muted">Today, ${timeString}</small>
                        </div>
                    </div>
                `);

                // Add to detailed history table
                $('#historyDetails table tbody').prepend(`
                    <tr>
                        <td>${dateString}, ${now.getFullYear()} ${timeString}</td>
                        <td>₱${parseFloat(currentAmount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                        <td>₱${parseFloat(newAmount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                        <td><span class="${change >= 0 ? 'text-success' : 'text-danger'}">${change >= 0 ? '+' : ''}₱${Math.abs(change).toLocaleString('en-US', { minimumFractionDigits: 2 })}</span></td>
                        <td>${$('#updatedBy').text()}</td>
                        <td>${remarks || reason.replace('_', ' ')}</td>
                    </tr>
                `);

                // Update today's change
                const todayChangeElement = $('.h4.text-success');
                todayChangeElement.text((change >= 0 ? '+' : '-') + '₱' + Math.abs(change).toLocaleString('en-US', { minimumFractionDigits: 2 }));
                if (change < 0) {
                    todayChangeElement.removeClass('text-success').addClass('text-danger');
                }

                // Close modal and reset form
                $('#editCashModal').modal('hide');
                $('#editCashForm')[0].reset();

                // Show success message
                alert('Cash on hand updated successfully!');
            });

            // Toggle History Details
            let historyExpanded = false;
            $('#toggleHistory').on('click', function () {
                if (historyExpanded) {
                    $('#historyDetails').slideUp();
                    $(this).html('<i class="bi bi-chevron-down"></i> Expand');
                    $(this).removeClass('btn-secondary').addClass('btn-outline-secondary');
                } else {
                    $('#historyDetails').slideDown();
                    $(this).html('<i class="bi bi-chevron-up"></i> Collapse');
                    $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
                }
                historyExpanded = !historyExpanded;
            });

            // Format currency input
            $('#newAmount').on('input', function () {
                let value = $(this).val();
                if (value && !isNaN(value)) {
                    $(this).val(parseFloat(value).toFixed(2));
                }
            });
        });
    </script>

</body>

</html>