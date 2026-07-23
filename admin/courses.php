<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Manage Courses";

$courses = [];
$result = mysqli_query($conn, "SELECT id, course_name, description, teacher_name FROM courses ORDER BY course_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
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
        <h3 class="fa-section-title">Manage Courses</h3>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Course added successfully.</div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Course updated successfully.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Course deleted.</div>
        <?php endif; ?>

        <!-- Add new course form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">Add New Course</h5>
            <form action="actions/save_course.php" method="POST">
                <div class="fa-field">
                    <label>Course Name</label>
                    <input type="text" name="course_name" class="form-control fa-input" required>
                </div>
                <div class="fa-field">
                    <label>Description</label>
                    <textarea name="description" class="form-control fa-input" rows="2" required></textarea>
                </div>
                <div class="fa-field">
                    <label>Teacher Name</label>
                    <input type="text" name="teacher_name" class="form-control fa-input" required>
                </div>
                <button type="submit" class="btn fa-btn-primary">Add Course</button>
            </form>
        </div>

        <!-- Existing courses list -->
        <?php if (empty($courses)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No courses yet</h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Description</th>
                                <th>Teacher</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars(mb_strimwidth($c['description'], 0, 80, '...')); ?></td>
                                    <td><?php echo htmlspecialchars($c['teacher_name']); ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_course.php?id=<?php echo $c['id']; ?>" class="btn btn-sm fa-btn-outline">Edit</a>
                                            <button type="button"
                                                    class="btn btn-sm fa-btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-course-id="<?php echo $c['id']; ?>"
                                                    data-course-name="<?php echo htmlspecialchars($c['course_name']); ?>">
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
            <form action="actions/delete_course.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong id="deleteCourseName"></strong>?
                        This will also remove related assignments. This action cannot be undone.
                    </p>
                    <input type="hidden" name="id" id="deleteCourseId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-danger">Delete Course</button>
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
        document.getElementById('deleteCourseId').value = button.getAttribute('data-course-id');
        document.getElementById('deleteCourseName').textContent = button.getAttribute('data-course-name');
    });
</script>

</body>
</html>
