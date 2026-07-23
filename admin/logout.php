<?php
session_start();

// Only clear admin session keys — if a student session somehow exists
// in the same browser, don't touch it.
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_role']);

header("Location: login.php");
exit();
