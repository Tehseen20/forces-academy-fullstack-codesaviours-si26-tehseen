<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "My Courses";

$courses = [];
$result = mysqli_query($conn, "SELECT course_name, description, teacher_name FROM courses ORDER BY course_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
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
        <h3 class="fa-section-title">My Courses</h3>

        <?php if (empty($courses)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5C4 4.67 4.67 4 5.5 4H12V20H5.5C4.67 20 4 19.33 4 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M20 5.5C20 4.67 19.33 4 18.5 4H12V20H18.5C19.33 20 20 19.33 20 18.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No courses assigned yet</h5>
                <p style="font-size: 0.9rem;">Once courses are added to the database, they'll appear here.</p>
            </div>
        <?php else: ?>
            <div class="fa-course-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="fa-course-card">
                        <div class="fa-course-card-top"><?php echo htmlspecialchars($course['course_name']); ?></div>
                        <div class="fa-course-card-body">
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="fa-course-teacher">Instructor: <?php echo htmlspecialchars($course['teacher_name']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
