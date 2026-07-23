<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../courses.php");
    exit();
}

$courseName  = trim($_POST['course_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$teacherName = trim($_POST['teacher_name'] ?? '');
$courseId    = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;

if ($courseName === '' || $description === '' || $teacherName === '') {
    header("Location: ../courses.php");
    exit();
}

if ($courseId > 0) {
    // ---------- Update existing course ----------
    $stmt = mysqli_prepare($conn, "UPDATE courses SET course_name = ?, description = ?, teacher_name = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $courseName, $description, $teacherName, $courseId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../courses.php?updated=1");
} else {
    // ---------- Insert new course ----------
    $stmt = mysqli_prepare($conn, "INSERT INTO courses (course_name, description, teacher_name) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $courseName, $description, $teacherName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../courses.php?added=1");
}
exit();
