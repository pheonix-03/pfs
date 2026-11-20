<?php
require_once __DIR__ . '/includes/auth.php';

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pfs_subscribers_' . date('Y-m-d_H-i') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Email', 'Date']);

    $stmt = $pdo->query("SELECT * FROM subscribers ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        fputcsv($output, [$row['id'], $row['email'], $row['created_at']]);
    }
    fclose($output);
    exit;
}

// Handle Delete
if (isset($_GET['del']) && verify_csrf($_GET['csrf'] ?? '')) {
    $id = (int)$_GET['del'];
    $pdo->prepare("DELETE FROM subscribers WHERE id = ?")->execute([$id]);
    flash_message('success', 'Subscriber removed.');
    header('Location: subscribers.php');
    exit;
}

$pageTitle = 'Newsletter Subscribers';
require_once __DIR__ . '/includes/header.php';

$subscribers = $pdo->query("SELECT * FROM subscribers ORDER BY created_at DESC")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-people-fill me-2" style="color: var(--warning);"></i> Newsletter Subscribers (<?php echo count($subscribers); ?>)</h3>
        <a href="subscribers.php?export=csv" class="btn btn-secondary btn-sm">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export to CSV
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email Address</th>
                        <th>Subscribed Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($subscribers)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">No subscribers yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subscribers as $sub): ?>
                            <tr>
                                <td style="color: var(--text-muted);"><?php echo $sub['id']; ?></td>
                                <td>
                                    <a href="mailto:<?php echo e($sub['email']); ?>" style="color: var(--info); text-decoration: none; font-weight: 500;">
                                        <?php echo e($sub['email']); ?>
                                    </a>
                                </td>
                                <td style="color: var(--text-muted);"><?php echo date('M d, Y H:i', strtotime($sub['created_at'])); ?></td>
                                <td style="text-align: right;">
                                    <a href="subscribers.php?del=<?php echo $sub['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this subscriber?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
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
