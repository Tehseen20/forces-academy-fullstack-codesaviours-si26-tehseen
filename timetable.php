<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "Timetable";

// ---------- Get this student's class ----------
$studentClass = '';
$stmt = mysqli_prepare($conn, "SELECT class FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $studentClass = $row['class'];
}
mysqli_stmt_close($stmt);

// ---------- Pull this class's timetable entries ----------
$entries = [];
if ($studentClass !== '') {
    $stmt = mysqli_prepare($conn, "SELECT day, time_slot, subject, teacher FROM timetable WHERE class = ?");
    mysqli_stmt_bind_param($stmt, "s", $studentClass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        // Index by "day|time_slot" for fast lookup while building the grid
        $entries[$row['day'] . '|' . $row['time_slot']] = $row;
    }
    mysqli_stmt_close($stmt);
}

// ---------- Fixed grid structure (same lists admin uses, so entries always land on the grid) ----------
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
        <h3 class="fa-section-title">Timetable</h3>

        <?php if ($studentClass === ''): ?>
            <div class="alert fa-alert fa-alert-danger mb-3">
                Your account doesn't have a class assigned yet, so no timetable can be shown. Contact the Academy office to update your record.
            </div>
        <?php elseif (empty($entries)): ?>
            <div class="fa-panel fa-empty-state">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3.5" y="4.5" width="17" height="16" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 3V6M16 3V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No timetable posted yet</h5>
                <p style="font-size: 0.9rem;">No classes have been scheduled for <?php echo htmlspecialchars($studentClass); ?> yet. Check back soon.</p>
            </div>
        <?php else: ?>
            <div class="fa-panel mb-3" style="padding: 14px 20px;">
                <span class="fa-stat-label" style="margin: 0;">Class</span>
                <div class="fa-stat-value" style="font-size: 1.1rem;"><?php echo htmlspecialchars($studentClass); ?></div>
            </div>

            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table fa-timetable-grid align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="white-space: nowrap;">Time Slot</th>
                                <?php foreach ($days as $d): ?>
                                    <th><?php echo $d; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timeSlots as $slot): ?>
                                <tr>
                                    <td class="fa-timetable-slot"><?php echo $slot; ?></td>
                                    <?php foreach ($days as $d):
                                        $key = $d . '|' . $slot;
                                        $cell = $entries[$key] ?? null;
                                    ?>
                                        <td>
                                            <?php if ($cell): ?>
                                                <div class="fa-timetable-subject"><?php echo htmlspecialchars($cell['subject']); ?></div>
                                                <div class="fa-timetable-teacher"><?php echo htmlspecialchars($cell['teacher']); ?></div>
                                            <?php else: ?>
                                                <span class="fa-timetable-empty">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
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
