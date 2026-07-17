<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "Assignments";

// ---------- Pull all assignments with their course name ----------
$assignments = [];
$sql = "SELECT a.id, a.title, a.description, a.due_date, c.course_name
        FROM assignments a
        JOIN courses c ON a.course_id = c.id
        ORDER BY a.due_date ASC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $assignments[] = $row;
    }
}

// ---------- Find which assignments THIS student has already submitted ----------
$submittedIds = [];
$stmt = mysqli_prepare($conn, "SELECT assignment_id FROM submissions WHERE student_id = ?");
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$subResult = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($subResult)) {
    $submittedIds[] = $row['assignment_id'];
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
        <h3 class="fa-section-title">Assignments</h3>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert fa-alert fa-alert-success mb-4">Assignment submitted successfully.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert fa-alert fa-alert-danger mb-4">
                <?php
                $errors = [
                    'filetype' => 'Only PDF and image files (JPG, PNG) are allowed.',
                    'filesize' => 'File is too large. Maximum size is 5MB.',
                    'upload'   => 'Something went wrong uploading your file. Please try again.',
                    'duplicate'=> 'You have already submitted this assignment.',
                ];
                echo $errors[$_GET['error']] ?? 'Submission failed. Please try again.';
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($assignments)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 6H18.5C19.33 6 20 6.67 20 7.5V19.5C20 20.33 19.33 21 18.5 21H5.5C4.67 21 4 20.33 4 19.5V7.5C4 6.67 4.67 6 5.5 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 12L11 15L16 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No assignments yet</h5>
                <p style="font-size: 0.9rem;">Check back once your instructors post assignments.</p>
            </div>
        <?php else: ?>
            <div class="fa-course-grid">
                <?php foreach ($assignments as $a): ?>
                    <?php $isSubmitted = in_array($a['id'], $submittedIds); ?>
                    <div class="fa-course-card">
                        <div class="fa-course-card-top"><?php echo htmlspecialchars($a['title']); ?></div>
                        <div class="fa-course-card-body">
                            <div class="fa-assignment-course"><?php echo htmlspecialchars($a['course_name']); ?></div>
                            <p><?php echo htmlspecialchars($a['description']); ?></p>

                            <div class="fa-assignment-footer">
                                <div class="fa-assignment-due">
                                    Due: <?php echo date("d M Y", strtotime($a['due_date'])); ?>
                                </div>

                                <?php if ($isSubmitted): ?>
                                    <span class="badge fa-badge-submitted mt-2">Submitted</span>
                                <?php else: ?>
                                    <button type="button"
                                            class="btn fa-btn-primary mt-2 w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadModal"
                                            data-assignment-id="<?php echo $a['id']; ?>"
                                            data-assignment-title="<?php echo htmlspecialchars($a['title']); ?>">
                                        Submit Assignment
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<!-- Upload Modal (shared, reused for every assignment) -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fa-modal-content">
            <form action="upload_assignment.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalTitle">Submit Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="assignment_id" id="uploadAssignmentId">

                    <div class="fa-field">
                        <label>Upload File (PDF or Image, max 5MB)</label>
                        <input type="file" name="assignment_file" class="form-control fa-input" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-primary">Upload &amp; Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const uploadModal = document.getElementById('uploadModal');
    uploadModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const assignmentId = button.getAttribute('data-assignment-id');
        const assignmentTitle = button.getAttribute('data-assignment-title');

        document.getElementById('uploadAssignmentId').value = assignmentId;
        document.getElementById('uploadModalTitle').textContent = 'Submit: ' + assignmentTitle;
    });
</script>

</body>
</html>
