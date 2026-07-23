<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Student Details";

$studentIdParam = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT id, full_name, email, roll_number, class, created_at FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $studentIdParam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: students.php");
    exit();
}

// ---------- Bonus context: this student's course results ----------
$stmt2 = mysqli_prepare($conn, "SELECT subject, marks, total_marks, grade, exam_type FROM results WHERE student_id = ? ORDER BY exam_type ASC");
mysqli_stmt_bind_param($stmt2, "i", $studentIdParam);
mysqli_stmt_execute($stmt2);
$resultsData = mysqli_stmt_get_result($stmt2);
$studentResults = [];
while ($row = mysqli_fetch_assoc($resultsData)) {
    $studentResults[] = $row;
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
        <h3 class="fa-section-title">Student Details</h3>

        <div class="fa-panel mb-3">
            <div class="fa-stat-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 0;">
                <div>
                    <div class="fa-stat-label">Full Name</div>
                    <div class="fa-stat-value" style="font-size: 1.1rem;"><?php echo htmlspecialchars($student['full_name']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Email</div>
                    <div class="fa-stat-value" style="font-size: 1.1rem;"><?php echo htmlspecialchars($student['email']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Roll Number</div>
                    <div class="fa-stat-value" style="font-size: 1.1rem;"><?php echo htmlspecialchars($student['roll_number']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Class</div>
                    <div class="fa-stat-value" style="font-size: 1.1rem;"><?php echo htmlspecialchars($student['class']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Registered</div>
                    <div class="fa-stat-value" style="font-size: 1.1rem;">
                        <?php echo !empty($student['created_at']) ? date("d M Y", strtotime($student['created_at'])) : '—'; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="fa-panel">
            <h5 class="fa-section-title" style="margin-bottom: 12px;">Academic Results</h5>
            <?php if (empty($studentResults)): ?>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">No results uploaded for this student yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr><th>Subject</th><th>Marks</th><th>Total</th><th>Grade</th><th>Exam Type</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentResults as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($r['marks']); ?></td>
                                    <td><?php echo htmlspecialchars($r['total_marks']); ?></td>
                                    <td><span class="badge fa-badge-grade"><?php echo htmlspecialchars($r['grade']); ?></span></td>
                                    <td><?php echo htmlspecialchars($r['exam_type']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <a href="students.php" class="btn fa-btn-outline mt-3">&larr; Back to Students</a>
    </main>
</div>

</body>
</html>
