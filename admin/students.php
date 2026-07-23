<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Manage Students";

// ---------- Search filter (by name or roll number) ----------
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT id, full_name, email, roll_number, class, created_at
            FROM students
            WHERE full_name LIKE ? OR roll_number LIKE ?
            ORDER BY full_name ASC";
    $stmt = mysqli_prepare($conn, $sql);
    $likeSearch = "%$search%";
    mysqli_stmt_bind_param($stmt, "ss", $likeSearch, $likeSearch);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT id, full_name, email, roll_number, class, created_at FROM students ORDER BY full_name ASC");
}

$students = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
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
        <h3 class="fa-section-title">Manage Students</h3>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Student record deleted.</div>
        <?php endif; ?>

        <!-- Search bar -->
        <form method="GET" class="fa-panel mb-3 d-flex gap-2" style="padding: 16px;">
            <input type="text"
                   name="search"
                   class="form-control fa-input"
                   placeholder="Search by name or roll number..."
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn fa-btn-primary" style="white-space: nowrap;">Search</button>
            <?php if ($search !== ''): ?>
                <a href="students.php" class="btn fa-btn-outline" style="white-space: nowrap;">Clear</a>
            <?php endif; ?>
        </form>

        <?php if (empty($students)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="3.2" stroke="currentColor" stroke-width="1.5"/><path d="M5 20c0-3.6 3.1-6.5 7-6.5s7 2.9 7 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">
                    <?php echo $search !== '' ? 'No matching students' : 'No students registered yet'; ?>
                </h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roll Number</th>
                                <th>Class</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($s['email']); ?></td>
                                    <td><?php echo htmlspecialchars($s['roll_number']); ?></td>
                                    <td><?php echo htmlspecialchars($s['class']); ?></td>
                                    <td><?php echo !empty($s['created_at']) ? date("d M Y", strtotime($s['created_at'])) : '—'; ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="student_view.php?id=<?php echo $s['id']; ?>" class="btn btn-sm fa-btn-outline">View</a>
                                            <button type="button"
                                                    class="btn btn-sm fa-btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-student-id="<?php echo $s['id']; ?>"
                                                    data-student-name="<?php echo htmlspecialchars($s['full_name']); ?>">
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
            <form action="actions/delete_student.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong id="deleteStudentName"></strong>'s record?
                        This action cannot be undone.
                    </p>
                    <input type="hidden" name="id" id="deleteStudentId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-danger">Delete Student</button>
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
        document.getElementById('deleteStudentId').value = button.getAttribute('data-student-id');
        document.getElementById('deleteStudentName').textContent = button.getAttribute('data-student-name');
    });
</script>

</body>
</html>
