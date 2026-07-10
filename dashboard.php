<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['student_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

<p>You have successfully logged in.</p>

<a href="logout.php" class="btn btn-danger">
    Logout
</a>

</body>
</html>