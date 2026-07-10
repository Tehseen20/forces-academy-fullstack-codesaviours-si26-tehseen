<?php
session_start();
require_once 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, password
            FROM students
            WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $student = mysqli_fetch_assoc($result);

    if ($student && password_verify($password, $student['password'])) {

        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['full_name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login — Forces Academy LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="fa-auth-wrap">
    <div class="fa-auth-card">

        <!-- Crest / brand panel -->
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
                <p class="fa-motto">Sign in to access your courses, notices, and academic record.</p>
            </div>
            <div class="fa-crest-foot">STUDENT ACCESS PORTAL</div>
        </div>

        <!-- Form panel -->
        <div class="fa-form-panel">
            <h2 class="fa-form-heading">Student Login</h2>
            <p class="fa-form-subheading">Enter your credentials to continue.</p>

            <?php if(!empty($error)): ?>
                <div class="alert fa-alert fa-alert-danger mb-3">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['registered'])): ?>
                <div class="alert fa-alert fa-alert-success mb-3">
                    Registration successful! Please login.
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>

                <div class="fa-field">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="form-control fa-input"
                           placeholder="you@example.com"
                           required>
                </div>

                <div class="fa-field">
                    <label>Password</label>
                    <input type="password"
                           name="password"
                           class="form-control fa-input"
                           placeholder="••••••••"
                           required>
                </div>

                <button class="btn fa-btn-primary w-100 mt-2">
                    Login
                </button>

            </form>

            <div class="fa-alt-action">
                New to the Academy? <a href="register.php">Create an account</a>
            </div>

        </div>

    </div>
</div>

</body>
</html>