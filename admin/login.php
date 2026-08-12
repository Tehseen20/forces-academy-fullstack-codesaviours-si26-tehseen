<?php
session_start();
require_once '../config/db.php';

$error = "";
$emailError = "";
$passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ---------- Email validation: must be a well-formed email (any domain allowed) ----------
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Please enter a valid email address.";
    }

    // ---------- Password validation: max 8 characters ----------
    if (strlen($password) > 10) {
        $passwordError = "Password cannot be more than 10 characters long.";
    }

    if ($emailError === "" && $passwordError === "") {

        $sql = "SELECT id, full_name, password FROM admins WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        if ($admin && password_verify($password, $admin['password'])) {

            // Admin session keys are completely separate from the student
            // session keys ($_SESSION['student_id']) so the two never collide.
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = 'admin';

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Forces Academy LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="fa-auth-wrap">
    <div class="fa-auth-card">

        <div class="fa-crest-panel">
            <div>
                <div class="fa-crest-emblem">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L20 5.5V11C20 16 16.5 19.7 12 21C7.5 19.7 4 16 4 11V5.5L12 2Z" stroke="#C6A15B" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M9 11.5L11 13.5L15.5 9" stroke="#C6A15B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="fa-brand-name">Forces<br>Academy</div>
                <div class="fa-brand-sub">Learning Management System</div>
                <p class="fa-motto">Restricted access. Administrator credentials required to manage academy records.</p>
            </div>
            <div class="fa-crest-foot">ADMIN CONTROL PANEL</div>
        </div>

        <div class="fa-form-panel">
            <h2 class="fa-form-heading">Admin Login</h2>
            <p class="fa-form-subheading">Enter your administrator credentials to continue.</p>

            <?php if (!empty($error)): ?>
                <div class="alert fa-alert fa-alert-danger mb-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="fa-field">
                    <label>Email</label>
                    <input type="email" id="adminLoginEmail" name="email" class="form-control fa-input" required>
                    <div class="fa-field-error" id="adminLoginEmailError"><?php echo htmlspecialchars($emailError); ?></div>
                </div>

                <div class="fa-field">
                    <label>Password</label>
                    <div class="fa-password-wrap">
                        <input type="password" id="adminLoginPassword" name="password" class="form-control fa-input"
                               autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                        <button type="button" class="fa-password-toggle" aria-label="Show password">
                            <svg class="fa-eye-closed" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                            <svg class="fa-eye-open" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10.6 5.2C11.06 5.07 11.53 5 12 5C18.5 5 22 12 22 12C21.6 12.8 20.6 14.3 19 15.6M6.5 6.5C4 8.1 2 12 2 12C2 12 5.5 19 12 19C13.9 19 15.5 18.5 16.8 17.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.9 10.1C9.5 10.5 9.3 11 9.3 11.5C9.3 12.6 10.2 13.5 11.3 13.5C11.9 13.5 12.4 13.2 12.8 12.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    <div class="fa-field-error" id="adminLoginPasswordError"><?php echo htmlspecialchars($passwordError); ?></div>
                </div>

                <button class="btn fa-btn-primary w-100 mt-2">Login</button>
            </form>

            <div class="fa-alt-action">
                <a href="../login.php">&larr; Back to Student Login</a>
            </div>
        </div>

    </div>
</div>

<script src="../js/main.js"></script>
<script>
(function () {
    var form = document.querySelector('.fa-form-panel form');
    var emailInput = document.getElementById('adminLoginEmail');
    var passwordInput = document.getElementById('adminLoginPassword');
    var emailError = document.getElementById('adminLoginEmailError');
    var passwordError = document.getElementById('adminLoginPasswordError');

    if (!form) return;

    function clearError(input, errorEl) {
        input.classList.remove('fa-input-invalid');
        errorEl.textContent = '';
    }

    function showError(input, errorEl, message) {
        input.classList.add('fa-input-invalid');
        errorEl.textContent = message;
    }

    emailInput.addEventListener('input', function () { clearError(emailInput, emailError); });
    passwordInput.addEventListener('input', function () { clearError(passwordInput, passwordError); });

    form.addEventListener('submit', function (e) {
        var valid = true;
        var emailVal = emailInput.value.trim();
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(emailVal)) {
            showError(emailInput, emailError, 'Please enter a valid email address.');
            valid = false;
        } else {
            clearError(emailInput, emailError);
        }

        if (passwordInput.value.length > 10) {
            showError(passwordInput, passwordError, 'Password cannot be more than 10 characters long.');
            valid = false;
        } else {
            clearError(passwordInput, passwordError);
        }

        if (!valid) {
            e.preventDefault();
        }
    });
})();
</script>
</body>
</html>
