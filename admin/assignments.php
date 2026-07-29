<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Manage Assignments";

// ---------- Dropdown data ----------
$courses = [];
$result = mysqli_query($conn, "SELECT id, course_name FROM courses ORDER BY course_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
}

// ---------- All assignments, with course name + submission count ----------
$sql = "SELECT a.id, a.title, a.description, a.due_date, c.course_name,
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

        <?php if (isset($_GET['added'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Assignment added successfully.</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Assignment updated successfully.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Assignment deleted.</div>
        <?php endif; ?>

        <?php if (empty($courses)): ?>
            <div class="alert fa-alert fa-alert-danger mb-3">
                You need to add at least one course before you can create assignments.
                <a href="courses.php">Add a course first &rarr;</a>
            </div>
        <?php else: ?>
        <!-- Add new assignment form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">Add New Assignment</h5>
            <form action="actions/save_assignment.php" method="POST">
                <div class="fa-field">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control fa-input" required>
                </div>
                <div class="fa-field">
                    <label>Description</label>
                    <textarea name="description" class="form-control fa-input" rows="2" required></textarea>
                </div>
                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Course</label>
                        <select name="course_id" class="form-control fa-input" required>
                            <option value="">Select course...</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fa-field">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control fa-input" required>
                    </div>
                </div>
                <button type="submit" class="btn fa-btn-primary">Add Assignment</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Existing assignments -->
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['title']); ?></td>
                                    <td><?php echo htmlspecialchars($a['course_name']); ?></td>
                                    <td><?php echo date("d M Y", strtotime($a['due_date'])); ?></td>
                                    <td><span class="badge fa-badge-grade"><?php echo (int) $a['submission_count']; ?></span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_assignment.php?id=<?php echo $a['id']; ?>" class="btn btn-sm fa-btn-outline">Edit</a>
                                            <button type="button"
                                                    class="btn btn-sm fa-btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-assignment-id="<?php echo $a['id']; ?>"
                                                    data-assignment-title="<?php echo htmlspecialchars($a['title']); ?>">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fa-modal-content">
            <form action="actions/delete_assignment.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong id="deleteAssignmentTitle"></strong>?
                        Any student submissions for this assignment will also be removed. This action cannot be undone.
                    </p>
                    <input type="hidden" name="id" id="deleteAssignmentId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-danger">Delete Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteAssignmentId').value = button.getAttribute('data-assignment-id');
        document.getElementById('deleteAssignmentTitle').textContent = button.getAttribute('data-assignment-title');
    });
</script>

</body>
</html>
