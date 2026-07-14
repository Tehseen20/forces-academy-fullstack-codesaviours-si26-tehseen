<?php
require_once 'includes/auth.php';

$pageTitle = "Assignments";
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
        <h3 class="fa-section-title">Assignments</h3>

        <div class="fa-panel fa-empty-state">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 6H18.5C19.33 6 20 6.67 20 7.5V19.5C20 20.33 19.33 21 18.5 21H5.5C4.67 21 4 20.33 4 19.5V7.5C4 6.67 4.67 6 5.5 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 12L11 15L16 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">Coming in Week 3</h5>
            <p style="font-size: 0.9rem;">Assignment submission and tracking will be built out next.</p>
        </div>
    </main>
</div>

</body>
</html>
