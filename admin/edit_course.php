<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Edit Course";

$courseId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT id, course_name, description, teacher_name FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $courseId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result);

if (!$course) {
    header("Location: courses.php");
    exit();
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
        <h3 class="fa-section-title">Edit Course</h3>

        <div class="fa-panel" style="max-width: 560px;">
            <form action="actions/save_course.php" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">

                <div class="fa-field">
                    <label>Course Name</label>
                    <input type="text" name="course_name" class="form-control fa-input"
                           value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                </div>
                <div class="fa-field">
                    <label>Description</label>
                    <textarea name="description" class="form-control fa-input" rows="3" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>
                <div class="fa-field">
                    <label>Teacher Name</label>
                    <input type="text" name="teacher_name" class="form-control fa-input"
                           value="<?php echo htmlspecialchars($course['teacher_name']); ?>" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn fa-btn-primary">Update Course</button>
                    <a href="courses.php" class="btn fa-btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
