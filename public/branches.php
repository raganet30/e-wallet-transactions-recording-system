<!DOCTYPE html>
<html lang="en">

<body>
    <?php include '../views/sidebar.php'; ?>

    <!-- Main Content Area -->
    <?php include '../views/header.php'; ?>

    <div class="content" id="mainContent">
        <h1 class="mb-4">Branches</h1>

        <!-- Add Branch Button -->
        <div class="mb-3 text-end">
           
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Branch
            </button>
        </div>


        <!-- Branches Table -->
        <div class="card">
            <div class="card-body table-responsive">
                <table id="branchesTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Branch Name</th>
                            <th>Address</th>
                            <th>Current COH</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Main Branch</td>
                            <td>Calbayog City</td>
                            <td>₱10,000.00</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2025-12-05</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-branch" data-id="1" data-name="Main Branch"
                                    data-status="Active">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-branch" data-id="1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2nd Branch</td>
                            <td>Catbalogan City</td>
                            <td>₱20,000.00</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2025-12-05</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-branch" data-id="1" data-name="Main Branch"
                                    data-status="Active">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-branch" data-id="1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <!-- Add Branch Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" required>
                    </div>
                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="branch_address" required>
                    </div>

                    <!-- Current COH -->
                      <div class="mb-3">
                        <label class="form-label">Current COH</label>
                        <input type="number" class="form-control" name="current_coh" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="branch_status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save Branch</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Branch Modal -->
    <div class="modal fade" id="editBranchModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="branch_id">

                    <div class="mb-3">
                        <label class="form-label">Branch Name</label>
                        <input type="text" class="form-control" name="edit_branch_name" required>
                    </div>
                     <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="edit_branch_address" required>
                    </div>

                    <!-- Current COH -->
                      <div class="mb-3">
                        <label class="form-label">Current COH</label>
                        <input type="number" class="form-control" name="edit_current_coh" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="edit_branch_status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Update Branch</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize DataTable
            let table = new DataTable("#branchesTable");

            // Edit button click
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("edit-branch")) {
                    let id = e.target.dataset.id;
                    let name = e.target.dataset.name;
                    let status = e.target.dataset.status;

                    document.querySelector("[name='branch_id']").value = id;
                    document.querySelector("[name='edit_branch_name']").value = name;
                    document.querySelector("[name='edit_branch_status']").value = status;

                    new bootstrap.Modal(document.getElementById("editBranchModal")).show();
                }
            });
        });
    </script>

    <script src="../assets/js/script.js"></script>
    <?php include '../views/footer.php'; ?>
</body>

</html>