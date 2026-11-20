<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Page Sections Content Manager';
$pageFilter = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Toggle Visibility
if ($action === 'toggle' && $editId > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $pdo->prepare("UPDATE page_sections SET is_visible = (CASE WHEN is_visible = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$editId]);
        flash_message('success', 'Section visibility updated.');
    }
    header('Location: page_sections.php?page=' . $pageFilter);
    exit;
}

// Handle Section Save POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: page_sections.php?page=' . $pageFilter);
        exit;
    }

    $id         = (int)($_POST['section_id'] ?? 0);
    $badge      = trim($_POST['badge'] ?? '');
    $title      = trim($_POST['title'] ?? '');
    $subtitle   = trim($_POST['subtitle'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $btn_text   = trim($_POST['btn_text'] ?? '');
    $btn_url    = trim($_POST['btn_url'] ?? '');
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;
    $image_url  = trim($_POST['existing_image'] ?? '');

    // Handle optional image upload
    if (!empty($_FILES['image_file']['name'])) {
        $upload = upload_file($_FILES['image_file'], 'sections');
        if ($upload['success']) {
            $image_url = $upload['url'];
        } else {
            flash_message('error', 'Image error: ' . $upload['error']);
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE page_sections SET badge = ?, title = ?, subtitle = ?, content = ?, btn_text = ?, btn_url = ?, image_url = ?, is_visible = ? WHERE id = ?");
        $stmt->execute([$badge, $title, $subtitle, $content, $btn_text, $btn_url, $image_url, $is_visible, $id]);
        flash_message('success', 'Section content updated successfully!');
    }

    header('Location: page_sections.php?page=' . $pageFilter);
    exit;
}

// Fetch single section if editing
$editSection = null;
if ($action === 'edit' && $editId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE id = ?");
    $stmt->execute([$editId]);
    $editSection = $stmt->fetch();
}

// Fetch sections for active page tab
$sql = "SELECT * FROM page_sections";
$params = [];
if ($pageFilter !== 'all') {
    $sql .= " WHERE page = ?";
    $params[] = $pageFilter;
}
$sql .= " ORDER BY page ASC, sort_order ASC, id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sectionsList = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Tabs Navigation -->
<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px;">
    <?php
    $tabs = [
        'home'     => ['Home Page', 'bi bi-house-door'],
        'about'    => ['About Us', 'bi bi-info-circle'],
        'services' => ['Services', 'bi bi-briefcase'],
        'digital'  => ['Digital Marketing', 'bi bi-graph-up'],
        'contact'  => ['Contact Us', 'bi bi-envelope'],
        'all'      => ['All Sections', 'bi bi-grid']
    ];
    foreach ($tabs as $key => $tabInfo):
        $isActive = ($pageFilter === $key);
    ?>
        <a href="page_sections.php?page=<?php echo $key; ?>" class="btn <?php echo $isActive ? 'btn-primary' : 'btn-secondary'; ?>">
            <i class="<?php echo $tabInfo[1]; ?>"></i> <?php echo $tabInfo[0]; ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($editSection): ?>
    <!-- Edit Section Modal/Card -->
    <div class="card" style="border: 2px solid var(--primary);">
        <div class="card-header" style="background: rgba(255, 94, 20, 0.08);">
            <h3 class="card-title">
                <i class="bi bi-pencil-square me-2" style="color: var(--primary);"></i>
                Editing: <?php echo e($editSection['section_name']); ?>
            </h3>
            <a href="page_sections.php?page=<?php echo $pageFilter; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-x-lg"></i> Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="page_sections.php?page=<?php echo $pageFilter; ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="section_id" value="<?php echo $editSection['id']; ?>">
                <input type="hidden" name="existing_image" value="<?php echo e($editSection['image_url']); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Badge Tag / Subtitle</label>
                        <input type="text" name="badge" class="form-control" value="<?php echo e($editSection['badge']); ?>" placeholder="e.g. HOW WE HELP YOUR BUSINESS">
                        <p class="form-hint">The pill tag displayed above the main heading.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Headline Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo e($editSection['title']); ?>" required placeholder="e.g. Your Journey. Our Commitment.">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Subheading / Intro Kicker</label>
                        <input type="text" name="subtitle" class="form-control" value="<?php echo e($editSection['subtitle']); ?>" placeholder="Brief supporting statement">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Main Body Content / Description</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Detailed text content..."><?php echo e($editSection['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Call-To-Action Button Text</label>
                        <input type="text" name="btn_text" class="form-control" value="<?php echo e($editSection['btn_text']); ?>" placeholder="e.g. Explore Services">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Button Target URL</label>
                        <input type="text" name="btn_url" class="form-control" value="<?php echo e($editSection['btn_url']); ?>" placeholder="e.g. services.php or contact.php">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Section Graphic / Background Image</label>
                        <?php if (!empty($editSection['image_url'])): ?>
                            <div style="margin-bottom: 8px;">
                                <img src="../<?php echo e($editSection['image_url']); ?>" alt="Section Preview" style="max-height: 80px; border-radius: 6px; border: 1px solid var(--border);">
                                <span style="font-size: 0.8rem; color: var(--text-muted); margin-left: 10px;"><?php echo e($editSection['image_url']); ?></span>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group full-width" style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                        <input type="checkbox" name="is_visible" id="visCheck" value="1" <?php echo ($editSection['is_visible'] == 1) ? 'checked' : ''; ?> style="width: 20px; height: 20px; cursor: pointer;">
                        <label for="visCheck" style="margin: 0; cursor: pointer; color: #fff; font-weight: 600; font-size: 0.95rem;">
                            Section is Visible on Website
                        </label>
                    </div>
                </div>

                <div style="margin-top: 25px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Save Changes</button>
                    <a href="page_sections.php?page=<?php echo $pageFilter; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Sections List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-layers me-2" style="color: var(--primary);"></i>
            Editable Sections (<?php echo count($sectionsList); ?>)
        </h3>
        <span style="font-size: 0.82rem; color: var(--text-muted);">Click 'Edit' on any section to customize its content or toggle visibility.</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Section Name &amp; Key</th>
                        <th>Badge / Tag</th>
                        <th>Headline Title</th>
                        <th>Preview Content</th>
                        <th>Visibility</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sectionsList)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No sections found for this page tab.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sectionsList as $s): ?>
                            <tr>
                                <td>
                                    <strong style="color: #fff;"><?php echo e($s['section_name']); ?></strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">
                                        [<?php echo e($s['page']); ?>] <?php echo e($s['section_key']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($s['badge'])): ?>
                                        <span class="badge badge-warning" style="font-size: 0.75rem;"><?php echo e($s['badge']); ?></span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 600; color: #e2e8f0; max-width: 200px;">
                                    <?php echo e($s['title'] ?: '(No title)'); ?>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.82rem; max-width: 300px;">
                                    <?php echo e(mb_strimwidth($s['content'] ?: $s['subtitle'], 0, 75, '...')); ?>
                                </td>
                                <td>
                                    <a href="page_sections.php?action=toggle&id=<?php echo $s['id']; ?>&page=<?php echo $pageFilter; ?>&csrf=<?php echo csrf_token(); ?>" class="badge <?php echo ($s['is_visible'] == 1) ? 'badge-success' : 'badge-danger'; ?>" style="text-decoration: none;">
                                        <?php echo ($s['is_visible'] == 1) ? 'Visible' : 'Hidden'; ?>
                                    </a>
                                </td>
                                <td style="text-align: right;">
                                    <a href="page_sections.php?action=edit&id=<?php echo $s['id']; ?>&page=<?php echo $pageFilter; ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
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
