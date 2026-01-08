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
        <div id="globalAlertArea"></div>
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
                        <!-- Populate by AJAX -->
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
                        <label class="form-label">Status</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="branch_status" id="addStatusToggle"
                                checked>
                            <label class="form-check-label" for="addStatusToggle">Active</label>
                        </div>
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
                        <label class="form-label">Status</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="edit_branch_status"
                                id="editStatusToggle">
                            <label class="form-check-label" for="editStatusToggle">Active</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Update Branch</button>
                </div>
            </form>
        </div>
    </div>



    <?php include '../views/scripts.php'; ?>

    <script>
        // populate branches table
        $(document).ready(function () {
            $('#branchesTable').DataTable({
                processing: true,
                serverSide: false, // set to true if your fetch_branch.php supports server-side processing
                ajax: {
                    url: '../api/fetch_branches.php',
                    type: 'GET',
                    dataSrc: 'data' // your JSON key
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Auto numbering
                        }
                    },
                    { data: 'branch_name' },
                    { data: 'address' },
                    {
                        data: 'coh',
                        render: function (data) {
                            return '₱' + parseFloat(data).toLocaleString();
                        }
                    },
                    {
                        data: 'status',
                        render: function (data) {
                            let label = (data == 1) ? "Active" : "Inactive";
                            let badge = (data == 1) ? "bg-success" : "bg-danger";

                            return `<span class="badge ${badge}">${label}</span>`;
                        }
                    },
                    { data: 'date_created' },
                    {
                        data: null,
                        render: function (data) {
                            return `
                      <button class="btn btn-sm btn-warning edit-branch"
                                data-id="${data.id}"
                                data-name="${data.branch_name}"
                                data-address="${data.address}"
                                data-coh="${data.coh}"
                                data-status="${data.status}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    `;
                        }
                    }
                ]
            });

            // optional: click events
            $(document).on('click', '.edit-branch', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let status = $(this).data('status');
                console.log("Edit branch", id, name, status);
                // open modal here...
            });

            $(document).on('click', '.delete-branch', function () {
                let id = $(this).data('id');
                console.log("Delete branch", id);
                // delete confirmation here...
            });
        });


        // add new branch 
        // ADD BRANCH
        $("#addBranchModal form").on("submit", function (e) {
            e.preventDefault();

            // Convert toggle to text
            let isActive = $("#addStatusToggle").is(":checked") ? "Active" : "Inactive";
            let form = new FormData(this);
            form.set("branch_status", isActive);

            $.ajax({
                url: "../processes/add_branch.php",
                type: "POST",
                data: form,
                processData: false,
                contentType: false,

                success: function (response) {
                    if (response.success) {

                        $("#addBranchModal").modal("hide");
                        $("#addBranchModal form")[0].reset();

                        $('#branchesTable').DataTable().ajax.reload(null, false);

                        // Show global alert
                        let alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message || "Branch added successfully."}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                        $("#globalAlertArea").html(alertHtml);

                        setTimeout(() => $(".alert").alert('close'), 3000);

                    } else {
                        alert(response.message || "Failed to add branch.");
                    }
                },

                error: function () {
                    alert("An error occurred while saving the branch.");
                }
            });
        });


        // edit branch
        $(document).ready(function () {

            // OPEN EDIT MODAL + POPULATE FIELDS
            // OPEN EDIT MODAL
            $(document).on("click", ".edit-branch", function () {

                let id = $(this).data("id");
                let name = $(this).data("name");
                let address = $(this).data("address");
                let coh = $(this).data("coh");
                let status = $(this).data("status"); // 1 or 0

                $("#editBranchModal [name='branch_id']").val(id);
                $("#editBranchModal [name='edit_branch_name']").val(name);
                $("#editBranchModal [name='edit_branch_address']").val(address);
                $("#editBranchModal [name='edit_current_coh']").val(coh);

                // Set toggle
                if (status == 1) {
                    $("#editStatusToggle").prop("checked", true);
                } else {
                    $("#editStatusToggle").prop("checked", false);
                }

                $("#editBranchModal").modal("show");
            });

            // UPDATE BRANCH AJAX
            // UPDATE BRANCH
            $("#editBranchModal form").on("submit", function (e) {
                e.preventDefault();

                let statusText = $("#editStatusToggle").is(":checked") ? "Active" : "Inactive";

                let form = new FormData(this);
                form.set("edit_branch_status", statusText);

                $.ajax({
                    url: "../processes/edit_branch.php",
                    type: "POST",
                    data: form,
                    processData: false,
                    contentType: false,

                    success: function (response) {
                        if (response.success) {

                            $("#editBranchModal").modal("hide");

                            $('#branchesTable').DataTable().ajax.reload(null, false);

                            // Global alert
                            let alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message || "Branch updated successfully."}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                            $("#globalAlertArea").html(alertHtml);

                            setTimeout(() => $(".alert").alert('close'), 3000);

                        } else {
                            alert(response.message || "Failed to update branch.");
                        }
                    },

                    error: function () {
                        alert("An error occurred during the update.");
                    }
                });
            });


        });

    </script>


    <?php include '../views/footer.php'; ?>
</body>

</html>