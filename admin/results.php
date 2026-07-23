<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Upload Results";

// ---------- Dropdown data ----------
$students = [];
$result = mysqli_query($conn, "SELECT id, full_name, roll_number FROM students ORDER BY full_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
}

$courses = [];
$result = mysqli_query($conn, "SELECT id, course_name FROM courses ORDER BY course_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
}

// ---------- Recently uploaded results (most recent 10) ----------
$recentResults = [];
$sql = "SELECT r.id, s.full_name, c.course_name, r.subject, r.marks, r.total_marks, r.grade, r.exam_type
        FROM results r
        JOIN students s ON r.student_id = s.id
        JOIN courses c ON r.course_id = c.id
        ORDER BY r.id DESC
        LIMIT 10";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recentResults[] = $row;
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
        <h3 class="fa-section-title">Upload Results</h3>

        <?php if (isset($_GET['uploaded'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Result uploaded successfully.</div>
        <?php endif; ?>

        <!-- Upload form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">New Result</h5>
            <form action="actions/save_result.php" method="POST">
                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Student</label>
                        <select name="student_id" class="form-control fa-input" required>
                            <option value="">Select student...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>">
                                    <?php echo htmlspecialchars($s['full_name']); ?> (<?php echo htmlspecialchars($s['roll_number']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fa-field">
                        <label>Course</label>
                        <select name="course_id" class="form-control fa-input" required>
                            <option value="">Select course...</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="fa-field">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control fa-input" required>
                </div>

                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Marks Obtained</label>
                        <input type="number" name="marks" class="form-control fa-input" min="0" required>
                    </div>
                    <div class="fa-field">
                        <label>Total Marks</label>
                        <input type="number" name="total_marks" class="form-control fa-input" min="1" required>
                    </div>
                </div>

                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Grade</label>
                        <input type="text" name="grade" class="form-control fa-input" placeholder="e.g. A, B+, C" required>
                    </div>
                    <div class="fa-field">
                        <label>Exam Type</label>
                        <select name="exam_type" class="form-control fa-input" required>
                            <option value="">Select...</option>
                            <option value="Quiz">Quiz</option>
                            <option value="Mid-Term">Mid-Term</option>
                            <option value="Final">Final</option>
                            <option value="Assignment">Assignment</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn fa-btn-primary">Upload Result</button>
            </form>
        </div>

        <!-- Recently uploaded results -->
        <?php if (!empty($recentResults)): ?>
            <div class="fa-panel">
                <h5 class="fa-section-title" style="margin-bottom: 14px;">Recently Uploaded</h5>
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Exam Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentResults as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($r['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($r['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($r['marks']); ?>/<?php echo htmlspecialchars($r['total_marks']); ?></td>
                                    <td><span class="badge fa-badge-grade"><?php echo htmlspecialchars($r['grade']); ?></span></td>
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
