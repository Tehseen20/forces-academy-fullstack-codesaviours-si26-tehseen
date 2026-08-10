<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: ../fees.php");
    exit();
}

$id = (int) $_POST['id'];

$stmt = mysqli_prepare($conn, "UPDATE fees SET status = 'paid', paid_date = CURDATE() WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../fees.php?paid=1");
exit();
