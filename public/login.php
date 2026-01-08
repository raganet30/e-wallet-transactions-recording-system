<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<?php include '../views/head.php'; ?>
<?php include '../processes/session_checker.php'; ?>

<!-- Bootstrap 5 CSS -->
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="icon" type="image/png" href="../assets/images/e-wallet.png">


<body>

    <div class="login-card">

        <h4 class="text-center mb-3">
            <i class="bi bi-shield-lock-fill text-primary"></i> Login
        </h4>

        <?php if (isset($_SESSION['expired'])): ?>
            <div class="alert alert-warning" id="loginAlert">
                <?= htmlspecialchars($_SESSION['expired']) ?>
            </div>
            <?php unset($_SESSION['expired']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" id="loginAlert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" id="loginAlert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>


        <form action="../processes/login_auth.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <span><u>Forgot your password? Contact Admin.</u></span>

            <br><br>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertBox = document.getElementById("loginAlert");
        if (!alertBox) return;

        setTimeout(() => {
            alertBox.style.transition = "opacity 0.5s";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 600);
        }, 3000);
    });
</script>


</html>