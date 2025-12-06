<!DOCTYPE html>
<html lang="en">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">


<body>

    <div class="login-card">
        <h4 class="text-center mb-3"><i class="bi bi-shield-lock-fill text-primary"></i>Login</h4>

        <form action="dashboard" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <span><u>Forgot your password? Contact Admin.</u></span>
    
            <br>
            <br>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>

</body>

</html>