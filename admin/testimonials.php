<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Testimonials & Client Reviews';
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Delete
if ($action === 'delete' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        flash_message('success', 'Testimonial deleted.');
    }
    header('Location: testimonials.php');
    exit;
}

// Toggle
if ($action === 'toggle' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $pdo->prepare("UPDATE testimonials SET status = (CASE WHEN status = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$id]);
        flash_message('success', 'Testimonial status toggled.');
    }
    header('Location: testimonials.php');
    exit;
}

// Save POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: testimonials.php');
        exit;
    }

    $client_name = trim($_POST['client_name'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $role        = trim($_POST['role'] ?? '');
    $rating      = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $review_text = trim($_POST['review_text'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $status      = isset($_POST['status']) ? 1 : 0;
    $avatar_url  = trim($_POST['existing_avatar'] ?? 'assets/img/services/s1.png');

    if (!empty($_FILES['avatar_file']['name'])) {
        $upload = upload_file($_FILES['avatar_file'], 'avatars');
        if ($upload['success']) {
            $avatar_url = $upload['url'];
        } else {
            flash_message('error', 'Avatar upload error: ' . $upload['error']);
        }
    }

    if (empty($client_name) || empty($review_text)) {
        flash_message('error', 'Client name and review text are required.');
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE testimonials SET client_name = ?, company = ?, role = ?, avatar_url = ?, rating = ?, review_text = ?, sort_order = ?, status = ? WHERE id = ?");
            $stmt->execute([$client_name, $company, $role, $avatar_url, $rating, $review_text, $sort_order, $status, $id]);
            flash_message('success', 'Testimonial updated.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, company, role, avatar_url, rating, review_text, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$client_name, $company, $role, $avatar_url, $rating, $review_text, $sort_order, $status]);
            flash_message('success', 'New testimonial added.');
        }
        header('Location: testimonials.php');
        exit;
    }
}

// Fetch single
$testimonial = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch();
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo ($action === 'edit') ? 'Edit Testimonial' : 'Add New Client Review'; ?></h3>
            <a href="testimonials.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form method="post" action="testimonials.php<?php echo ($id > 0) ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="existing_avatar" value="<?php echo e($testimonial['avatar_url'] ?? 'assets/img/services/s1.png'); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="client_name" class="form-control" value="<?php echo e($testimonial['client_name'] ?? ''); ?>" required placeholder="e.g. Michael Vance">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Company / Organization</label>
                        <input type="text" name="company" class="form-control" value="<?php echo e($testimonial['company'] ?? ''); ?>" placeholder="e.g. Nexus Logix Inc.">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Job Title / Role</label>
                        <input type="text" name="role" class="form-control" value="<?php echo e($testimonial['role'] ?? ''); ?>" placeholder="e.g. VP of Operations">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Star Rating (1 - 5)</label>
                        <select name="rating" class="form-select">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo (($testimonial['rating'] ?? 5) == $i) ? 'selected' : ''; ?>><?php echo str_repeat('★ ', $i); ?> (<?php echo $i; ?> Stars)</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Review / Testimonial Text</label>
                        <textarea name="review_text" class="form-control" rows="4" required placeholder="Write the client's endorsement or review quote..."><?php echo e($testimonial['review_text'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Client Photo / Avatar</label>
                        <?php if (!empty($testimonial['avatar_url'])): ?>
                            <div style="margin-bottom: 8px;">
                                <img src="../<?php echo e($testimonial['avatar_url']); ?>" alt="Avatar" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="avatar_file" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo e($testimonial['sort_order'] ?? 0); ?>">
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 30px;">
                        <input type="checkbox" name="status" id="testStatus" value="1" <?php echo (!isset($testimonial) || $testimonial['status'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                        <label for="testStatus" style="margin: 0; cursor: pointer; color: #fff; font-weight: 500;">Active / Visible on Carousel</label>
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Testimonial</button>
                    <a href="testimonials.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-chat-square-quote me-2" style="color: var(--primary);"></i> Client Testimonials &amp; Reviews</h3>
            <a href="testimonials.php?action=add" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Review</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Company &amp; Role</th>
                            <th>Rating</th>
                            <th>Review Quote</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($testimonials)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No testimonials yet. Click 'Add Review' to create one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($testimonials as $t): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="../<?php echo e($t['avatar_url']); ?>" alt="Avatar" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                                            <strong style="color: #fff;"><?php echo e($t['client_name']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="color: #e2e8f0; font-weight: 500;"><?php echo e($t['company'] ?: '-'); ?></div>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);"><?php echo e($t['role']); ?></div>
                                    </td>
                                    <td>
                                        <span style="color: #ffb703;"><?php echo str_repeat('★', $t['rating']); ?></span>
                                    </td>
                                    <td style="max-width: 340px; font-size: 0.85rem; color: var(--text-muted); font-style: italic;">
                                        "<?php echo e(mb_strimwidth($t['review_text'], 0, 85, '...')); ?>"
                                    </td>
                                    <td>
                                        <a href="testimonials.php?action=toggle&id=<?php echo $t['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="badge <?php echo ($t['status'] == 1) ? 'badge-success' : 'badge-danger'; ?>" style="text-decoration: none;">
                                            <?php echo ($t['status'] == 1) ? 'Active' : 'Disabled'; ?>
                                        </a>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="testimonials.php?action=edit&id=<?php echo $t['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                        <a href="testimonials.php?action=delete&id=<?php echo $t['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this testimonial?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
