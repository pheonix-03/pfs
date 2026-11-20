<?php
$pageTitle = 'Services Manager';
require_once __DIR__ . '/includes/header.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$filter = $_GET['cat'] ?? 'all';

// Handle Delete
if ($action === 'delete' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        flash_message('success', 'Service removed successfully.');
    }
    header('Location: services.php' . ($filter !== 'all' ? '?cat=' . $filter : ''));
    exit;
}

// Handle Toggle
if ($action === 'toggle' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $pdo->prepare("UPDATE services SET status = (CASE WHEN status = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$id]);
        flash_message('success', 'Service status toggled.');
    }
    header('Location: services.php' . ($filter !== 'all' ? '?cat=' . $filter : ''));
    exit;
}

// Handle Save POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: services.php');
        exit;
    }

    $title      = trim($_POST['title'] ?? '');
    $category   = trim($_POST['category'] ?? 'bpo');
    $slug       = trim($_POST['slug'] ?? '') ?: slugify($title);
    $icon_class = trim($_POST['icon_class'] ?? 'bi bi-briefcase');
    $short_desc = trim($_POST['short_desc'] ?? '');
    $full_desc  = trim($_POST['full_desc'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status     = isset($_POST['status']) ? 1 : 0;
    $image_url  = trim($_POST['existing_image'] ?? 'assets/img/services/1.jpg');

    if (!empty($_FILES['image_file']['name'])) {
        $upload = upload_file($_FILES['image_file'], 'services');
        if ($upload['success']) {
            $image_url = $upload['url'];
        } else {
            flash_message('error', 'Image error: ' . $upload['error']);
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE services SET title = ?, category = ?, slug = ?, icon_class = ?, short_desc = ?, full_desc = ?, image_url = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $category, $slug, $icon_class, $short_desc, $full_desc, $image_url, $sort_order, $status, $id]);
        flash_message('success', 'Service updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (title, category, slug, icon_class, short_desc, full_desc, image_url, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $slug, $icon_class, $short_desc, $full_desc, $image_url, $sort_order, $status]);
        flash_message('success', 'New service added successfully.');
    }

    header('Location: services.php?cat=' . $category);
    exit;
}

// Fetch single service for edit
$service = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
}

// Fetch list
$sql = "SELECT * FROM services";
$params = [];
if ($filter !== 'all') {
    $sql .= " WHERE category = ?";
    $params[] = $filter;
}
$sql .= " ORDER BY category ASC, sort_order ASC, id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo ($action === 'edit') ? 'Edit Service' : 'Add New Service'; ?></h3>
            <a href="services.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Services</a>
        </div>
        <div class="card-body">
            <form method="post" action="services.php<?php echo ($id > 0) ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="existing_image" value="<?php echo e($service['image_url'] ?? 'assets/img/services/1.jpg'); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo e($service['title'] ?? ''); ?>" required placeholder="e.g. Voice Support">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="bpo" <?php echo (($service['category'] ?? '') === 'bpo') ? 'selected' : ''; ?>>BPO &amp; Customer Support</option>
                            <option value="digital" <?php echo (($service['category'] ?? '') === 'digital') ? 'selected' : ''; ?>>Digital Marketing</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bootstrap Icon Class</label>
                        <input type="text" name="icon_class" class="form-control" value="<?php echo e($service['icon_class'] ?? 'bi bi-headset'); ?>" placeholder="e.g. bi bi-headset, bi bi-search">
                        <p class="form-hint">Browse icons on <a href="https://icons.getbootstrap.com/" target="_blank" style="color: var(--primary);">Bootstrap Icons</a></p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo e($service['sort_order'] ?? 0); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Short Summary (Shown in cards)</label>
                        <textarea name="short_desc" class="form-control" rows="2" placeholder="One or two sentences explaining the service..."><?php echo e($service['short_desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Full Details (Shown in service modal / page)</label>
                        <textarea name="full_desc" class="form-control" rows="4" placeholder="Detailed breakdown of the service, deliverables, and SLAs..."><?php echo e($service['full_desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Service Card Image</label>
                        <?php if (!empty($service['image_url'])): ?>
                            <div style="margin-bottom: 8px;">
                                <img src="../<?php echo e($service['image_url']); ?>" alt="Current Image" style="max-height: 90px; border-radius: 6px; border: 1px solid var(--border);">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                        <input type="checkbox" name="status" id="srvStatus" value="1" <?php echo (!isset($service) || $service['status'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                        <label for="srvStatus" style="margin: 0; cursor: pointer; color: #fff; font-weight: 500;">Active / Visible to public</label>
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Service</button>
                    <a href="services.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <h3 class="card-title"><i class="bi bi-briefcase me-2" style="color: var(--primary);"></i> Services Directory</h3>
                <div style="display: flex; gap: 6px;">
                    <a href="services.php?cat=all" class="btn btn-sm <?php echo ($filter === 'all') ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
                    <a href="services.php?cat=bpo" class="btn btn-sm <?php echo ($filter === 'bpo') ? 'btn-primary' : 'btn-secondary'; ?>">BPO &amp; Support</a>
                    <a href="services.php?cat=digital" class="btn btn-sm <?php echo ($filter === 'digital') ? 'btn-primary' : 'btn-secondary'; ?>">Digital Marketing</a>
                </div>
            </div>
            <a href="services.php?action=add" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Service</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title &amp; Category</th>
                            <th>Icon</th>
                            <th>Summary</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No services found for this category.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($services as $srv): ?>
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="../<?php echo e($srv['image_url']); ?>" alt="Icon" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);">
                                    </td>
                                    <td>
                                        <strong style="color: #fff;"><?php echo e($srv['title']); ?></strong>
                                        <div>
                                            <span class="badge <?php echo ($srv['category'] === 'bpo') ? 'badge-success' : 'badge-warning'; ?>" style="font-size: 0.7rem; margin-top: 3px;">
                                                <?php echo ($srv['category'] === 'bpo') ? 'BPO Support' : 'Digital Marketing'; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td><i class="<?php echo e($srv['icon_class']); ?>" style="font-size: 1.3rem; color: var(--primary);"></i></td>
                                    <td style="max-width: 320px; font-size: 0.85rem; color: var(--text-muted);">
                                        <?php echo e(mb_strimwidth($srv['short_desc'] ?: $srv['full_desc'], 0, 80, '...')); ?>
                                    </td>
                                    <td><?php echo (int)$srv['sort_order']; ?></td>
                                    <td>
                                        <a href="services.php?action=toggle&id=<?php echo $srv['id']; ?>&cat=<?php echo $filter; ?>&csrf=<?php echo csrf_token(); ?>" class="badge <?php echo ($srv['status'] == 1) ? 'badge-success' : 'badge-danger'; ?>" style="text-decoration: none;">
                                            <?php echo ($srv['status'] == 1) ? 'Active' : 'Disabled'; ?>
                                        </a>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="services.php?action=edit&id=<?php echo $srv['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                        <a href="services.php?action=delete&id=<?php echo $srv['id']; ?>&cat=<?php echo $filter; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this service?');"><i class="bi bi-trash"></i></a>
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
