<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "Fees";

// ---------- All fee records for this student ----------
$fees = [];
$totalPending = 0.0;

$stmt = mysqli_prepare($conn, "SELECT amount, due_date, paid_date, status, description
                                FROM fees
                                WHERE student_id = ?
                                ORDER BY due_date ASC");
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] === 'pending' && strtotime($row['due_date']) < strtotime('today')) {
        $row['display_status'] = 'overdue';
    } else {
        $row['display_status'] = $row['status'];
    }

    if ($row['status'] !== 'paid') {
        $totalPending += (float) $row['amount'];
    }

    $fees[] = $row;
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
        <h3 class="fa-section-title">Fees</h3>

        <!-- Total pending — shown prominently at the top -->
        <div class="fa-fee-summary <?php echo $totalPending > 0 ? 'has-due' : ''; ?>">
            <div>
                <div class="fa-fee-summary-label">Total Pending Amount</div>
                <div class="fa-fee-summary-value">PKR <?php echo number_format($totalPending, 2); ?></div>
            </div>
            <?php if ($totalPending > 0): ?>
                <div class="fa-fee-summary-tag">Payment Required</div>
            <?php else: ?>
                <div class="fa-fee-summary-tag fa-fee-summary-tag-clear">All Clear</div>
            <?php endif; ?>
        </div>

        <?php if (empty($fees)): ?>
            <div class="fa-panel fa-empty-state">
                <h5 style="font-family: var(--font-display); text-transform: uppercase; color: var(--navy-800);">No fee records yet</h5>
                <p style="font-size: 0.9rem;">Your fee records will appear here once the Academy office posts them.</p>
            </div>
        <?php else: ?>
            <div class="fa-panel">
                <div class="table-responsive">
                    <table class="table fa-results-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fees as $f): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($f['description']); ?></td>
                                    <td>PKR <?php echo number_format($f['amount'], 2); ?></td>
                                    <td><?php echo date("d M Y", strtotime($f['due_date'])); ?></td>
                                    <td>
                                        <span class="badge fa-badge-fee-<?php echo $f['display_status']; ?>">
                                            <?php echo ucfirst($f['display_status']); ?>
                                        </span>
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

</body>
</html>
