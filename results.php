<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "My Results";

// ---------- Pull results for THIS student only ----------
$results = [];
$stmt = mysqli_prepare($conn, "SELECT subject, marks, total_marks, grade, exam_type
                                FROM results
                                WHERE student_id = ?
                                ORDER BY exam_type ASC, subject ASC");
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
}
mysqli_stmt_close($stmt);
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

        <?php if (empty($results)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 20V13M12 20V8M19 20V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No results yet</h5>
                <p style="font-size: 0.9rem;">Your results will appear here once they're released.</p>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Marks Obtained</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Exam Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($r['marks']); ?></td>
                                    <td><?php echo htmlspecialchars($r['total_marks']); ?></td>
                                    <td>
                                        <span class="badge fa-badge-grade"><?php echo htmlspecialchars($r['grade']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($r['exam_type']); ?></td>
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
