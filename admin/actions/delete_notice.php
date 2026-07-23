<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: ../notices.php");
    exit();
}

$id = (int) $_POST['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM notices WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../notices.php?deleted=1");
exit();
