<?php
$pageTitle = 'Why Choose Us & Statistics';
require_once __DIR__ . '/includes/header.php';

// Handle Why Choose Us POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_why'])) {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: statistics.php');
        exit;
    }

    $id    = (int)($_POST['why_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $icon  = trim($_POST['icon'] ?? 'bi bi-check2-circle');
    $order = (int)($_POST['sort_order'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE why_choose_us SET title = ?, description = ?, icon = ?, sort_order = ? WHERE id = ?");
        $stmt->execute([$title, $desc, $icon, $order, $id]);
        flash_message('success', 'Feature updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO why_choose_us (title, description, icon, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $icon, $order]);
        flash_message('success', 'Feature added.');
    }
    header('Location: statistics.php');
    exit;
}

// Handle Stats POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_stat'])) {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: statistics.php');
        exit;
    }

    $id    = (int)($_POST['stat_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $val   = trim($_POST['value'] ?? '');
    $suf   = trim($_POST['suffix'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE statistics SET title = ?, value = ?, suffix = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $val, $suf, $desc, $id]);
        flash_message('success', 'Metric updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO statistics (title, value, suffix, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $val, $suf, $desc]);
        flash_message('success', 'Metric added.');
    }
    header('Location: statistics.php');
    exit;
}

// Handle Deletes
if (isset($_GET['del_why']) && verify_csrf($_GET['csrf'] ?? '')) {
    $pdo->prepare("DELETE FROM why_choose_us WHERE id = ?")->execute([(int)$_GET['del_why']]);
    flash_message('success', 'Item deleted.');
    header('Location: statistics.php');
    exit;
}
if (isset($_GET['del_stat']) && verify_csrf($_GET['csrf'] ?? '')) {
    $pdo->prepare("DELETE FROM statistics WHERE id = ?")->execute([(int)$_GET['del_stat']]);
    flash_message('success', 'Metric deleted.');
    header('Location: statistics.php');
    exit;
}

$whyPoints = $pdo->query("SELECT * FROM why_choose_us ORDER BY sort_order ASC, id ASC")->fetchAll();
$stats = $pdo->query("SELECT * FROM statistics ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<!-- Why Choose Us Features -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-shield-check me-2" style="color: var(--primary);"></i> 'Why Choose Us' Pillars</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="margin-bottom: 30px;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Order</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($whyPoints as $wp): ?>
                        <tr>
                            <td><i class="<?php echo e($wp['icon']); ?>" style="font-size: 1.3rem; color: var(--primary);"></i></td>
                            <td><strong style="color: #fff;"><?php echo e($wp['title']); ?></strong></td>
                            <td style="color: var(--text-muted); font-size: 0.85rem; max-width: 400px;"><?php echo e($wp['description']); ?></td>
                            <td><?php echo (int)$wp['sort_order']; ?></td>
                            <td style="text-align: right;">
                                <a href="statistics.php?del_why=<?php echo $wp['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this feature?');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 style="font-size: 0.95rem; color: var(--text-heading); margin-bottom: 15px;">Add / Update Feature</h4>
        <form method="post" action="statistics.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="save_why" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Feature Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. ADVANCED TECHNOLOGY" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bootstrap Icon</label>
                    <input type="text" name="icon" class="form-control" placeholder="bi bi-cpu" value="bi bi-check2-circle">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Feature Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Explain the key benefits to the client..."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Save Feature</button>
        </form>
    </div>
</div>

<!-- Key Metrics & Statistics -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-bar-chart-line-fill me-2" style="color: var(--info);"></i> Counter Statistics (Numbers &amp; KPIs)</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="margin-bottom: 30px;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Metric Value</th>
                        <th>Suffix</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats as $st): ?>
                        <tr>
                            <td><strong style="color: var(--primary); font-size: 1.2rem;"><?php echo e($st['value']); ?></strong></td>
                            <td><?php echo e($st['suffix']); ?></td>
                            <td style="color: #fff; font-weight: 600;"><?php echo e($st['title']); ?></td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo e($st['description']); ?></td>
                            <td style="text-align: right;">
                                <a href="statistics.php?del_stat=<?php echo $st['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this statistic?');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 style="font-size: 0.95rem; color: var(--text-heading); margin-bottom: 15px;">Add / Update Metric</h4>
        <form method="post" action="statistics.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="save_stat" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Number / Value</label>
                    <input type="text" name="value" class="form-control" placeholder="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Suffix (e.g. %, +, /7)</label>
                    <input type="text" name="suffix" class="form-control" placeholder="%">
                </div>
                <div class="form-group">
                    <label class="form-label">Title / Label</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Satisfied" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Description (Optional)</label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. SLA Delivery Guaranteed">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Save Metric</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
