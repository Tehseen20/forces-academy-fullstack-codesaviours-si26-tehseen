<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../notices.php");
    exit();
}

$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($title === '' || $content === '') {
    header("Location: ../notices.php");
    exit();
}

// Your notices table also has a posted_by column — fill it with the admin's name.
$stmt = mysqli_prepare($conn, "INSERT INTO notices (title, content, posted_by) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $title, $content, $adminName);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../notices.php?posted=1");
exit();
