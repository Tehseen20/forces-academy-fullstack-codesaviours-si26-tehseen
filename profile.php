<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$pageTitle = "My Profile";

$profileError   = "";
$profileSuccess = "";
$passwordError  = "";
$passwordSuccess = "";

// ---------- Fetch current student record ----------
function fa_get_student($conn, $studentId) {
    $stmt = mysqli_prepare($conn, "SELECT full_name, email, roll_number, class, password FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

$student = fa_get_student($conn, $studentId);

// ============================================
// Handle: Edit Profile (name + email)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName  = trim($_POST['full_name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');

    if ($newName === '' || $newEmail === '') {
        $profileError = "Name and email cannot be empty.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $profileError = "Please enter a valid email address.";
    } else {
        // Make sure no OTHER student already uses this email
        $stmt = mysqli_prepare($conn, "SELECT id FROM students WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "si", $newEmail, $studentId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $profileError = "That email is already in use by another account.";
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, "UPDATE students SET full_name = ?, email = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $newName, $newEmail, $studentId);

            if (mysqli_stmt_execute($stmt)) {
                // Keep the session in sync with the new name immediately
                $_SESSION['student_name'] = $newName;
                $studentName = $newName;
                $profileSuccess = "Profile updated successfully.";
                $student = fa_get_student($conn, $studentId); // refresh displayed values
            } else {
                $profileError = "Something went wrong updating your profile. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// ============================================
// Handle: Change Password
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_new_password'] ?? '';

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $passwordError = "Please fill in all three password fields.";
    } elseif (!password_verify($currentPassword, $student['password'])) {
        $passwordError = "Your current password is incorrect.";
    } elseif (strlen($newPassword) < 8) {
        $passwordError = "New password must be at least 8 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $passwordError = "New password and confirmation do not match.";
    } else {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE students SET password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $newHash, $studentId);

        if (mysqli_stmt_execute($stmt)) {
            $passwordSuccess = "Password changed successfully.";
            $student = fa_get_student($conn, $studentId); // refresh in-memory hash
        } else {
            $passwordError = "Something went wrong changing your password. Please try again.";
        }
        mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="fa-shell">
    <?php include 'includes/sidebar.php'; ?>

    <main class="fa-main">
        <h3 class="fa-section-title">My Profile</h3>

        <!-- Current details -->
        <div class="fa-panel mb-4">
            <h5 class="fa-section-title" style="margin-bottom: 14px;">Student Details</h5>
            <div class="fa-stat-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 0;">
                <div>
                    <div class="fa-stat-label">Full Name</div>
                    <div class="fa-stat-value" style="font-size: 1rem;"><?php echo htmlspecialchars($student['full_name']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Email</div>
                    <div class="fa-stat-value" style="font-size: 1rem;"><?php echo htmlspecialchars($student['email']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Roll Number</div>
                    <div class="fa-stat-value" style="font-size: 1rem;"><?php echo htmlspecialchars($student['roll_number']); ?></div>
                </div>
                <div>
                    <div class="fa-stat-label">Class</div>
                    <div class="fa-stat-value" style="font-size: 1rem;"><?php echo htmlspecialchars($student['class']); ?></div>
                </div>
            </div>
        </div>

        <div class="fa-content-grid" style="grid-template-columns: 1fr 1fr;">

            <!-- Edit Profile -->
            <div class="fa-panel">
                <h5 class="fa-section-title" style="margin-bottom: 14px;">Edit Profile</h5>

                <?php if ($profileError): ?>
                    <div class="alert fa-alert fa-alert-danger mb-3"><?php echo htmlspecialchars($profileError); ?></div>
                <?php elseif ($profileSuccess): ?>
                    <div class="alert fa-alert fa-alert-success mb-3"><?php echo htmlspecialchars($profileSuccess); ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="fa-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control fa-input"
                               value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    <div class="fa-field">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control fa-input"
                               value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="fa-field mb-0">
                        <label>Roll Number <span style="font-weight: 400; color: var(--slate-400);">(not editable)</span></label>
                        <input type="text" class="form-control fa-input" value="<?php echo htmlspecialchars($student['roll_number']); ?>" disabled>
                    </div>
                    <div class="fa-field">
                        <label>Class <span style="font-weight: 400; color: var(--slate-400);">(not editable)</span></label>
                        <input type="text" class="form-control fa-input" value="<?php echo htmlspecialchars($student['class']); ?>" disabled>
                    </div>

                    <button type="submit" name="update_profile" value="1" class="btn fa-btn-primary">Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="fa-panel">
                <h5 class="fa-section-title" style="margin-bottom: 14px;">Change Password</h5>

                <?php if ($passwordError): ?>
                    <div class="alert fa-alert fa-alert-danger mb-3"><?php echo htmlspecialchars($passwordError); ?></div>
                <?php elseif ($passwordSuccess): ?>
                    <div class="alert fa-alert fa-alert-success mb-3"><?php echo htmlspecialchars($passwordSuccess); ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="fa-field">
                        <label>Current Password</label>
                        <div class="fa-password-wrap">
                            <input type="password" name="current_password" class="form-control fa-input"
                                   autocomplete="off" required>
                            <button type="button" class="fa-password-toggle" aria-label="Show password">
                                <svg class="fa-eye-closed" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                <svg class="fa-eye-open" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10.6 5.2C11.06 5.07 11.53 5 12 5C18.5 5 22 12 22 12C21.6 12.8 20.6 14.3 19 15.6M6.5 6.5C4 8.1 2 12 2 12C2 12 5.5 19 12 19C13.9 19 15.5 18.5 16.8 17.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.9 10.1C9.5 10.5 9.3 11 9.3 11.5C9.3 12.6 10.2 13.5 11.3 13.5C11.9 13.5 12.4 13.2 12.8 12.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="fa-field">
                        <label>New Password</label>
                        <div class="fa-password-wrap">
                            <input type="password" name="new_password" class="form-control fa-input"
                                   autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                            <button type="button" class="fa-password-toggle" aria-label="Show password">
                                <svg class="fa-eye-closed" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                <svg class="fa-eye-open" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10.6 5.2C11.06 5.07 11.53 5 12 5C18.5 5 22 12 22 12C21.6 12.8 20.6 14.3 19 15.6M6.5 6.5C4 8.1 2 12 2 12C2 12 5.5 19 12 19C13.9 19 15.5 18.5 16.8 17.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.9 10.1C9.5 10.5 9.3 11 9.3 11.5C9.3 12.6 10.2 13.5 11.3 13.5C11.9 13.5 12.4 13.2 12.8 12.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="fa-field">
                        <label>Confirm New Password</label>
                        <div class="fa-password-wrap">
                            <input type="password" name="confirm_new_password" class="form-control fa-input"
                                   autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                            <button type="button" class="fa-password-toggle" aria-label="Show password">
                                <svg class="fa-eye-closed" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                <svg class="fa-eye-open" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10.6 5.2C11.06 5.07 11.53 5 12 5C18.5 5 22 12 22 12C21.6 12.8 20.6 14.3 19 15.6M6.5 6.5C4 8.1 2 12 2 12C2 12 5.5 19 12 19C13.9 19 15.5 18.5 16.8 17.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.9 10.1C9.5 10.5 9.3 11 9.3 11.5C9.3 12.6 10.2 13.5 11.3 13.5C11.9 13.5 12.4 13.2 12.8 12.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="change_password" value="1" class="btn fa-btn-outline">Change Password</button>
                </form>
            </div>

        </div>
    </main>
</div>

<script src="js/main.js"></script>
</body>
</html>
