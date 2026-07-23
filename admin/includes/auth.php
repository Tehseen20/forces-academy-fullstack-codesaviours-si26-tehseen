<?php
// ============================================
// ADMIN AUTH CHECK — include at the top of every admin page
// (except admin/login.php itself). Must run before any output.
//
// Uses $_SESSION['admin_id'] and $_SESSION['admin_role'] — completely
// separate keys from the student session ($_SESSION['student_id']),
// so an admin and a student can even be logged in on the same browser
// in different tabs without clashing.
// ============================================
session_start();

if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminId   = $_SESSION['admin_id'];
$adminName = $_SESSION['admin_name'];
