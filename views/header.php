
<nav class="navbar" id="navbar">
    <div class="container-fluid">
        <span class="navbar-brand">
            <i class="bi bi-wallet2 me-2"></i>E-Wallet Transaction Recording System
        </span>
       

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userMenu"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i>
                <span>
                    <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-gear me-2"></i>Edit Profile
                    </a>

                </li>
                <li>
                    <a class="dropdown-item text-danger" href="../processes/logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear me-2"></i>Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editProfileForm">
                <div class="modal-body">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <hr>
                   
                    <!-- Old Password -->
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                            
                    </div>

                    <!-- Password -->
                     
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password">
                        <small class="text-muted">Leave blank if you do not wish to change password.</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Confirm Save Modal -->
<div class="modal fade" id="confirmSaveProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-circle me-2"></i>Confirm Changes
                </h5>
            </div>

            <div class="modal-body">
                Are you sure you want to save these changes to your profile?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSaveProfileBtn">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>

<script src="../assets/js/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function () {

    const editModal = new bootstrap.Modal('#editProfileModal');
    const confirmModal = new bootstrap.Modal('#confirmSaveProfileModal');

    // ===============================
    // OPEN EDIT PROFILE MODAL
    // ===============================
    $('#editProfileModal').on('show.bs.modal', function () {
        $.getJSON('../api/fetch_profile.php', function (res) {
            if (!res.success) return;

            const f = $('#editProfileForm');
            f.find('[name="name"]').val(res.data.name);
            f.find('[name="username"]').val(res.data.username);
            f.find('[name="password"]').val('');
            f.find('[name="confirm_password"]').val('');
            f.find('[name="current_password"]').val('');

            // Remove previous alerts
            f.find('.alert').remove();
        });
    });

    // ===============================
    // FORM SUBMIT (SHOW CONFIRMATION)
    // ===============================
    $('#editProfileForm').on('submit', function (e) {
        e.preventDefault();

        const password = $('[name="password"]').val();
        const confirm = $('[name="confirm_password"]').val();
        const current = $('[name="current_password"]').val();
        const f = $(this);

        // Remove previous alerts
        f.find('.alert').remove();

        // If changing password, require current password
        if (password) {
            if (!current) {
                f.prepend('<div class="alert alert-danger">Current password is required.</div>');
                return;
            }

            const strongRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!strongRegex.test(password)) {
                f.prepend('<div class="alert alert-danger">Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.</div>');
                // hide after 5 seconds
                setTimeout(() => {
                    f.find('.alert').fadeOut(500, function() { $(this).remove(); });
                }, 5000);
                return;
            }

            if (password !== confirm) {
                f.prepend('<div class="alert alert-danger">Passwords do not match.</div>');
                // hide after 5 seconds
                setTimeout(() => {
                    f.find('.alert').fadeOut(500, function() { $(this).remove(); });
                }, 5000);
                return;
            }
        }

        confirmModal.show();
        editModal.hide();
    });

    // ===============================
    // CONFIRM SAVE
    // ===============================
    $('#confirmSaveProfileBtn').on('click', function () {
        const formData = $('#editProfileForm').serialize();

        $.post('../processes/edit_profile.php', formData, function (res) {

            // Always close confirm modal
            confirmModal.hide();

            const f = $('#editProfileForm');
            f.find('.alert').remove(); // clear previous alerts

            if (res.success) {
                f.prepend('<div class="alert alert-success">Profile updated successfully.</div>');
                // Optionally reset password fields
                f.find('[name="password"], [name="confirm_password"], [name="current_password"]').val('');
            } else {
                editModal.show(); // reopen edit modal for correction
                 f.prepend('<div class="alert alert-danger">' + (res.message || 'Failed to update profile.') + '</div>');
            }

        }, 'json');
    });

});

</script>
