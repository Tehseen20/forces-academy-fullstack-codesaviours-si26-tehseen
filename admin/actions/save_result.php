<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../results.php");
    exit();
}

$studentId  = (int) ($_POST['student_id'] ?? 0);
$courseId   = (int) ($_POST['course_id'] ?? 0);
$subject    = trim($_POST['subject'] ?? '');
$marks      = (int) ($_POST['marks'] ?? 0);
$totalMarks = (int) ($_POST['total_marks'] ?? 0);
$grade      = trim($_POST['grade'] ?? '');
$examType   = trim($_POST['exam_type'] ?? '');

if ($studentId <= 0 || $courseId <= 0 || $subject === '' || $totalMarks <= 0 || $grade === '' || $examType === '') {
    header("Location: ../results.php");
    exit();
}

$stmt = mysqli_prepare($conn, "INSERT INTO results (student_id, course_id, subject, marks, total_marks, grade, exam_type)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iisiiss", $studentId, $courseId, $subject, $marks, $totalMarks, $grade, $examType);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../results.php?uploaded=1");
exit();
