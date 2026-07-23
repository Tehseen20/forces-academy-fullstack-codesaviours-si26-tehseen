<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Manage Assignments";

$sql = "SELECT a.id, a.title, a.due_date, c.course_name,
               (SELECT COUNT(*) FROM submissions sub WHERE sub.assignment_id = a.id) AS submission_count
        FROM assignments a
        JOIN courses c ON a.course_id = c.id
        ORDER BY a.due_date ASC";
$result = mysqli_query($conn, $sql);

$assignments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $assignments[] = $row;
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
        <h3 class="fa-section-title">Manage Assignments</h3>

        <?php if (empty($assignments)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No assignments posted yet</h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Due Date</th>
                                <th>Submissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['title']); ?></td>
                                    <td><?php echo htmlspecialchars($a['course_name']); ?></td>
                                    <td><?php echo date("d M Y", strtotime($a['due_date'])); ?></td>
                                    <td><span class="badge fa-badge-grade"><?php echo (int) $a['submission_count']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
