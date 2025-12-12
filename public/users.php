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
            <div id="globalAlertArea"></div>

            <!-- Page Header with Add User Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Users</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle me-2"></i>Add User
                </button>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Branch</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    require '../config/db.php';

    // Fetch branches
    $branches = [];
    try {
        $stmt = $con->prepare("SELECT id, branch_name FROM branches ORDER BY branch_name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $branches[] = $row;
        }
    } catch (Throwable $e) {
        error_log('Error fetching branches: ' . $e->getMessage());
    }
    ?>
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert area for messages -->
                    <div id="addUserAlertArea"></div>

                    <form id="addUserForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <!-- Branch selector -->
                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select class="form-select" name="branch_id" required>
                                <option value="">Select Branch</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['branch_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addUserForm" class="btn btn-primary">Save User</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <!-- Branch selector -->
                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select class="form-select" name="edit_branch_id" required>
                                <option value="">Select Branch</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['branch_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" id="editRole" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="edit_password"
                                placeholder="Leave blank to keep current">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password"
                                id="edit_confirm_password" placeholder="Leave blank to keep current">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editUserForm" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate/Deactivate Confirmation Modal -->
    <div class="modal fade" id="toggleStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="statusMessage">Are you sure you want to change this user's status?</p>
                    <input type="hidden" id="toggleUserId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmToggleStatus">Save</button>
                </div>
            </div>
        </div>
    </div>
    <?php include '../views/footer.php'; ?>
    <?php include '../views/scripts.php'; ?>

    <script>
        // fetch and populate users table
        $(document).ready(function () {
            $('#usersTable').DataTable({
                "processing": true,
                "ajax": {
                    "url": "../api/get_users.php",
                    "type": "POST"
                }
            });
        });



        // Add Users
        $("#addUserForm").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "../processes/add_users.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {

                    // Build alert box
                    let alertBox = `
                <div class="alert alert-${response.status === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                    // Close modal first, then show alert
                    $("#addUserModal").modal("hide");

                    // If success → reset form + reload table
                    if (response.status === "success") {
                        $("#addUserForm")[0].reset();
                        $("#usersTable").DataTable().ajax.reload();
                    }

                    // Show alert after modal closes
                    setTimeout(() => {
                        $("#globalAlertArea").html(alertBox);

                        // Auto-dismiss alert
                        setTimeout(() => {
                            $(".alert").alert('close');
                        }, 3000);
                    }, 300);
                }
            });
        });


        // edit user
        $(document).ready(function () {
            // Open Edit User Modal and populate data
            $('#usersTable').on('click', '.edit-btn', function () {
                let userId = $(this).data('id');

                $.ajax({
                    url: '../api/select_user.php',
                    type: 'GET',
                    data: { id: userId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            let user = response.data;
                            $('#edit_user_id').val(user.id);
                            $('#editName').val(user.name);
                            $('#edit_username').val(user.username);
                            $('#editRole').val(user.role.trim().toLowerCase());
                            $('select[name="edit_branch_id"]').val(user.branch_id);
                            $('#edit_password, #edit_confirm_password').val('');
                            $('#editUserModal').modal('show');


                        } else {
                            alert(response.message);

                        }
                    }
                });
            });

            // Handle Edit Form Submission
            $('#editUserForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '../processes/edit_user.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        let alertBox = `
                    <div class="alert alert-${response.status === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                        $('#editUserModal').modal('hide');

                        // Show alert after modal closes
                        setTimeout(() => {
                            $("#globalAlertArea").html(alertBox);

                            // Auto-dismiss alert
                            setTimeout(() => {
                                $(".alert").alert('close');
                            }, 3000);
                        }, 300);

                        if (response.status === 'success') {
                            $('#usersTable').DataTable().ajax.reload();

                        }
                    }
                });
            });
        });


        // activate/deactivate user
        $(document).ready(function () {
            // Open toggle status modal
            $('#usersTable').on('click', '.toggle-status-btn', function () {
                let userId = $(this).data('id');
                let isActive = $(this).find('i').hasClass('bi-toggle-on'); // true if currently active

                // Set hidden input and message
                $('#toggleUserId').val(userId);
                $('#statusMessage').text(
                    isActive
                        ? "Are you sure you want to deactivate this user?"
                        : "Are you sure you want to activate this user?"
                );

                // Update modal button color (optional)
                $('#confirmToggleStatus')
                    .removeClass('btn-warning btn-success')
                    .addClass(isActive ? 'btn-warning' : 'btn-success')
                    .text(isActive ? 'Deactivate' : 'Activate');

                // Show modal
                $('#toggleStatusModal').modal('show');
            });

            // Confirm status change
            $('#confirmToggleStatus').on('click', function () {
                let userId = $('#toggleUserId').val();

                $.ajax({
                    url: '../processes/toggle_user_status.php',
                    type: 'POST',
                    data: { id: userId },
                    dataType: 'json',
                    success: function (response) {
                        $('#toggleStatusModal').modal('hide'); // close modal first

                        let alertBox = '';

                        if (response.success) {
                            alertBox = `
                                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                            User status updated successfully!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    `;

                            // Show alert after modal is fully closed
                            setTimeout(() => {
                                $("#globalAlertArea").html(alertBox);
                                 $("#usersTable").DataTable().ajax.reload();
                                // Auto-dismiss
                                setTimeout(() => {
                                    $(".alert").alert('close');
                                }, 3000);
                            }, 300);

                        } else {
                            alertBox = `
                                        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                            ${response.message || 'Failed to update status.'}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    `;

                            setTimeout(() => {
                                $("#globalAlertArea").html(alertBox);

                                setTimeout(() => {
                                    $(".alert").alert('close');
                                }, 3000);
                            }, 300);
                        }
                    },

                    error: function () {
                        $('#toggleStatusModal').modal('hide');

                        let alertBox = `
                                        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                            An error occurred while updating status.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    `;

                        setTimeout(() => {
                            $("#globalAlertArea").html(alertBox);

                            setTimeout(() => {
                                $(".alert").alert('close');
                            }, 3000);
                        }, 300);
                    }

                });
            });
        });


    </script>

</body>

</html>