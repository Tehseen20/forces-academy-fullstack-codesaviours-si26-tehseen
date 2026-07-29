<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../assignments.php");
    exit();
}

$title        = trim($_POST['title'] ?? '');
$description  = trim($_POST['description'] ?? '');
$courseId     = (int) ($_POST['course_id'] ?? 0);
$dueDate      = trim($_POST['due_date'] ?? '');
$assignmentId = isset($_POST['assignment_id']) ? (int) $_POST['assignment_id'] : 0;

if ($title === '' || $description === '' || $courseId <= 0 || $dueDate === '') {
    header("Location: ../assignments.php");
    exit();
}

if ($assignmentId > 0) {
    // ---------- Update existing assignment ----------
    $stmt = mysqli_prepare($conn, "UPDATE assignments SET title = ?, description = ?, course_id = ?, due_date = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssisi", $title, $description, $courseId, $dueDate, $assignmentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../assignments.php?updated=1");
} else {
    // ---------- Insert new assignment ----------
    $stmt = mysqli_prepare($conn, "INSERT INTO assignments (title, description, course_id, due_date) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssis", $title, $description, $courseId, $dueDate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../assignments.php?added=1");
}
exit();
