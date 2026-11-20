<?php
require_once __DIR__ . '/auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$adminUser = $_SESSION['admin_user'] ?? 'Admin';

// Count unread inquiries
$unreadCount = 0;
try {
    $unreadCount = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE is_read = 0")->fetchColumn();
} catch (Exception $e) {}

$siteName = get_setting('site_name', 'Prime Fleet Services');
$logoUrl = get_setting('logo_url', 'assets/img/logo.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - ' : ''; ?>PFS Control Panel</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/fonts/bootstrap-icons-1.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent: #fbbf24;
            --accent-hover: #f59e0b;
            --bg-body: #05070f;
            --bg-sidebar: #090e1a;
            --bg-card: #0d1424;
            --bg-input: #060a12;
            --border: rgba(59, 130, 246, 0.16);
            --border-highlight: rgba(251, 191, 36, 0.35);
            --text: #cbd5e1;
            --text-heading: #f8fafc;
            --text-muted: #64748b;
            --sidebar-width: 260px;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #fbbf24;
            --info: #38bdf8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body {
            background-color: var(--bg-body);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
        
        /* Layout */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--bg-body);
        }
        .admin-topbar {
            height: 65px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .admin-content {
            padding: 30px;
            flex: 1;
        }

        /* Sidebar Elements */
        .sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .sidebar-brand img {
            max-height: 36px;
            max-width: 140px;
            object-fit: contain;
        }
        .sidebar-brand-title {
            color: var(--text-heading);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .sidebar-nav {
            padding: 15px 0;
            flex: 1;
        }
        .nav-category {
            padding: 10px 20px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 600;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.04);
            color: var(--text-heading);
        }
        .nav-link.active {
            background: rgba(37, 99, 235, 0.14);
            color: #60a5fa;
            border-left-color: var(--accent);
            font-weight: 600;
        }
        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        .badge-count {
            margin-left: auto;
            background: var(--accent);
            color: #000000;
            padding: 2px 7px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        /* Topbar Elements */
        .topbar-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--text-heading);
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .btn-view-site {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #21262d;
            border: 1px solid var(--border);
            color: var(--text-heading);
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-view-site:hover {
            background: #30363d;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-heading);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Cards and Components */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-heading);
        }
        .card-body {
            padding: 22px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        .btn-secondary {
            background: #21262d;
            color: var(--text-heading);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover {
            background: #30363d;
        }
        .btn-danger {
            background: rgba(218, 54, 51, 0.15);
            color: #f85149;
            border: 1px solid rgba(218, 54, 51, 0.4);
        }
        .btn-danger:hover {
            background: var(--danger);
            color: #fff;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }
        .table th {
            background: rgba(0,0,0,0.2);
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .table tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-heading);
        }
        .form-control, .form-select {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 14px;
            color: var(--text-heading);
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 90px;
        }
        .form-hint {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background: rgba(35, 134, 54, 0.2); color: #3fb950; border: 1px solid rgba(35, 134, 54, 0.4); }
        .badge-warning { background: rgba(210, 153, 34, 0.2); color: #d29922; border: 1px solid rgba(210, 153, 34, 0.4); }
        .badge-danger { background: rgba(218, 54, 51, 0.2); color: #f85149; border: 1px solid rgba(218, 54, 51, 0.4); }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .alert-success { background: rgba(35, 134, 54, 0.15); border: 1px solid #238636; color: #3fb950; }
        .alert-error { background: rgba(218, 54, 51, 0.15); border: 1px solid #da3633; color: #f85149; }

        /* Responsive */
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform 0.2s; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="index.php" class="sidebar-brand">
            <img src="../<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>">
        </a>

        <div class="sidebar-nav">
            <div class="nav-category">Main Overview</div>
            <a href="index.php" class="nav-link <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-category">Website Content</div>
            <a href="page_sections.php" class="nav-link <?php echo ($currentPage === 'page_sections.php') ? 'active' : ''; ?>">
                <i class="bi bi-layers-fill" style="color: var(--primary);"></i> Page Sections (All Pages)
            </a>
            <a href="settings.php" class="nav-link <?php echo ($currentPage === 'settings.php') ? 'active' : ''; ?>">
                <i class="bi bi-sliders"></i> Site Settings &amp; Info
            </a>
            <a href="sliders.php" class="nav-link <?php echo ($currentPage === 'sliders.php') ? 'active' : ''; ?>">
                <i class="bi bi-images"></i> Hero Sliders &amp; Carousel
            </a>
            <a href="services.php" class="nav-link <?php echo ($currentPage === 'services.php') ? 'active' : ''; ?>">
                <i class="bi bi-briefcase"></i> Services
            </a>
            <a href="certificates.php" class="nav-link <?php echo ($currentPage === 'certificates.php') ? 'active' : ''; ?>">
                <i class="bi bi-patch-check"></i> Certificates
            </a>
            <a href="testimonials.php" class="nav-link <?php echo ($currentPage === 'testimonials.php') ? 'active' : ''; ?>">
                <i class="bi bi-chat-square-quote"></i> Testimonials
            </a>
            <a href="statistics.php" class="nav-link <?php echo ($currentPage === 'statistics.php') ? 'active' : ''; ?>">
                <i class="bi bi-bar-chart"></i> Why Choose Us &amp; Stats
            </a>
            <a href="challenges.php" class="nav-link <?php echo ($currentPage === 'challenges.php') ? 'active' : ''; ?>">
                <i class="bi bi-arrow-left-right"></i> Challenges &amp; Solutions
            </a>
            <a href="about_content.php" class="nav-link <?php echo ($currentPage === 'about_content.php') ? 'active' : ''; ?>">
                <i class="bi bi-file-text"></i> CEO Message &amp; Story
            </a>

            <div class="nav-category">Lead Generation</div>
            <a href="inquiries.php" class="nav-link <?php echo ($currentPage === 'inquiries.php') ? 'active' : ''; ?>">
                <i class="bi bi-envelope"></i> Inquiries
                <?php if ($unreadCount > 0): ?>
                    <span class="badge-count"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="subscribers.php" class="nav-link <?php echo ($currentPage === 'subscribers.php') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Newsletter List
            </a>

            <div class="nav-category">Account</div>
            <a href="profile.php" class="nav-link <?php echo ($currentPage === 'profile.php') ? 'active' : ''; ?>">
                <i class="bi bi-person-gear"></i> Admin Profile
            </a>
            <a href="logout.php" class="nav-link">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <h1 class="topbar-title"><?php echo isset($pageTitle) ? e($pageTitle) : 'Dashboard'; ?></h1>

            <div class="topbar-actions">
                <a href="../index.php" target="_blank" class="btn-view-site">
                    <i class="bi bi-box-arrow-up-right"></i> View Website
                </a>
                <div class="user-dropdown">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($adminUser, 0, 1)); ?>
                    </div>
                    <span><?php echo e($adminUser); ?></span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="admin-content">
            <?php foreach (get_flash_messages() as $flash): ?>
                <div class="alert alert-<?php echo ($flash['type'] === 'success') ? 'success' : 'error'; ?>">
                    <span><?php echo e($flash['message']); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="bi bi-x"></i></button>
                </div>
            <?php endforeach; ?>
