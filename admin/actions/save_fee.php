<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../fees.php");
    exit();
}

$studentId   = (int) ($_POST['student_id'] ?? 0);
$amount      = trim($_POST['amount'] ?? '');
$dueDate     = trim($_POST['due_date'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($studentId <= 0 || $amount === '' || $dueDate === '' || $description === '') {
    header("Location: ../fees.php");
    exit();
}

// New fee records always start as 'pending' — status only changes via Mark Paid,
// or is shown as overdue on the display side once the due date passes.
$stmt = mysqli_prepare($conn, "INSERT INTO fees (student_id, amount, due_date, description, status) VALUES (?, ?, ?, ?, 'pending')");
mysqli_stmt_bind_param($stmt, "idss", $studentId, $amount, $dueDate, $description);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../fees.php?added=1");
exit();
