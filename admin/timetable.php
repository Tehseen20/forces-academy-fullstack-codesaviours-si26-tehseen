<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Timetable";

// ---------- Class dropdown: pull distinct classes actually used by students ----------
$classes = [];
$result = mysqli_query($conn, "SELECT DISTINCT class FROM students WHERE class != '' ORDER BY class ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $classes[] = $row['class'];
    }
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

$timeSlots = [
    '08:00 AM - 09:00 AM',
    '09:00 AM - 10:00 AM',
    '10:00 AM - 11:00 AM',
    '11:00 AM - 12:00 PM',
    '12:00 PM - 01:00 PM',
    '01:00 PM - 02:00 PM',
    '02:00 PM - 03:00 PM',
    '03:00 PM - 04:00 PM',
    '04:00 PM - 05:00 PM',
];

// ---------- All timetable entries, sorted for readability ----------
$dayOrder = "FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')";
$sql = "SELECT id, class, day, time_slot, subject, teacher FROM timetable ORDER BY class ASC, $dayOrder ASC, time_slot ASC";
$result = mysqli_query($conn, $sql);

$entries = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $entries[] = $row;
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
        <h3 class="fa-section-title">Timetable</h3>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Timetable entry added successfully.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Timetable entry deleted.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert fa-alert fa-alert-danger mb-3">Please fill in all fields correctly.</div>
        <?php endif; ?>

        <?php if (empty($classes)): ?>
            <div class="alert fa-alert fa-alert-danger mb-3">
                No student classes found yet — the class dropdown pulls from registered students.
                Register at least one student first, or add classes manually once a student exists.
            </div>
        <?php else: ?>
        <!-- Add new timetable entry form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">Add Timetable Entry</h5>
            <form action="actions/save_timetable.php" method="POST">
                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Class</label>
                        <select name="class" class="form-control fa-input" required>
                            <option value="">Select class...</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fa-field">
                        <label>Day</label>
                        <select name="day" class="form-control fa-input" required>
                            <option value="">Select day...</option>
                            <?php foreach ($days as $d): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Time Slot</label>
                        <select name="time_slot" class="form-control fa-input" required>
                            <option value="">Select time slot...</option>
                            <?php foreach ($timeSlots as $t): ?>
                                <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fa-field">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control fa-input" required>
                    </div>
                </div>

                <div class="fa-field">
                    <label>Teacher</label>
                    <input type="text" name="teacher" class="form-control fa-input" required>
                </div>

                <button type="submit" class="btn fa-btn-primary">Add to Timetable</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Existing entries -->
        <?php if (empty($entries)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No timetable entries yet</h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Day</th>
                                <th>Time Slot</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entries as $e): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($e['class']); ?></td>
                                    <td><?php echo htmlspecialchars($e['day']); ?></td>
                                    <td><?php echo htmlspecialchars($e['time_slot']); ?></td>
                                    <td><?php echo htmlspecialchars($e['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($e['teacher']); ?></td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm fa-btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-entry-id="<?php echo $e['id']; ?>"
                                                data-entry-label="<?php echo htmlspecialchars($e['class'] . ' — ' . $e['day'] . ' ' . $e['time_slot']); ?>">
                                            Delete
                                        </button>
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
            <form action="actions/delete_timetable.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong id="deleteEntryLabel"></strong>?
                        This action cannot be undone.
                    </p>
                    <input type="hidden" name="id" id="deleteEntryId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-danger">Delete Entry</button>
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
        document.getElementById('deleteEntryId').value = button.getAttribute('data-entry-id');
        document.getElementById('deleteEntryLabel').textContent = button.getAttribute('data-entry-label');
    });
</script>

</body>
</html>
