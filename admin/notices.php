<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Post Notice";

$notices = [];
$result = mysqli_query($conn, "SELECT id, title, content, created_at FROM notices ORDER BY created_at DESC");
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="fa-shell">
    <?php include 'includes/sidebar.php'; ?>

    <main class="fa-main">
        <h3 class="fa-section-title">Post Notice</h3>

        <?php if (isset($_GET['posted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Notice posted successfully.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Notice deleted.</div>
        <?php endif; ?>

        <!-- Post notice form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">New Notice</h5>
            <form action="actions/save_notice.php" method="POST">
                <div class="fa-field">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control fa-input" required>
                </div>
                <div class="fa-field">
                    <label>Content</label>
                    <textarea name="content" class="form-control fa-input" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn fa-btn-primary">Post Notice</button>
            </form>
        </div>

        <!-- Existing notices -->
        <?php if (empty($notices)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No notices posted yet</h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <?php foreach ($notices as $n): ?>
                    <div class="fa-notice-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fa-notice-date"><?php echo date("d M Y, h:i A", strtotime($n['created_at'])); ?></div>
                            <h6><?php echo htmlspecialchars($n['title']); ?></h6>
                            <p><?php echo nl2br(htmlspecialchars($n['content'])); ?></p>
                        </div>
                        <form action="actions/delete_notice.php" method="POST" onsubmit="return confirm('Delete this notice? This cannot be undone.');">
                            <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                            <button type="submit" class="btn btn-sm fa-btn-danger">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
