<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Admin Dashboard";

function fa_count($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $table");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int) $row['total'];
    }
    return 0;
}

$totalStudents    = fa_count($conn, 'students');
$totalCourses     = fa_count($conn, 'courses');
$totalAssignments = fa_count($conn, 'assignments');
$totalNotices     = fa_count($conn, 'notices');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> — Forces Academy LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="fa-shell">
    <?php include 'includes/sidebar.php'; ?>

    <main class="fa-main">
        <div class="fa-id-card">
            <div class="fa-id-left">
                <div class="fa-id-eyebrow">Administrator</div>
                <div class="fa-id-name">Welcome, <?php echo htmlspecialchars($adminName); ?></div>
                <div class="fa-id-status">Academy-wide overview and management tools.</div>
            </div>
            <div class="fa-id-tag">Role: Administrator</div>
        </div>

        <div class="fa-stat-grid" style="grid-template-columns: repeat(4, 1fr);">
            <div class="fa-stat-card">
                <div class="fa-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="3.2" stroke="currentColor" stroke-width="1.5"/><path d="M5 20c0-3.6 3.1-6.5 7-6.5s7 2.9 7 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <div class="fa-stat-value"><?php echo $totalStudents; ?></div>
                <div class="fa-stat-label">Total Students</div>
            </div>
            <div class="fa-stat-card">
                <div class="fa-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5C4 4.67 4.67 4 5.5 4H12V20H5.5C4.67 20 4 19.33 4 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M20 5.5C20 4.67 19.33 4 18.5 4H12V20H18.5C19.33 20 20 19.33 20 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                </div>
                <div class="fa-stat-value"><?php echo $totalCourses; ?></div>
                <div class="fa-stat-label">Total Courses</div>
            </div>
            <div class="fa-stat-card">
                <div class="fa-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 6H18.5C19.33 6 20 6.67 20 7.5V19.5C20 20.33 19.33 21 18.5 21H5.5C4.67 21 4 20.33 4 19.5V7.5C4 6.67 4.67 6 5.5 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 12L11 15L16 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="fa-stat-value"><?php echo $totalAssignments; ?></div>
                <div class="fa-stat-label">Total Assignments</div>
            </div>
            <div class="fa-stat-card">
                <div class="fa-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 4.5C5 3.67 5.67 3 6.5 3H14L19 8V19.5C19 20.33 18.33 21 17.5 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3V8H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                </div>
                <div class="fa-stat-value"><?php echo $totalNotices; ?></div>
                <div class="fa-stat-label">Total Notices</div>
            </div>
        </div>

        <div class="fa-content-grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div class="fa-panel">
                <h3 class="fa-section-title">Quick Actions</h3>
                <div class="d-grid gap-2">
                    <a href="students.php" class="btn fa-btn-primary">Manage Students</a>
                    <a href="courses.php" class="btn fa-btn-outline">Manage Courses</a>
                </div>
            </div>
            <div class="fa-panel">
                <h3 class="fa-section-title">Content</h3>
                <div class="d-grid gap-2">
                    <a href="notices.php" class="btn fa-btn-primary">Post Notice</a>
                    <a href="results.php" class="btn fa-btn-outline">Upload Results</a>
                </div>
            </div>
            <div class="fa-panel">
                <h3 class="fa-section-title">Assignments</h3>
                <div class="d-grid gap-2">
                    <a href="assignments.php" class="btn fa-btn-outline">View Assignments</a>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>
