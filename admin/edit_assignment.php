<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Edit Assignment";

$assignmentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT id, title, description, course_id, due_date FROM assignments WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $assignmentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$assignment = mysqli_fetch_assoc($result);

if (!$assignment) {
    header("Location: assignments.php");
    exit();
}

$courses = [];
$result = mysqli_query($conn, "SELECT id, course_name FROM courses ORDER BY course_name ASC");
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
        <h3 class="fa-section-title">Edit Assignment</h3>

        <div class="fa-panel" style="max-width: 620px;">
            <form action="actions/save_assignment.php" method="POST">
                <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">

                <div class="fa-field">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control fa-input"
                           value="<?php echo htmlspecialchars($assignment['title']); ?>" required>
                </div>
                <div class="fa-field">
                    <label>Description</label>
                    <textarea name="description" class="form-control fa-input" rows="3" required><?php echo htmlspecialchars($assignment['description']); ?></textarea>
                </div>
                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Course</label>
                        <select name="course_id" class="form-control fa-input" required>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $assignment['course_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fa-field">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control fa-input"
                               value="<?php echo htmlspecialchars($assignment['due_date']); ?>" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn fa-btn-primary">Update Assignment</button>
                    <a href="assignments.php" class="btn fa-btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
