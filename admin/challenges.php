<?php
$pageTitle = 'Challenges vs Solutions Manager';
require_once __DIR__ . '/includes/header.php';

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: challenges.php');
        exit;
    }

    $id    = (int)($_POST['item_id'] ?? 0);
    $type  = ($_POST['type'] === 'solution') ? 'solution' : 'challenge';
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $icon  = trim($_POST['icon'] ?? '');
    $order = (int)($_POST['sort_order'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE challenges_solutions SET type = ?, title = ?, description = ?, icon = ?, sort_order = ? WHERE id = ?");
        $stmt->execute([$type, $title, $desc, $icon, $order, $id]);
        flash_message('success', 'Item updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO challenges_solutions (type, title, description, icon, sort_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$type, $title, $desc, $icon, $order]);
        flash_message('success', 'New item created.');
    }

    header('Location: challenges.php');
    exit;
}

// Handle Delete
if (isset($_GET['del']) && verify_csrf($_GET['csrf'] ?? '')) {
    $pdo->prepare("DELETE FROM challenges_solutions WHERE id = ?")->execute([(int)$_GET['del']]);
    flash_message('success', 'Item removed.');
    header('Location: challenges.php');
    exit;
}

$challenges = $pdo->query("SELECT * FROM challenges_solutions WHERE type = 'challenge' ORDER BY sort_order ASC, id ASC")->fetchAll();
$solutions = $pdo->query("SELECT * FROM challenges_solutions WHERE type = 'solution' ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <!-- Challenges Column -->
    <div class="card">
        <div class="card-header" style="border-left: 4px solid var(--danger);">
            <h3 class="card-title"><i class="bi bi-x-circle-fill me-2" style="color: var(--danger);"></i> The Challenges (Pain Points)</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="margin-bottom: 20px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($challenges as $ch): ?>
                            <tr>
                                <td><strong style="color: #fff; font-size: 0.88rem;"><?php echo e($ch['title']); ?></strong></td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);"><?php echo e($ch['description']); ?></td>
                                <td style="text-align: right;">
                                    <a href="challenges.php?del=<?php echo $ch['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Solutions Column -->
    <div class="card">
        <div class="card-header" style="border-left: 4px solid var(--success);">
            <h3 class="card-title"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i> Our Solutions (PFS Value)</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="margin-bottom: 20px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solutions as $sol): ?>
                            <tr>
                                <td><strong style="color: #fff; font-size: 0.88rem;"><?php echo e($sol['title']); ?></strong></td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);"><?php echo e($sol['description']); ?></td>
                                <td style="text-align: right;">
                                    <a href="challenges.php?del=<?php echo $sol['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add New Entry -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-plus-circle me-2" style="color: var(--primary);"></i> Add New Comparison Point</h3>
    </div>
    <div class="card-body">
        <form method="post" action="challenges.php">
            <?php echo csrf_field(); ?>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="challenge">Challenge (Red / Problem)</option>
                        <option value="solution">Solution (Green / Deliverable)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. SLOW RESPONSE TIMES" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bootstrap Icon</label>
                    <input type="text" name="icon" class="form-control" placeholder="bi bi-hourglass-split">
                </div>
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Detail the challenge or how Prime Fleet Services solves it..."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Add Item</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
