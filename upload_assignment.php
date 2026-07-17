<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: assignments.php");
    exit();
}

$assignmentId = isset($_POST['assignment_id']) ? (int) $_POST['assignment_id'] : 0;

if ($assignmentId <= 0 || !isset($_FILES['assignment_file'])) {
    header("Location: assignments.php?error=upload");
    exit();
}

// ---------- Prevent duplicate submissions ----------
$stmt = mysqli_prepare($conn, "SELECT id FROM submissions WHERE assignment_id = ? AND student_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $assignmentId, $studentId);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    header("Location: assignments.php?error=duplicate");
    exit();
}
mysqli_stmt_close($stmt);

$file = $_FILES['assignment_file'];

// ---------- Basic upload error check ----------
if ($file['error'] !== UPLOAD_ERR_OK) {
    header("Location: assignments.php?error=upload");
    exit();
}

// ---------- File size check (max 5MB) ----------
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    header("Location: assignments.php?error=filesize");
    exit();
}

// ---------- File type validation ----------
// Check both the extension AND the actual MIME type (extension alone can be spoofed)
$allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
$allowedMimeTypes = [
    'application/pdf',
    'image/jpeg',
    'image/png',
];

$originalName = $file['name'];
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
    header("Location: assignments.php?error=filetype");
    exit();
}

// ---------- Build a safe, unique filename ----------
$uniqueName = 'sub_' . $studentId . '_' . $assignmentId . '_' . uniqid() . '.' . $extension;
$uploadDir = __DIR__ . '/uploads/';
$destination = $uploadDir . $uniqueName;

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    header("Location: assignments.php?error=upload");
    exit();
}

// ---------- Save the record ----------
$relativePath = 'uploads/' . $uniqueName;
$stmt = mysqli_prepare($conn, "INSERT INTO submissions (assignment_id, student_id, file_path, status) VALUES (?, ?, ?, 'submitted')");
mysqli_stmt_bind_param($stmt, "iis", $assignmentId, $studentId, $relativePath);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: assignments.php?success=1");
    exit();
} else {
    mysqli_stmt_close($stmt);
    // Clean up the uploaded file since the DB record failed
    if (file_exists($destination)) {
        unlink($destination);
    }
    header("Location: assignments.php?error=upload");
    exit();
}
