<?php
require_once __DIR__ . '/includes/auth.php';

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pfs_inquiries_' . date('Y-m-d_H-i') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Date', 'Status', 'Message']);

    $stmt = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['subject'],
            $row['created_at'],
            $row['is_read'] ? 'Read' : 'Unread',
            $row['message']
        ]);
    }
    fclose($output);
    exit;
}

// Handle Delete
if (isset($_GET['del']) && verify_csrf($_GET['csrf'] ?? '')) {
    $id = (int)$_GET['del'];
    $pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([$id]);
    flash_message('success', 'Inquiry deleted.');
    header('Location: inquiries.php');
    exit;
}

// Handle Mark Read / Unread
if (isset($_GET['toggle_read']) && verify_csrf($_GET['csrf'] ?? '')) {
    $id = (int)$_GET['toggle_read'];
    $pdo->prepare("UPDATE inquiries SET is_read = (CASE WHEN is_read = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$id]);
    header('Location: inquiries.php');
    exit;
}

// View Single
$viewInquiry = null;
if (isset($_GET['view'])) {
    $id = (int)$_GET['view'];
    $stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);
    $viewInquiry = $stmt->fetch();
    if ($viewInquiry && $viewInquiry['is_read'] == 0) {
        $pdo->prepare("UPDATE inquiries SET is_read = 1 WHERE id = ?")->execute([$id]);
    }
}

$pageTitle = 'Inquiries & Leads Inbox';
require_once __DIR__ . '/includes/header.php';

$inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
?>

<?php if ($viewInquiry): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Inquiry Details #<?php echo $viewInquiry['id']; ?></h3>
            <a href="inquiries.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Inbox</a>
        </div>
        <div class="card-body">
            <div style="background: rgba(0,0,0,0.25); border: 1px solid var(--border); border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">From Name</span>
                        <h4 style="color: #fff; font-size: 1.1rem; margin-top: 4px;"><?php echo e($viewInquiry['name']); ?></h4>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Email</span>
                        <h4 style="margin-top: 4px;"><a href="mailto:<?php echo e($viewInquiry['email']); ?>" style="color: var(--info); text-decoration: none;"><?php echo e($viewInquiry['email']); ?></a></h4>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Phone Number</span>
                        <h4 style="color: #fff; font-size: 1rem; margin-top: 4px;"><?php echo e($viewInquiry['phone'] ?: 'N/A'); ?></h4>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Date Received</span>
                        <p style="color: var(--text-muted); margin-top: 4px;"><?php echo date('F d, Y \a\t H:i', strtotime($viewInquiry['created_at'])); ?></p>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Subject</span>
                    <h3 style="color: #fff; font-size: 1.15rem; margin-top: 4px;"><?php echo e($viewInquiry['subject'] ?: 'No Subject'); ?></h3>
                </div>

                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Message Content</span>
                    <div style="background: var(--bg-body); border: 1px solid var(--border); border-radius: 6px; padding: 18px; margin-top: 6px; font-size: 0.95rem; line-height: 1.6; color: #fff; white-space: pre-wrap;"><?php echo e($viewInquiry['message']); ?></div>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="mailto:<?php echo e($viewInquiry['email']); ?>?subject=Re:%20<?php echo urlencode($viewInquiry['subject'] ?: 'Your inquiry to Prime Fleet Services'); ?>" class="btn btn-primary">
                    <i class="bi bi-reply-fill"></i> Reply via Email
                </a>
                <a href="inquiries.php?del=<?php echo $viewInquiry['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger" onclick="return confirm('Delete this inquiry?');">
                    <i class="bi bi-trash"></i> Delete
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-inbox-fill me-2" style="color: var(--primary);"></i> Inquiries &amp; Leads Inbox (<?php echo count($inquiries); ?>)</h3>
        <a href="inquiries.php?export=csv" class="btn btn-secondary btn-sm">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export to CSV
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Sender Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inquiries)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">Your inbox is empty. No inquiries received yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inq): ?>
                            <tr style="<?php echo ($inq['is_read'] == 0) ? 'background: rgba(255, 94, 20, 0.05);' : ''; ?>">
                                <td>
                                    <a href="inquiries.php?toggle_read=<?php echo $inq['id']; ?>&csrf=<?php echo csrf_token(); ?>" style="text-decoration: none;">
                                        <?php if ($inq['is_read'] == 0): ?>
                                            <span class="badge badge-danger">Unread</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><strong style="color: #fff;"><?php echo e($inq['name']); ?></strong></td>
                                <td><a href="mailto:<?php echo e($inq['email']); ?>" style="color: var(--info); text-decoration: none;"><?php echo e($inq['email']); ?></a></td>
                                <td><?php echo e($inq['phone'] ?: '-'); ?></td>
                                <td><?php echo e(mb_strimwidth($inq['subject'] ?: 'General', 0, 30, '...')); ?></td>
                                <td style="color: var(--text-muted); font-size: 0.82rem;"><?php echo date('M d, Y H:i', strtotime($inq['created_at'])); ?></td>
                                <td style="text-align: right;">
                                    <a href="inquiries.php?view=<?php echo $inq['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-eye"></i> View</a>
                                    <a href="inquiries.php?del=<?php echo $inq['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this inquiry?');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
