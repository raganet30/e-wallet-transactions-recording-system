
<nav class="navbar" id="navbar">
    <div id="globalAlertArea"></div>
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

    let openedFromError = false;

    const editModalEl = document.getElementById('editProfileModal');
    const confirmModalEl = document.getElementById('confirmSaveProfileModal');

    const editModal = new bootstrap.Modal(editModalEl);
    const confirmModal = new bootstrap.Modal(confirmModalEl);

    const form = $('#editProfileForm');

    function showFormAlert(type, message) {
        form.find('.alert').remove();
        const alert = $(`<div class="alert alert-${type}">${message}</div>`);
        form.prepend(alert);

        setTimeout(() => {
            alert.fadeOut(400, () => alert.remove());
        }, 5000);
    }

    function showGlobalAlert(type, message) {
        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show mt-2">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        $('#globalAlertArea').html(alert);

        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }

    // ===============================
    // LOAD PROFILE DATA
    // ===============================
    $('#editProfileModal').on('show.bs.modal', function () {

    const f = $('#editProfileForm');

    //  DO NOT RESET if reopening due to error
    if (openedFromError) {
        openedFromError = false;
        return;
    }

    $.getJSON('../api/fetch_profile.php', function (res) {
        if (!res.success) return;

        f.find('[name="name"]').val(res.data.name);
        f.find('[name="username"]').val(res.data.username);
        f.find('[name="password"]').val('');
        f.find('[name="confirm_password"]').val('');
        f.find('[name="current_password"]').val('');
        f.find('.alert').remove();
    });
});

    // ===============================
    // FORM SUBMIT → VALIDATION
    // ===============================
    form.on('submit', function (e) {
        e.preventDefault();

        const current = form.find('[name="current_password"]').val().trim();
        const password = form.find('[name="password"]').val();
        const confirm = form.find('[name="confirm_password"]').val();

        form.find('.alert').remove();

        // Current password ALWAYS required
        if (!current) {
            showFormAlert('danger', 'Current password is required to save changes.');
            return;
        }

        if (password !== '') {

            // Prevent same password reuse
            if (password === current) {
                showFormAlert('danger', 'New password cannot be the same as current password.');
                return;
            }

            // Strong password validation
            const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!strongRegex.test(password)) {
                showFormAlert(
                    'danger',
                    'Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.'
                );
                return;
            }

            if (password !== confirm) {
                showFormAlert('danger', 'New password and confirmation do not match.');
                return;
            }
        }

        // Passed validation → confirm
        editModal.hide();
        confirmModal.show();
    });

    // ===============================
    // CONFIRM SAVE
    // ===============================
    $('#confirmSaveProfileBtn').on('click', function () {
    const formData = $('#editProfileForm').serialize();

    $.post('../processes/edit_profile.php', formData, function (res) {

        confirmModal.hide();

        if (res.success) {
            editModal.hide();
            showGlobalAlert('success', 'Profile updated successfully.');
        } else {
            openedFromError = true; //  KEY FIX
            editModal.show();

            $('#editProfileForm')
                .prepend(`<div class="alert alert-danger">${res.message}</div>`);
        }

    }, 'json');
});


});
</script>

