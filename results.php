<?php
require_once 'includes/auth.php';

$pageTitle = "My Results";
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
        <h3 class="fa-section-title">My Results</h3>

        <div class="fa-panel fa-empty-state">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 20V13M12 20V8M19 20V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">Coming Soon</h5>
            <p style="font-size: 0.9rem;">Your academic results and grades will appear here once released.</p>
        </div>
    </main>
</div>

</body>
</html>
