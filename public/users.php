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
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Data Rows, replace with PHP loop -->
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>johndoe</td>
                                    <td>Admin</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>2025-12-06</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning toggle-status-btn" data-id="1">
                                            <i class="bi bi-toggle-on"></i> Deactivate
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>janesmith</td>
                                    <td>User</td>
                                    <td><span class="badge bg-danger">Inactive</span></td>
                                    <td>2025-11-20</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="2">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success toggle-status-btn" data-id="2">
                                            <i class="bi bi-toggle-off"></i> Activate
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
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
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="editUsername" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" id="editRole" required>
                                <option value="">Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="editPassword"
                                placeholder="Leave blank to keep current">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="editConfirmPassword"
                                placeholder="Leave blank to keep current">
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

    <script>
        // Edit User button click
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const row = this.closest("tr");
                const id = this.dataset.id;
                const name = row.children[1].innerText;
                const username = row.children[2].innerText;
                const role = row.children[3].innerText;

                document.getElementById("editUserId").value = id;
                document.getElementById("editName").value = name;
                document.getElementById("editUsername").value = username;
                document.getElementById("editRole").value = role;

                const editModal = new bootstrap.Modal(document.getElementById("editUserModal"));
                editModal.show();
            });
        });

        // Toggle Status button click
        document.querySelectorAll(".toggle-status-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const row = this.closest("tr");
                const id = this.dataset.id;
                const statusText = row.children[4].innerText;
                const action = statusText === "Active" ? "deactivate" : "activate";

                document.getElementById("toggleUserId").value = id;
                document.getElementById("statusMessage").innerText = `Are you sure you want to ${action} this user?`;

                const toggleModal = new bootstrap.Modal(document.getElementById("toggleStatusModal"));
                toggleModal.show();
            });
        });

        // Confirm status change
        document.getElementById("confirmToggleStatus").addEventListener("click", function () {
            const id = document.getElementById("toggleUserId").value;
            // Perform AJAX request to update status in DB
            console.log("Status change confirmed for user ID:", id);
            // Close modal after action
            bootstrap.Modal.getInstance(document.getElementById("toggleStatusModal")).hide();
        });

    </script>
    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>