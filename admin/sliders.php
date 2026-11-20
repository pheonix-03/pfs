<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Hero Sliders & Carousel Controls';
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Save Global Carousel Playback Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_carousel_config'])) {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: sliders.php');
        exit;
    }

    $autoplay = isset($_POST['slider_autoplay']) ? '1' : '0';
    $delay    = (int)($_POST['slider_delay'] ?? 5000);
    $effect   = trim($_POST['slider_effect'] ?? 'slide');

    update_setting('slider_autoplay', $autoplay);
    update_setting('slider_delay', (string)$delay);
    update_setting('slider_effect', $effect);

    flash_message('success', 'Carousel playback settings saved!');
    header('Location: sliders.php');
    exit;
}

// Handle Delete
if ($action === 'delete' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $stmt = $pdo->prepare("DELETE FROM hero_slides WHERE id = ?");
        $stmt->execute([$id]);
        flash_message('success', 'Slide successfully removed.');
    }
    header('Location: sliders.php');
    exit;
}

// Handle Toggle Status
if ($action === 'toggle' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $pdo->prepare("UPDATE hero_slides SET status = (CASE WHEN status = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$id]);
        flash_message('success', 'Slide status updated.');
    }
    header('Location: sliders.php');
    exit;
}

// Handle Add / Edit POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_carousel_config'])) {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: sliders.php');
        exit;
    }

    $title           = trim($_POST['title'] ?? '');
    $subtitle        = trim($_POST['subtitle'] ?? '');
    $badge           = trim($_POST['badge'] ?? '');
    $badge_icon      = trim($_POST['badge_icon'] ?? 'bi bi-stars');
    $alignment       = in_array($_POST['alignment'] ?? '', ['left', 'center', 'right']) ? $_POST['alignment'] : 'left';
    $overlay_opacity = max(0.1, min(0.95, (float)($_POST['overlay_opacity'] ?? 0.65)));
    $btn_text        = trim($_POST['btn_text'] ?? '');
    $btn_url         = trim($_POST['btn_url'] ?? '');
    $btn2_text       = trim($_POST['btn2_text'] ?? '');
    $btn2_url        = trim($_POST['btn2_url'] ?? '');
    $sort_order      = (int)($_POST['sort_order'] ?? 0);
    $status          = isset($_POST['status']) ? 1 : 0;
    $bg_image        = trim($_POST['existing_bg_image'] ?? 'assets/img/slider/slide-1.jpg');

    if (!empty($_FILES['bg_image_file']['name'])) {
        $upload = upload_file($_FILES['bg_image_file'], 'slider');
        if ($upload['success']) {
            $bg_image = $upload['url'];
        } else {
            flash_message('error', 'Image upload failed: ' . $upload['error']);
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE hero_slides SET title = ?, subtitle = ?, badge = ?, badge_icon = ?, alignment = ?, overlay_opacity = ?, bg_image = ?, btn_text = ?, btn_url = ?, btn2_text = ?, btn2_url = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $subtitle, $badge, $badge_icon, $alignment, $overlay_opacity, $bg_image, $btn_text, $btn_url, $btn2_text, $btn2_url, $sort_order, $status, $id]);
        flash_message('success', 'Slide updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO hero_slides (title, subtitle, badge, badge_icon, alignment, overlay_opacity, bg_image, btn_text, btn_url, btn2_text, btn2_url, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $badge, $badge_icon, $alignment, $overlay_opacity, $bg_image, $btn_text, $btn_url, $btn2_text, $btn2_url, $sort_order, $status]);
        flash_message('success', 'New slide created successfully.');
    }

    header('Location: sliders.php');
    exit;
}

// Fetch slide for editing
$slide = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM hero_slides WHERE id = ?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch();
}

// Fetch all slides
$slides = $pdo->query("SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC")->fetchAll();

$sliderAutoplay = get_setting('slider_autoplay', '1');
$sliderDelay    = get_setting('slider_delay', '5000');
$sliderEffect   = get_setting('slider_effect', 'slide');

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo ($action === 'edit') ? 'Edit Hero Slide' : 'Create New Hero Slide'; ?></h3>
            <a href="sliders.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Sliders</a>
        </div>
        <div class="card-body">
            <form method="post" action="sliders.php<?php echo ($id > 0) ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="existing_bg_image" value="<?php echo e($slide['bg_image'] ?? 'assets/img/slider/slide-1.jpg'); ?>">

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Headline Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo e($slide['title'] ?? ''); ?>" required placeholder="e.g. Your Journey. Our Commitment.">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Subtitle / Description Narrative</label>
                        <textarea name="subtitle" class="form-control" rows="2" placeholder="Brief supporting statement..."><?php echo e($slide['subtitle'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Badge Label (Top Tag)</label>
                        <input type="text" name="badge" class="form-control" value="<?php echo e($slide['badge'] ?? ''); ?>" placeholder="e.g. BPO SOLUTIONS THAT DRIVE SUCCESS">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Badge Icon Class</label>
                        <input type="text" name="badge_icon" class="form-control" value="<?php echo e($slide['badge_icon'] ?? 'bi bi-stars'); ?>" placeholder="e.g. bi bi-stars">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Text Alignment</label>
                        <select name="alignment" class="form-select">
                            <option value="left" <?php echo (($slide['alignment'] ?? 'left') === 'left') ? 'selected' : ''; ?>>Left Aligned</option>
                            <option value="center" <?php echo (($slide['alignment'] ?? '') === 'center') ? 'selected' : ''; ?>>Center Aligned</option>
                            <option value="right" <?php echo (($slide['alignment'] ?? '') === 'right') ? 'selected' : ''; ?>>Right Aligned</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Darkness Overlay (Opacity: <span id="opacityVal"><?php echo round(($slide['overlay_opacity'] ?? 0.65) * 100); ?>%</span>)</label>
                        <input type="range" name="overlay_opacity" min="0.2" max="0.9" step="0.05" class="form-range" value="<?php echo $slide['overlay_opacity'] ?? 0.65; ?>" style="width: 100%;" oninput="document.getElementById('opacityVal').textContent = Math.round(this.value * 100) + '%'">
                        <p class="form-hint">Higher opacity creates higher contrast against bright background photos.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Primary Button Text</label>
                        <input type="text" name="btn_text" class="form-control" value="<?php echo e($slide['btn_text'] ?? 'Our Services'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Primary Button Link</label>
                        <input type="text" name="btn_url" class="form-control" value="<?php echo e($slide['btn_url'] ?? 'services.php'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Secondary Button Text (Optional)</label>
                        <input type="text" name="btn2_text" class="form-control" value="<?php echo e($slide['btn2_text'] ?? 'Contact Us'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Secondary Button Link (Optional)</label>
                        <input type="text" name="btn2_url" class="form-control" value="<?php echo e($slide['btn2_url'] ?? 'contact.php'); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Background Banner Image</label>
                        <?php if (!empty($slide['bg_image'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../<?php echo e($slide['bg_image']); ?>" alt="Current Banner" style="max-height: 120px; border-radius: 6px; border: 1px solid var(--border);">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="bg_image_file" class="form-control" accept="image/*">
                        <p class="form-hint">High resolution image recommended: 1920x800 px.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo e($slide['sort_order'] ?? 0); ?>">
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 30px;">
                        <input type="checkbox" name="status" id="statusCheck" value="1" <?php echo (!isset($slide) || $slide['status'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="statusCheck" style="margin: 0; cursor: pointer; color: #fff; font-weight: 500;">Active / Visible on Homepage</label>
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Slide</button>
                    <a href="sliders.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Carousel Settings Widget -->
    <div class="card" style="margin-bottom: 25px;">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-sliders me-2" style="color: var(--primary);"></i> Swiper Carousel Playback Settings</h3>
        </div>
        <div class="card-body">
            <form method="post" action="sliders.php" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="save_carousel_config" value="1">

                <div style="flex: 1; min-width: 180px;">
                    <label class="form-label">Slide Transition Effect</label>
                    <select name="slider_effect" class="form-select">
                        <option value="slide" <?php echo ($sliderEffect === 'slide') ? 'selected' : ''; ?>>Smooth Slide</option>
                        <option value="fade" <?php echo ($sliderEffect === 'fade') ? 'selected' : ''; ?>>Crossfade</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 180px;">
                    <label class="form-label">Slide Duration Delay (ms)</label>
                    <input type="number" name="slider_delay" class="form-control" value="<?php echo e($sliderDelay); ?>" step="500" min="2000" max="15000">
                </div>

                <div style="display: flex; align-items: center; gap: 10px; padding-bottom: 10px;">
                    <input type="checkbox" name="slider_autoplay" id="autoplayCheck" value="1" <?php echo ($sliderAutoplay === '1') ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="autoplayCheck" style="margin: 0; cursor: pointer; color: #fff; font-weight: 500;">Autoplay Active</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2"></i> Save Playback
                </button>
            </form>
        </div>
    </div>

    <!-- Sliders List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-images me-2" style="color: var(--primary);"></i> Homepage Banner Sliders (<?php echo count($slides); ?>)</h3>
            <a href="sliders.php?action=add" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add New Slide</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Headline &amp; Subtitle</th>
                            <th>Badge</th>
                            <th>Alignment</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($slides)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No slides found. Click 'Add New Slide' to create one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($slides as $sl): ?>
                                <tr>
                                    <td style="width: 120px;">
                                        <img src="../<?php echo e($sl['bg_image']); ?>" alt="Slide" style="width: 100px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);">
                                    </td>
                                    <td>
                                        <strong style="color: #fff;"><?php echo e($sl['title']); ?></strong>
                                        <?php if (!empty($sl['subtitle'])): ?>
                                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 3px;"><?php echo e(mb_strimwidth($sl['subtitle'], 0, 60, '...')); ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td><span style="font-size: 0.8rem; color: var(--info);"><?php echo e($sl['badge'] ?: '-'); ?></span></td>
                                    <td><span class="badge badge-secondary" style="text-transform: capitalize;"><?php echo e($sl['alignment'] ?? 'left'); ?></span></td>
                                    <td><?php echo (int)$sl['sort_order']; ?></td>
                                    <td>
                                        <a href="sliders.php?action=toggle&id=<?php echo $sl['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="badge <?php echo ($sl['status'] == 1) ? 'badge-success' : 'badge-danger'; ?>" style="text-decoration: none;">
                                            <?php echo ($sl['status'] == 1) ? 'Active' : 'Disabled'; ?>
                                        </a>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="sliders.php?action=edit&id=<?php echo $sl['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                        <a href="sliders.php?action=delete&id=<?php echo $sl['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this slide?');"><i class="bi bi-trash"></i></a>
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
