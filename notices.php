<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "Notice Board";

$notices = [];
$result = mysqli_query($conn, "SELECT title, content, created_at FROM notices ORDER BY created_at DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notices[] = $row;
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
        <h3 class="fa-section-title">Notice Board</h3>

        <?php if (empty($notices)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 4.5C5 3.67 5.67 3 6.5 3H14L19 8V19.5C19 20.33 18.33 21 17.5 21H6.5C5.67 21 5 20.33 5 19.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3V8H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No notices posted yet</h5>
                <p style="font-size: 0.9rem;">Check back later for announcements from the Academy.</p>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <?php foreach ($notices as $notice): ?>
                    <div class="fa-notice-item">
                        <div class="fa-notice-date"><?php echo date("d M Y, h:i A", strtotime($notice['created_at'])); ?></div>
                        <h6><?php echo htmlspecialchars($notice['title']); ?></h6>
                        <p><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
