<?php
$pageTitle = 'CEO Message & Page Content';
require_once __DIR__ . '/includes/header.php';

// Fetch CEO message content
$stmt = $pdo->prepare("SELECT * FROM pages_content WHERE section_key = 'ceo_message' LIMIT 1");
$stmt->execute();
$ceo = $stmt->fetch();

// Fetch About page content
$stmtAb = $pdo->prepare("SELECT * FROM pages_content WHERE section_key = 'company_overview' LIMIT 1");
$stmtAb->execute();
$about = $stmtAb->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: about_content.php');
        exit;
    }

    if (isset($_POST['save_ceo'])) {
        $title = trim($_POST['ceo_title'] ?? '');
        $subtitle = trim($_POST['ceo_subtitle'] ?? '');
        $content = trim($_POST['ceo_content'] ?? '');
        $img = $ceo['image_url'] ?? 'assets/img/services/s1.png';

        if (!empty($_FILES['ceo_image']['name'])) {
            $up = upload_file($_FILES['ceo_image'], 'about');
            if ($up['success']) {
                $img = $up['url'];
            }
        }

        $stmtUp = $pdo->prepare("UPDATE pages_content SET title = ?, subtitle = ?, content = ?, image_url = ? WHERE section_key = 'ceo_message'");
        $stmtUp->execute([$title, $subtitle, $content, $img]);
        flash_message('success', 'CEO Message updated.');
    }

    if (isset($_POST['save_about'])) {
        $title = trim($_POST['about_title'] ?? '');
        $subtitle = trim($_POST['about_subtitle'] ?? '');
        $content = trim($_POST['about_content'] ?? '');

        $stmtUp = $pdo->prepare("UPDATE pages_content SET title = ?, subtitle = ?, content = ? WHERE section_key = 'company_overview'");
        $stmtUp->execute([$title, $subtitle, $content]);
        flash_message('success', 'Company Overview updated.');
    }

    header('Location: about_content.php');
    exit;
}
?>

<!-- CEO Message -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-person-workspace me-2" style="color: var(--primary);"></i> Message From The CEO</h3>
    </div>
    <div class="card-body">
        <form method="post" action="about_content.php" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="save_ceo" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Heading Title</label>
                    <input type="text" name="ceo_title" class="form-control" value="<?php echo e($ceo['title'] ?? 'Message From The CEO'); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="ceo_subtitle" class="form-control" value="<?php echo e($ceo['subtitle'] ?? 'Our Vision For Your Growth'); ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">CEO Message Narrative</label>
                    <textarea name="ceo_content" class="form-control" rows="6" required><?php echo e($ceo['content'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">CEO Photo / Portrait</label>
                    <?php if (!empty($ceo['image_url'])): ?>
                        <div style="margin-bottom: 8px;">
                            <img src="../<?php echo e($ceo['image_url']); ?>" alt="CEO" style="max-height: 80px; border-radius: 6px; border: 1px solid var(--border);">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="ceo_image" class="form-control" accept="image/*">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;"><i class="bi bi-check-lg"></i> Update CEO Message</button>
        </form>
    </div>
</div>

<!-- Company Overview -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-file-earmark-text me-2" style="color: var(--info);"></i> About Prime Fleet Services - Narrative</h3>
    </div>
    <div class="card-body">
        <form method="post" action="about_content.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="save_about" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Section Title</label>
                    <input type="text" name="about_title" class="form-control" value="<?php echo e($about['title'] ?? 'About Prime Fleet Services'); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="about_subtitle" class="form-control" value="<?php echo e($about['subtitle'] ?? ''); ?>">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Company Story &amp; Operational Overview</label>
                    <textarea name="about_content" class="form-control" rows="6" required><?php echo e($about['content'] ?? ''); ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;"><i class="bi bi-check-lg"></i> Update Company Overview</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
