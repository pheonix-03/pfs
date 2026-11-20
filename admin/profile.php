<?php
$pageTitle = 'Admin Profile & Security';
require_once __DIR__ . '/includes/header.php';

$adminId = $_SESSION['admin_id'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: profile.php');
        exit;
    }

    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username'] ?? '');
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

        if (empty($username) || !$email) {
            flash_message('error', 'Valid username and email are required.');
        } else {
            try {
                $stmtUp = $pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
                $stmtUp->execute([$username, $email, $adminId]);
                $_SESSION['admin_user'] = $username;
                $_SESSION['admin_email'] = $email;
                flash_message('success', 'Profile updated successfully.');
            } catch (Exception $e) {
                flash_message('error', 'Username or email may already be in use.');
            }
        }
    }

    if (isset($_POST['change_password'])) {
        $currPass = $_POST['current_password'] ?? '';
        $newPass  = $_POST['new_password'] ?? '';
        $confPass = $_POST['confirm_password'] ?? '';

        if (!password_verify($currPass, $admin['password'])) {
            flash_message('error', 'Current password is incorrect.');
        } elseif (strlen($newPass) < 6) {
            flash_message('error', 'New password must be at least 6 characters long.');
        } elseif ($newPass !== $confPass) {
            flash_message('error', 'New passwords do not match.');
        } else {
            $hashed = password_hash($newPass, PASSWORD_BCRYPT);
            $stmtUp = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmtUp->execute([$hashed, $adminId]);
            flash_message('success', 'Password successfully changed! Please use your new password next time you sign in.');
        }
    }

    header('Location: profile.php');
    exit;
}
?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <!-- Profile Info -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-person-circle me-2" style="color: var(--primary);"></i> Account Details</h3>
        </div>
        <div class="card-body">
            <form method="post" action="profile.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="update_profile" value="1">

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo e($admin['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($admin['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Created</label>
                    <input type="text" class="form-control" value="<?php echo date('M d, Y H:i', strtotime($admin['created_at'])); ?>" disabled style="opacity: 0.6;">
                </div>

                <div class="form-group">
                    <label class="form-label">Last Login</label>
                    <input type="text" class="form-control" value="<?php echo $admin['last_login'] ? date('M d, Y H:i', strtotime($admin['last_login'])) : 'First Session'; ?>" disabled style="opacity: 0.6;">
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="bi bi-check-lg"></i> Update Account
                </button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-key-fill me-2" style="color: var(--warning);"></i> Security &amp; Password</h3>
        </div>
        <div class="card-body">
            <form method="post" action="profile.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="change_password" value="1">

                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required placeholder="Minimum 6 characters">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat new password">
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="bi bi-shield-lock"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
