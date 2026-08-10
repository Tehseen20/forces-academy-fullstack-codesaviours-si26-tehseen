<?php
require_once 'includes/auth.php';
require_once '../config/db.php';

$pageTitle = "Manage Fees";

// ---------- Student dropdown ----------
$students = [];
$result = mysqli_query($conn, "SELECT id, full_name, roll_number FROM students ORDER BY full_name ASC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
}

// ---------- All fee records, joined with student name ----------
$sql = "SELECT f.id, f.amount, f.due_date, f.paid_date, f.status, f.description,
               s.full_name, s.roll_number
        FROM fees f
        JOIN students s ON f.student_id = s.id
        ORDER BY f.due_date ASC";
$result = mysqli_query($conn, $sql);

$fees = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display-only: a pending fee whose due date has passed reads as "Overdue"
        // even though the stored status is still 'pending', until marked paid.
        if ($row['status'] === 'pending' && strtotime($row['due_date']) < strtotime('today')) {
            $row['display_status'] = 'overdue';
        } else {
            $row['display_status'] = $row['status'];
        }
        $fees[] = $row;
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
        <h3 class="fa-section-title">Manage Fees</h3>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Fee record added successfully.</div>
        <?php elseif (isset($_GET['paid'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Fee marked as paid.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert fa-alert fa-alert-success mb-3">Fee record deleted.</div>
        <?php endif; ?>

        <?php if (empty($students)): ?>
            <div class="alert fa-alert fa-alert-danger mb-3">You need at least one registered student before adding fee records.</div>
        <?php else: ?>
        <!-- Add fee form -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">Add Fee Record</h5>
            <form action="actions/save_fee.php" method="POST">
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
                        <label>Amount (PKR)</label>
                        <input type="number" name="amount" step="0.01" min="0" class="form-control fa-input" required>
                    </div>
                </div>
                <div class="fa-row-2">
                    <div class="fa-field">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control fa-input" required>
                    </div>
                    <div class="fa-field">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control fa-input" placeholder="e.g. Semester Fee" required>
                    </div>
                </div>
                <button type="submit" class="btn fa-btn-primary">Add Fee Record</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- All fee records -->
        <?php if (empty($fees)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No fee records yet</h5>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fees as $f): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($f['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($f['description']); ?></td>
                                    <td>PKR <?php echo number_format($f['amount'], 2); ?></td>
                                    <td><?php echo date("d M Y", strtotime($f['due_date'])); ?></td>
                                    <td>
                                        <span class="badge fa-badge-fee-<?php echo $f['display_status']; ?>">
                                            <?php echo ucfirst($f['display_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if ($f['status'] !== 'paid'): ?>
                                                <form action="actions/mark_fee_paid.php" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                                    <button type="submit" class="btn btn-sm fa-btn-outline">Mark Paid</button>
                                                </form>
                                            <?php endif; ?>
                                            <button type="button"
                                                    class="btn btn-sm fa-btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-fee-id="<?php echo $f['id']; ?>"
                                                    data-fee-label="<?php echo htmlspecialchars($f['description'] . ' — ' . $f['full_name']); ?>">
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
            <form action="actions/delete_fee.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete <strong id="deleteFeeLabel"></strong>? This action cannot be undone.</p>
                    <input type="hidden" name="id" id="deleteFeeId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fa-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fa-btn-danger">Delete Record</button>
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
        document.getElementById('deleteFeeId').value = button.getAttribute('data-fee-id');
        document.getElementById('deleteFeeLabel').textContent = button.getAttribute('data-fee-label');
    });
</script>

</body>
</html>
