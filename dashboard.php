<?php
require_once 'includes/auth.php';   // handles session check + redirect — must run before any output
require_once 'config/db.php';

$pageTitle = "Dashboard";

// ---------- Stat 1: Total Courses ----------
$totalCourses = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $totalCourses = $row['total'];
}

// ---------- Stat 2: Pending Assignments (placeholder — assignments table not built yet) ----------
$pendingAssignments = 0; // TODO: wire up once assignments table exists

// ---------- Stat 3: Latest Notice title ----------
$latestNoticeTitle = "No notices yet";
$result = mysqli_query($conn, "SELECT title FROM notices ORDER BY created_at DESC LIMIT 1");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $latestNoticeTitle = $row['title'];
}

// ---------- Recent Notices (last 3) ----------
$recentNotices = [];
$result = mysqli_query($conn, "SELECT title, content, created_at FROM notices ORDER BY created_at DESC LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recentNotices[] = $row;
    }
}
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="fa-shell">
    <?php include 'includes/sidebar.php'; ?>

    <main class="fa-main">

        <!-- Cadet ID card / welcome banner -->
        <div class="fa-id-card">
            <div class="fa-id-left">
                <div class="fa-id-eyebrow">Welcome back</div>
                <div class="fa-id-name">Hello, <?php echo htmlspecialchars($studentName); ?>!</div>
                <div class="fa-id-status">Here's what's happening across the Academy today.</div>
            </div>
            <div class="fa-id-tag">Status: Active Student</div>
        </div>

        <!-- Stats Row -->
        <div class="fa-stat-grid">
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
                <div class="fa-stat-value"><?php echo $pendingAssignments; ?></div>
                <div class="fa-stat-label">Pending Assignments</div>
            </div>

            <div class="fa-stat-card">
                <div class="fa-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 4.5C5 3.67 5.67 3 6.5 3H14L19 8V19.5C19 20.33 18.33 21 17.5 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3V8H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                </div>
                <div class="fa-stat-value" style="font-size:1rem; line-height:1.3;">
                    <?php echo htmlspecialchars($latestNoticeTitle); ?>
                </div>
                <div class="fa-stat-label">Latest Notice</div>
            </div>
        </div>

        <!-- Notices + Quick Links -->
        <div class="fa-content-grid">
            <div class="fa-panel">
                <h3 class="fa-section-title">Recent Notices</h3>

                <?php if (empty($recentNotices)): ?>
                    <p style="color: var(--slate-600); font-size:0.9rem;">No notices posted yet. Check back soon.</p>
                <?php else: ?>
                    <?php foreach ($recentNotices as $notice): ?>
                        <div class="fa-notice-item">
                            <div class="fa-notice-date"><?php echo date("d M Y", strtotime($notice['created_at'])); ?></div>
                            <h6><?php echo htmlspecialchars($notice['title']); ?></h6>
                            <p><?php echo htmlspecialchars(mb_strimwidth($notice['content'], 0, 120, '...')); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="fa-panel">
                <h3 class="fa-section-title">Quick Links</h3>
                <div class="d-grid gap-2">
                    <a href="courses.php" class="btn fa-btn-primary">My Courses</a>
                    <a href="assignments.php" class="btn fa-btn-outline">Assignments</a>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>
