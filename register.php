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
<html>
<head>
    <title>Student Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header text-center">
                    <h2>Student Registration</h2>
                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text"
                                   class="form-control"
                                   name="full_name"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password"
                                   class="form-control"
                                   name="confirm_password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Roll Number</label>
                            <input type="text"
                                   class="form-control"
                                   name="roll_number"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Class</label>
                            <input type="text"
                                   class="form-control"
                                   name="class"
                                   required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Register
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>