<?php
require_once '../includes/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../timetable.php");
    exit();
}

$class    = trim($_POST['class'] ?? '');
$day      = trim($_POST['day'] ?? '');
$timeSlot = trim($_POST['time_slot'] ?? '');
$subject  = trim($_POST['subject'] ?? '');
$teacher  = trim($_POST['teacher'] ?? '');

if ($class === '' || $day === '' || $timeSlot === '' || $subject === '' || $teacher === '') {
    header("Location: ../timetable.php?error=1");
    exit();
}

$stmt = mysqli_prepare($conn, "INSERT INTO timetable (class, day, time_slot, subject, teacher) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssss", $class, $day, $timeSlot, $subject, $teacher);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../timetable.php?added=1");
exit();
