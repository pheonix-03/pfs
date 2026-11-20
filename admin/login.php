<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please refresh the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$username, $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_user'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];

                // Update last login
                $pdo->prepare("UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?")->execute([$admin['id']]);

                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}

$logoUrl = get_setting('logo_url', '../assets/img/logo.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Prime Fleet Services</title>
    <link rel="stylesheet" href="../assets/fonts/bootstrap-icons-1.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent: #fbbf24;
            --accent-hover: #f59e0b;
            --bg-dark: #05070f;
            --card-bg: #0b111e;
            --border: rgba(59, 130, 246, 0.22);
            --border-hover: rgba(251, 191, 36, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body {
            background: radial-gradient(circle at top center, #0e172e 0%, #05070f 70%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 30px rgba(37, 99, 235, 0.12);
            position: relative;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2563eb 0%, #fbbf24 50%, #2563eb 100%);
            border-radius: 14px 14px 0 0;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-header img {
            max-height: 50px;
            margin-bottom: 15px;
        }
        .brand-header h2 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
        }
        .brand-header p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 6px;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid #ef4444;
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #cbd5e1;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: color 0.2s;
        }
        .form-control {
            width: 100%;
            background: #060a12;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px 12px 40px;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 12px rgba(251, 191, 36, 0.3);
        }
        .form-control:focus + i,
        .input-wrap:focus-within i {
            color: var(--accent);
        }
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #fff;
            border: 1px solid rgba(59, 130, 246, 0.4);
            padding: 13px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
            transform: translateY(-1px);
        }
        .login-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .login-footer strong {
            color: var(--accent);
        }
        .login-footer a {
            color: #60a5fa;
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-footer a:hover {
            color: var(--accent);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <img src="../<?php echo e($logoUrl); ?>" alt="Prime Fleet Services">
            <h2>Control Panel Login</h2>
            <p>Enter your credentials to access the CMS</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-wrap">
                    <i class="bi bi-person"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Sign In to Dashboard
            </button>
        </form>

        <div class="login-footer">
            <p>Default credentials: <strong>admin</strong> / <strong>admin123</strong></p>
            <p style="margin-top: 8px;"><a href="../index.php"><i class="bi bi-arrow-left"></i> Back to Website</a></p>
        </div>
    </div>

</body>
</html>
