<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['student_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Forces Academy LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- Top command bar -->
<header class="fa-topbar">
    <div class="fa-topbar-inner">
        <div class="fa-topbar-brand">
            <div class="fa-topbar-emblem">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L20 5.5V11C20 16 16.5 19.7 12 21C7.5 19.7 4 16 4 11V5.5L12 2Z" stroke="#C6A15B" stroke-width="1.4" stroke-linejoin="round"/>
                    <path d="M9 11.5L11 13.5L15.5 9" stroke="#C6A15B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="fa-topbar-title">
                Forces Academy
                <small>Student Dashboard</small>
            </div>
        </div>

        <a href="logout.php" class="btn fa-btn-logout">
            Logout
        </a>
    </div>
</header>

<div class="fa-dash-wrap">

    <!-- Cadet ID card -->
    <div class="fa-id-card">
        <div class="fa-id-left">
            <div class="fa-id-eyebrow">Welcome back</div>
            <div class="fa-id-name"><?php echo htmlspecialchars($name); ?></div>
            <div class="fa-id-status">You have successfully logged in to your Academy account.</div>
        </div>
        <div class="fa-id-tag">Status: Active Student</div>
    </div>

    <!-- Modules -->
    <h3 class="fa-section-title">Your Modules</h3>

    <div class="fa-module-grid">

        <div class="fa-module-card">
            <div class="fa-module-tab"></div>
            <div class="fa-module-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 5.5C4 4.67 4.67 4 5.5 4H12V20H5.5C4.67 20 4 19.33 4 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M20 5.5C20 4.67 19.33 4 18.5 4H12V20H18.5C19.33 20 20 19.33 20 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="fa-module-name">Courses</div>
            <div class="fa-module-desc">View enrolled courses, materials, and schedules.</div>
            <span class="fa-module-badge">Coming soon</span>
        </div>

        <div class="fa-module-card">
            <div class="fa-module-tab"></div>
            <div class="fa-module-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 4.5C5 3.67 5.67 3 6.5 3H14L19 8V19.5C19 20.33 18.33 21 17.5 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M14 3V8H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M8.5 13H15.5M8.5 16.5H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="fa-module-name">Notices</div>
            <div class="fa-module-desc">Official announcements and academic notices.</div>
            <span class="fa-module-badge">Coming soon</span>
        </div>

        <div class="fa-module-card">
            <div class="fa-module-tab"></div>
            <div class="fa-module-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 3H15V6H9V3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M5.5 6H18.5C19.33 6 20 6.67 20 7.5V19.5C20 20.33 19.33 21 18.5 21H5.5C4.67 21 4 20.33 4 19.5V7.5C4 6.67 4.67 6 5.5 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M8 12L11 15L16 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="fa-module-name">Assignments</div>
            <div class="fa-module-desc">Track submissions, deadlines, and grades.</div>
            <span class="fa-module-badge">Coming soon</span>
        </div>

        <div class="fa-module-card">
            <div class="fa-module-tab"></div>
            <div class="fa-module-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="8.5" r="3.5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5 20C5 16.5 8 14.5 12 14.5C16 14.5 19 16.5 19 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="fa-module-name">Profile</div>
            <div class="fa-module-desc">Manage your personal and enrollment details.</div>
            <span class="fa-module-badge">Coming soon</span>
        </div>

    </div>

</div>

</body>
</html>