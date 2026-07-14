<?php
// ============================================
// AUTH CHECK — include this at the very top of every
// protected page (dashboard, courses, notices, etc.)
// Must run BEFORE any HTML output.
// ============================================
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$studentName = $_SESSION['student_name'];
$studentId   = $_SESSION['student_id'];
