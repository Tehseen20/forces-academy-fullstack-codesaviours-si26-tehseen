<?php
require_once 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $roll_number = trim($_POST['roll_number']);
    $class = trim($_POST['class']);

    if (
        empty($full_name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($roll_number) ||
        empty($class)
    ) {
        $error = "All fields are required.";
    }

    elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    }

    else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO students
                (full_name, email, password, roll_number, class)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $full_name,
            $email,
            $hashed_password,
            $roll_number,
            $class
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $error = "Registration failed. Email or Roll Number may already exist.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration — Forces Academy LMS</title>

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
                <p class="fa-motto">Discipline in study. Precision in growth. Enroll to begin your record with the Academy.</p>
            </div>
            <div class="fa-crest-foot">STUDENT ENROLLMENT PORTAL</div>
        </div>

        <!-- Form panel -->
        <div class="fa-form-panel">
            <h2 class="fa-form-heading">Student Registration</h2>
            <p class="fa-form-subheading">Fill in your details to create your Academy record.</p>

            <?php if(!empty($error)): ?>
                <div class="alert fa-alert fa-alert-danger mb-3">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>

                <div class="fa-field">
                    <label>Full Name</label>
                    <input type="text"
                           class="form-control fa-input"
                           name="full_name"
                           placeholder="e.g. Ayesha Khan"
                           required>
                </div>

                <div class="fa-field">
                    <label>Email</label>
                    <input type="email"
                           class="form-control fa-input"
                           name="email"
                           placeholder="you@example.com"
                           required>
                </div>

                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Password</label>
                        <input type="password"
                               class="form-control fa-input"
                               name="password"
                               placeholder="••••••••"
                               required>
                    </div>

                    <div class="fa-field">
                        <label>Confirm Password</label>
                        <input type="password"
                               class="form-control fa-input"
                               name="confirm_password"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>

                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Roll Number</label>
                        <input type="text"
                               class="form-control fa-input"
                               name="roll_number"
                               placeholder="e.g. FA-2026-014"
                               required>
                    </div>

                    <div class="fa-field">
                        <label>Class</label>
                        <input type="text"
                               class="form-control fa-input"
                               name="class"
                               placeholder="e.g. BS-CS Final Year"
                               required>
                    </div>
                </div>

                <button class="btn fa-btn-primary w-100 mt-2">
                    Register
                </button>

            </form>

            <div class="fa-alt-action">
                Already enrolled? <a href="login.php">Sign in to your account</a>
            </div>

        </div>

    </div>
</div>

</body>
</html>