<?php
$pageTitle = 'Certificates & Accreditation Manager';
require_once __DIR__ . '/includes/header.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Handle Homepage Section Settings Update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'section_settings') {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: certificates.php');
        exit;
    }

    $badge      = trim($_POST['section_badge'] ?? 'Certified Excellence');
    $title      = trim($_POST['section_title'] ?? 'Accreditations & Compliance Credentials');
    $content    = trim($_POST['section_content'] ?? '');
    $is_visible = isset($_POST['section_visible']) ? 1 : 0;

    // Check if row exists in page_sections
    $chk = $pdo->prepare("SELECT id FROM page_sections WHERE page = 'home' AND section_key = 'certificates_section' LIMIT 1");
    $chk->execute();
    $existingSec = $chk->fetch();

    if ($existingSec) {
        $upd = $pdo->prepare("UPDATE page_sections SET badge = ?, title = ?, content = ?, is_visible = ? WHERE id = ?");
        $upd->execute([$badge, $title, $content, $is_visible, $existingSec['id']]);
    } else {
        $ins = $pdo->prepare("INSERT INTO page_sections (page, section_key, title, badge, content, is_visible, sort_order) VALUES ('home', 'certificates_section', ?, ?, ?, ?, 7)");
        $ins->execute([$title, $badge, $content, $is_visible]);
    }

    flash_message('success', 'Certificates section settings updated successfully.');
    header('Location: certificates.php');
    exit;
}

// 2. Handle Delete Certificate
if ($action === 'delete' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        // Fetch to clean up upload file if exists in uploads/
        $stmt = $pdo->prepare("SELECT image_url FROM certificates WHERE id = ?");
        $stmt->execute([$id]);
        $certToDelete = $stmt->fetch();
        if ($certToDelete && !empty($certToDelete['image_url']) && strpos($certToDelete['image_url'], 'uploads/certificates/') !== false) {
            $filePath = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $certToDelete['image_url']);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $stmtDel = $pdo->prepare("DELETE FROM certificates WHERE id = ?");
        $stmtDel->execute([$id]);
        flash_message('success', 'Certificate removed successfully.');
    }
    header('Location: certificates.php');
    exit;
}

// 3. Handle Status Toggle
if ($action === 'toggle' && $id > 0) {
    if (verify_csrf($_GET['csrf'] ?? '')) {
        $pdo->prepare("UPDATE certificates SET status = (CASE WHEN status = 1 THEN 0 ELSE 1 END) WHERE id = ?")->execute([$id]);
        flash_message('success', 'Certificate visibility status toggled.');
    }
    header('Location: certificates.php');
    exit;
}

// 4. Handle Save Certificate POST (Add / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['form_type']) || $_POST['form_type'] === 'certificate_item')) {
    if (!verify_csrf()) {
        flash_message('error', 'CSRF verification failed.');
        header('Location: certificates.php');
        exit;
    }

    $title              = trim($_POST['title'] ?? '');
    $issuing_authority  = trim($_POST['issuing_authority'] ?? '');
    $certificate_no     = trim($_POST['certificate_no'] ?? '');
    $issue_date         = trim($_POST['issue_date'] ?? '');
    $expiry_date        = trim($_POST['expiry_date'] ?? '');
    $description        = trim($_POST['description'] ?? '');
    $verification_url   = trim($_POST['verification_url'] ?? '');
    $sort_order         = (int)($_POST['sort_order'] ?? 0);
    $status             = isset($_POST['status']) ? 1 : 0;
    $is_featured        = isset($_POST['is_featured']) ? 1 : 0;
    $image_url          = trim($_POST['existing_image'] ?? '');

    // Allow direct custom image URL/path
    if (!empty($_POST['custom_image_url'])) {
        $image_url = trim($_POST['custom_image_url']);
    }

    // Handle new file upload
    if (!empty($_FILES['image_file']['name'])) {
        $upload = upload_file($_FILES['image_file'], 'certificates');
        if ($upload['success']) {
            $image_url = $upload['url'];
        } else {
            flash_message('error', 'Document upload error: ' . $upload['error']);
        }
    }

    if (empty($title)) {
        flash_message('error', 'Please enter a certificate title.');
    } elseif (empty($image_url)) {
        flash_message('error', 'Please provide or upload a certificate image or PDF document.');
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE certificates SET 
                title = ?, 
                issuing_authority = ?, 
                certificate_no = ?, 
                issue_date = ?, 
                expiry_date = ?, 
                description = ?, 
                verification_url = ?, 
                image_url = ?, 
                is_featured = ?, 
                sort_order = ?, 
                status = ? 
                WHERE id = ?");
            $stmt->execute([
                $title, $issuing_authority, $certificate_no, 
                $issue_date, $expiry_date, $description, 
                $verification_url, $image_url, $is_featured, 
                $sort_order, $status, $id
            ]);
            flash_message('success', 'Certificate "' . htmlspecialchars($title) . '" updated successfully.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO certificates (
                title, issuing_authority, certificate_no, 
                issue_date, expiry_date, description, 
                verification_url, image_url, is_featured, 
                sort_order, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title, $issuing_authority, $certificate_no, 
                $issue_date, $expiry_date, $description, 
                $verification_url, $image_url, $is_featured, 
                $sort_order, $status
            ]);
            flash_message('success', 'New certificate "' . htmlspecialchars($title) . '" uploaded and registered.');
        }
        header('Location: certificates.php');
        exit;
    }
}

// Fetch single certificate if editing
$cert = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = ?");
    $stmt->execute([$id]);
    $cert = $stmt->fetch();
    if (!$cert) {
        flash_message('error', 'Certificate not found.');
        header('Location: certificates.php');
        exit;
    }
}

// Fetch all certificates
$certificates = $pdo->query("SELECT * FROM certificates ORDER BY sort_order ASC, id ASC")->fetchAll();

// Fetch Homepage Section Settings
$secStmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = 'home' AND section_key = 'certificates_section' LIMIT 1");
$secStmt->execute();
$certSection = $secStmt->fetch() ?: [
    'badge' => 'Certified Excellence',
    'title' => 'Accreditations & Compliance Credentials',
    'content' => 'Audited and certified by leading quality assurance bodies ensuring strict SLA compliance.',
    'is_visible' => 1
];

// Calculate metrics
$totalCerts = count($certificates);
$activeCerts = count(array_filter($certificates, fn($c) => (int)$c['status'] === 1));
$featuredCerts = count(array_filter($certificates, fn($c) => !empty($c['is_featured'])));
$authorities = array_unique(array_filter(array_map(fn($c) => trim($c['issuing_authority'] ?? ''), $certificates)));
?>

<div style="margin-bottom: 24px;">
    <!-- Top Summary Banner -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-patch-check-fill" style="color: var(--accent);"></i>
                Certificates &amp; Accreditation Manager
            </h2>
            <p style="color: var(--text-muted); margin-top: 4px; font-size: 0.9rem;">
                Full control over compliance documents, ISO accreditations, NLA licenses, image uploads, and homepage presentation.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn btn-secondary" onclick="toggleSectionSettings();">
                <i class="bi bi-gear-fill"></i> Homepage Section Controls
            </button>
            <?php if ($action !== 'add' && $action !== 'edit'): ?>
                <a href="certificates.php?action=add" class="btn btn-primary">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Upload New Certificate
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick KPIs -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
        <div class="card" style="padding: 16px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(59, 130, 246, 0.35); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-files" style="font-size: 1.4rem; color: var(--info);"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Total Registered</span>
                <strong style="font-size: 1.3rem; color: #fff;"><?php echo $totalCerts; ?></strong>
            </div>
        </div>

        <div class="card" style="padding: 16px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(16, 185, 129, 0.35); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-eye-fill" style="font-size: 1.4rem; color: var(--success);"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Active on Website</span>
                <strong style="font-size: 1.3rem; color: #fff;"><?php echo $activeCerts; ?></strong>
            </div>
        </div>

        <div class="card" style="padding: 16px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(251, 191, 36, 0.18); border: 1px solid rgba(251, 191, 36, 0.35); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-star-fill" style="font-size: 1.4rem; color: var(--accent);"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Featured Credentials</span>
                <strong style="font-size: 1.3rem; color: #fff;"><?php echo $featuredCerts; ?></strong>
            </div>
        </div>

        <div class="card" style="padding: 16px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(168, 85, 247, 0.18); border: 1px solid rgba(168, 85, 247, 0.35); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-award-fill" style="font-size: 1.4rem; color: #c084fc;"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Issuing Bodies</span>
                <strong style="font-size: 1.3rem; color: #fff;"><?php echo count($authorities); ?></strong>
            </div>
        </div>
    </div>

    <!-- Homepage Section Settings Drawer / Card -->
    <div id="sectionSettingsPanel" class="card" style="display: none; margin-bottom: 25px; border: 1px solid var(--border-highlight); background: linear-gradient(135deg, rgba(13, 20, 36, 0.95) 0%, rgba(20, 30, 55, 0.95) 100%);">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="color: var(--accent); display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-sliders"></i> Homepage Section Display &amp; CMS Titles
            </h3>
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleSectionSettings();">
                <i class="bi bi-x-lg"></i> Close
            </button>
        </div>
        <div class="card-body">
            <form method="post" action="certificates.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="form_type" value="section_settings">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Section Pill Badge Text</label>
                        <input type="text" name="section_badge" class="form-control" value="<?php echo e($certSection['badge'] ?? 'Certified Excellence'); ?>" placeholder="e.g. Certified Excellence">
                        <small style="color: var(--text-muted); font-size: 0.78rem;">Shown in the pill banner above the main heading</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Main Section Heading Title</label>
                        <input type="text" name="section_title" class="form-control" value="<?php echo e($certSection['title'] ?? 'Accreditations & Compliance Credentials'); ?>" required placeholder="e.g. Accreditations & Compliance Credentials">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Section Subtitle / Description Paragraph</label>
                        <textarea name="section_content" rows="2" class="form-control" placeholder="Brief statement highlighting compliance standards, ISO audits, or NLA membership..."><?php echo e($certSection['content'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                        <input type="checkbox" name="section_visible" id="secVisible" value="1" <?php echo (!empty($certSection['is_visible'])) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                        <label for="secVisible" style="margin: 0; cursor: pointer; color: #fff; font-weight: 500;">
                            Display Certificates Section on Homepage
                        </label>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill"></i> Save Section Settings
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleSectionSettings();">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- =======================================================
         ADD / EDIT CERTIFICATE (FULL CONTROL UPLOAD INTERFACE)
         ======================================================= -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
                <i class="bi <?php echo ($action === 'edit') ? 'bi-pencil-square' : 'bi-cloud-plus-fill'; ?>" style="color: var(--accent);"></i>
                <?php echo ($action === 'edit') ? 'Edit Certificate: ' . e($cert['title']) : 'Upload &amp; Register New Certificate'; ?>
            </h3>
            <a href="certificates.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Certificates</a>
        </div>
        <div class="card-body">
            <form method="post" action="certificates.php<?php echo ($id > 0) ? '?id=' . $id : ''; ?>" enctype="multipart/form-data" id="certUploadForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="form_type" value="certificate_item">
                <input type="hidden" name="existing_image" id="existingImageInput" value="<?php echo e($cert['image_url'] ?? ''); ?>">

                <div class="form-grid">
                    <!-- Title -->
                    <div class="form-group full-width">
                        <label class="form-label" style="font-weight: 600;">Certificate Document Title <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?php echo e($cert['title'] ?? ''); ?>" required placeholder="e.g. NLA Certificate of Compliance 01">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">The public name displayed prominently on the card and modal</small>
                    </div>

                    <!-- Issuing Authority -->
                    <div class="form-group">
                        <label class="form-label">Issuing Authority / Organization</label>
                        <input type="text" name="issuing_authority" class="form-control" value="<?php echo e($cert['issuing_authority'] ?? 'National Limousine Association (NLA)'); ?>" placeholder="e.g. National Limousine Association (NLA) / ISO / DOT">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Organization that verified or issued this document</small>
                    </div>

                    <!-- Certificate Code / Number -->
                    <div class="form-group">
                        <label class="form-label">Certificate Code / ID Number</label>
                        <input type="text" name="certificate_no" class="form-control" value="<?php echo e($cert['certificate_no'] ?? ''); ?>" placeholder="e.g. NLA-01 or ISO-9001-2024">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Unique tracking, accreditation, or license number</small>
                    </div>

                    <!-- Issue Date -->
                    <div class="form-group">
                        <label class="form-label">Date of Issuance</label>
                        <input type="text" name="issue_date" class="form-control" value="<?php echo e($cert['issue_date'] ?? ''); ?>" placeholder="e.g. 2024-01-15 or Jan 2024">
                    </div>

                    <!-- Expiry Date / Validity -->
                    <div class="form-group">
                        <label class="form-label">Validity / Expiration Date</label>
                        <input type="text" name="expiry_date" class="form-control" value="<?php echo e($cert['expiry_date'] ?? ''); ?>" placeholder="e.g. 2026-12-31 or Lifetime / Valid">
                    </div>

                    <!-- DRAG & DROP UPLOAD ZONE -->
                    <div class="form-group full-width">
                        <label class="form-label" style="font-weight: 600;">Certificate Document Image / PDF File <span style="color: var(--danger);">*</span></label>
                        
                        <!-- Upload Container Box -->
                        <div id="dropZone" style="border: 2px dashed var(--border-highlight); border-radius: 12px; padding: 30px 20px; text-align: center; background: rgba(8, 13, 26, 0.6); cursor: pointer; transition: all 0.25s ease; position: relative;">
                            <input type="file" name="image_file" id="fileInput" accept="image/jpeg,image/png,image/webp,image/svg+xml,application/pdf" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 5;">
                            
                            <div id="dropZonePrompt">
                                <div style="width: 58px; height: 58px; border-radius: 50%; background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.4); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto;">
                                    <i class="bi bi-cloud-arrow-up-fill" style="font-size: 1.8rem; color: var(--accent);"></i>
                                </div>
                                <h4 style="font-size: 1.05rem; font-weight: 600; color: #fff; margin-bottom: 6px;">
                                    Drag &amp; drop certificate image or PDF here, or <span style="color: var(--accent); text-decoration: underline;">click to browse</span>
                                </h4>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                                    Supports high-resolution JPG, PNG, WEBP, SVG or PDF (Max 10MB)
                                </p>
                            </div>

                            <!-- Live File Preview Container -->
                            <div id="filePreviewContainer" style="display: none; align-items: center; justify-content: center; flex-direction: column; gap: 12px; margin-top: 10px;">
                                <div id="previewMediaWrapper" style="max-height: 240px; max-width: 380px; overflow: hidden; border-radius: 8px; border: 1px solid var(--border); background: #000; box-shadow: 0 8px 24px rgba(0,0,0,0.6);">
                                    <img id="imagePreview" src="" alt="Preview" style="max-height: 220px; width: auto; object-fit: contain; display: block;">
                                    <div id="pdfPreviewBadge" style="display: none; padding: 40px; text-align: center;">
                                        <i class="bi bi-file-earmark-pdf-fill" style="font-size: 3rem; color: var(--danger);"></i>
                                        <div style="color: #fff; font-weight: 600; margin-top: 8px;">PDF Document Ready</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; font-size: 0.85rem;">
                                    <span id="previewFileName" style="color: #fff; font-weight: 600;"></span>
                                    <span id="previewFileSize" style="color: var(--accent); font-family: monospace;"></span>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnRemovePreview" style="padding: 2px 8px; font-size: 0.75rem;">Change</button>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Image Preview if editing -->
                        <?php if (!empty($cert['image_url'])): ?>
                            <div id="existingImageBlock" style="margin-top: 14px; padding: 14px; background: rgba(13, 20, 36, 0.8); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 70px; height: 50px; background: #000; border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border);">
                                        <?php if (str_ends_with(strtolower($cert['image_url']), '.pdf')): ?>
                                            <i class="bi bi-file-earmark-pdf-fill" style="font-size: 1.8rem; color: var(--danger);"></i>
                                        <?php else: ?>
                                            <img src="../<?php echo e($cert['image_url']); ?>" alt="Current Certificate" style="max-height: 48px; max-width: 68px; object-fit: contain;">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <small style="color: var(--text-muted); display: block;">Currently Assigned File:</small>
                                        <code style="color: var(--accent); font-size: 0.85rem;"><?php echo e($cert['image_url']); ?></code>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <a href="../<?php echo e($cert['image_url']); ?>" target="_blank" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-box-arrow-up-right"></i> View Full File
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Manual Custom URL Override (Optional) -->
                        <div style="margin-top: 12px;">
                            <a href="javascript:void(0);" onclick="toggleManualUrl();" style="color: var(--info); font-size: 0.82rem; text-decoration: none;">
                                <i class="bi bi-link-45deg"></i> Or enter an existing server path / external URL manually
                            </a>
                            <div id="manualUrlField" style="display: none; margin-top: 8px;">
                                <input type="text" name="custom_image_url" class="form-control" placeholder="e.g. assets/img/certificates/iso.jpg or uploads/certificates/..." value="<?php echo e($cert['image_url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Description / Scope -->
                    <div class="form-group full-width">
                        <label class="form-label">Accreditation Scope / Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Describe what this certificate validates (e.g. 24/7 limousine dispatch coordination, strict SOP adherence, Livery Coach & Limo Anywhere platform compliance...)"><?php echo e($cert['description'] ?? ''); ?></textarea>
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Displayed in the certificate preview modal for prospective partners and inspectors</small>
                    </div>

                    <!-- External Verification Portal URL -->
                    <div class="form-group">
                        <label class="form-label">Official Online Verification URL (Optional)</label>
                        <input type="url" name="verification_url" class="form-control" value="<?php echo e($cert['verification_url'] ?? ''); ?>" placeholder="https://registry.example.org/verify/...">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">External registry link to verify validity</small>
                    </div>

                    <!-- Sort Order -->
                    <div class="form-group">
                        <label class="form-label">Display Priority / Sorting Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo e($cert['sort_order'] ?? 0); ?>">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Lower numbers appear first (e.g. 1, 2, 3...)</small>
                    </div>

                    <!-- Checkboxes: Status & Featured -->
                    <div class="form-group full-width" style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 15px; padding: 15px; background: rgba(8, 12, 24, 0.5); border-radius: 8px; border: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="status" id="certStatus" value="1" <?php echo (!isset($cert) || $cert['status'] == 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                            <label for="certStatus" style="margin: 0; cursor: pointer; color: #fff; font-weight: 600;">
                                <i class="bi bi-check2-circle text-success me-1"></i> Active / Published on Website
                            </label>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="is_featured" id="certFeatured" value="1" <?php echo (!empty($cert['is_featured'])) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                            <label for="certFeatured" style="margin: 0; cursor: pointer; color: #fff; font-weight: 600;">
                                <i class="bi bi-star-fill text-warning me-1"></i> Featured Credential (Gold Border &amp; Highlight)
                            </label>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 25px; display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        <i class="bi bi-check-lg"></i> <?php echo ($action === 'edit') ? 'Save Certificate Changes' : 'Upload &amp; Publish Certificate'; ?>
                    </button>
                    <a href="certificates.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- =======================================================
         CERTIFICATES LIST & CONTROL TABLE
         ======================================================= -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-patch-check" style="color: var(--success);"></i>
                Active Credentials &amp; Certifications Directory
            </h3>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="tableFilterInput" class="form-control" placeholder="Search certificates..." style="width: 220px; padding: 6px 12px; font-size: 0.85rem;" onkeyup="filterCertTable();">
                <a href="certificates.php?action=add" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Upload Certificate
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" id="certTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Document</th>
                            <th>Certificate Details</th>
                            <th>Issuing Body</th>
                            <th>Code</th>
                            <th>Validity</th>
                            <th style="text-align: center;">Order</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($certificates)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                    <i class="bi bi-file-earmark-x" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                    No certificates uploaded yet. Click <strong>Upload Certificate</strong> above to add your first credential.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($certificates as $c): 
                                $isPdf = str_ends_with(strtolower($c['image_url']), '.pdf');
                            ?>
                                <tr class="cert-row">
                                    <!-- Document Thumbnail -->
                                    <td style="width: 80px; vertical-align: middle;">
                                        <div style="width: 68px; height: 50px; background: #04060c; border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); position: relative; cursor: pointer;" onclick="openQuickPreview(<?php echo htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8'); ?>);" title="Click to view full preview">
                                            <?php if ($isPdf): ?>
                                                <i class="bi bi-file-earmark-pdf-fill" style="font-size: 1.8rem; color: var(--danger);"></i>
                                            <?php else: ?>
                                                <img src="../<?php echo e($c['image_url']); ?>" alt="Cert" style="max-height: 48px; max-width: 66px; object-fit: contain;">
                                            <?php endif; ?>
                                            <span style="position: absolute; bottom: 2px; right: 2px; background: rgba(0,0,0,0.7); border-radius: 3px; padding: 1px 3px; font-size: 0.65rem; color: #fff;">
                                                <i class="bi bi-zoom-in"></i>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Details -->
                                    <td style="vertical-align: middle;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <strong style="color: #fff; font-size: 0.95rem;"><?php echo e($c['title']); ?></strong>
                                            <?php if (!empty($c['is_featured'])): ?>
                                                <span class="badge" style="background: rgba(251, 191, 36, 0.2); color: var(--accent); border: 1px solid rgba(251, 191, 36, 0.4); font-size: 0.7rem; padding: 2px 6px;">
                                                    <i class="bi bi-star-fill"></i> Featured
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($c['description'])): ?>
                                            <small style="color: var(--text-muted); display: block; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 3px;">
                                                <?php echo e($c['description']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Issuer -->
                                    <td style="vertical-align: middle;">
                                        <span class="badge" style="background: rgba(37, 99, 235, 0.15); color: var(--info); border: 1px solid rgba(59, 130, 246, 0.3); font-size: 0.78rem;">
                                            <?php echo e($c['issuing_authority'] ?: 'Standard Body'); ?>
                                        </span>
                                    </td>

                                    <!-- Code -->
                                    <td style="vertical-align: middle;">
                                        <code style="color: var(--accent); font-weight: 600; font-size: 0.85rem;"><?php echo e($c['certificate_no'] ?: '-'); ?></code>
                                    </td>

                                    <!-- Validity -->
                                    <td style="vertical-align: middle; font-size: 0.85rem;">
                                        <?php if (!empty($c['expiry_date'])): ?>
                                            <span style="color: var(--text);"><?php echo e($c['expiry_date']); ?></span>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted);">Lifetime</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Order -->
                                    <td style="vertical-align: middle; text-align: center; font-weight: 600;">
                                        <?php echo (int)$c['sort_order']; ?>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a href="certificates.php?action=toggle&id=<?php echo $c['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="badge <?php echo ($c['status'] == 1) ? 'badge-success' : 'badge-danger'; ?>" style="text-decoration: none; cursor: pointer;" title="Click to toggle status">
                                            <?php echo ($c['status'] == 1) ? 'Active' : 'Disabled'; ?>
                                        </a>
                                    </td>

                                    <!-- Actions -->
                                    <td style="vertical-align: middle; text-align: right; white-space: nowrap;">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="openQuickPreview(<?php echo htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8'); ?>);" title="Inspect Document">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="certificates.php?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-secondary btn-sm" title="Edit Certificate">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="certificates.php?action=delete&id=<?php echo $c['id']; ?>&csrf=<?php echo csrf_token(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Permanently remove certificate: <?php echo addslashes(e($c['title'])); ?>?');" title="Delete Certificate">
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
<?php endif; ?>

<!-- =======================================================
     QUICK VIEW MODAL (INSPECT CERTIFICATE IN FULL RESOLUTION)
     ======================================================= -->
<div id="quickViewModal" style="display: none; position: fixed; inset: 0; background: rgba(3, 5, 12, 0.85); backdrop-filter: blur(8px); z-index: 10000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #090e1a; border: 1px solid var(--border-highlight); border-radius: 14px; max-width: 800px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.8); display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <h4 id="qvTitle" style="color: #fff; font-size: 1.15rem; font-weight: 700; margin: 0;"></h4>
            <button type="button" onclick="closeQuickPreview();" style="background: none; border: none; color: #fff; font-size: 1.3rem; cursor: pointer;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="padding: 20px; display: flex; flex-direction: column; align-items: center; gap: 20px;">
            <div id="qvMediaWrapper" style="width: 100%; min-height: 260px; max-height: 480px; background: #000; border-radius: 10px; border: 1px solid var(--border); overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 10px;">
                <img id="qvImage" src="" alt="Full Certificate" style="max-height: 460px; max-width: 100%; object-fit: contain; border-radius: 6px;">
                <div id="qvPdfBox" style="display: none; text-align: center; padding: 40px;">
                    <i class="bi bi-file-earmark-pdf-fill" style="font-size: 4rem; color: var(--danger);"></i>
                    <h5 style="color: #fff; margin-top: 10px;">PDF Certificate Document</h5>
                    <p style="color: var(--text-muted); font-size: 0.85rem;">This certificate was uploaded as an official PDF file.</p>
                </div>
            </div>

            <div style="width: 100%; background: rgba(13, 20, 36, 0.7); border: 1px solid var(--border); border-radius: 10px; padding: 15px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 0.88rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block;">Issuing Authority:</span>
                        <strong id="qvAuthority" style="color: var(--info);"></strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block;">Certificate Code / ID:</span>
                        <code id="qvCode" style="color: var(--accent); font-weight: 700;"></code>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block;">Issue &amp; Expiry:</span>
                        <span id="qvDates" style="color: #fff;"></span>
                    </div>
                </div>
                <div id="qvDescWrapper" style="margin-top: 12px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08);">
                    <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">Scope &amp; Accreditation Summary:</span>
                    <p id="qvDesc" style="color: var(--text); font-size: 0.88rem; margin: 4px 0 0 0; line-height: 1.5;"></p>
                </div>
            </div>
        </div>
        <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div id="qvLinks" style="display: flex; gap: 10px;">
                <a id="qvFullBtn" href="#" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bi bi-box-arrow-up-right"></i> Open File in New Tab
                </a>
                <a id="qvVerifyBtn" href="#" target="_blank" class="btn btn-secondary btn-sm" style="display: none;">
                    <i class="bi bi-shield-check"></i> External Verification
                </a>
            </div>
            <div style="display: flex; gap: 10px;">
                <a id="qvEditBtn" href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit This Certificate
                </a>
                <button type="button" onclick="closeQuickPreview();" class="btn btn-secondary btn-sm">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle Section Settings Box
function toggleSectionSettings() {
    var p = document.getElementById('sectionSettingsPanel');
    if (p.style.display === 'none' || p.style.display === '') {
        p.style.display = 'block';
        p.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        p.style.display = 'none';
    }
}

// Toggle Manual URL override field
function toggleManualUrl() {
    var f = document.getElementById('manualUrlField');
    f.style.display = (f.style.display === 'none' || f.style.display === '') ? 'block' : 'none';
}

// Search Filter in Certificates Table
function filterCertTable() {
    var input = document.getElementById('tableFilterInput');
    var filter = input.value.toLowerCase();
    var rows = document.querySelectorAll('#certTable tbody tr.cert-row');

    rows.forEach(function(r) {
        var text = r.textContent.toLowerCase();
        r.style.display = text.indexOf(filter) > -1 ? '' : 'none';
    });
}

// Quick View Modal Handlers
function openQuickPreview(cert) {
    document.getElementById('qvTitle').textContent = cert.title || 'Certificate Document';
    document.getElementById('qvAuthority').textContent = cert.issuing_authority || 'N/A';
    document.getElementById('qvCode').textContent = cert.certificate_no || 'N/A';
    
    var dates = [];
    if (cert.issue_date) dates.push('Issued: ' + cert.issue_date);
    if (cert.expiry_date) dates.push('Expires: ' + cert.expiry_date);
    document.getElementById('qvDates').textContent = dates.length ? dates.join(' | ') : 'Lifetime Validity';

    var descBox = document.getElementById('qvDescWrapper');
    if (cert.description) {
        descBox.style.display = 'block';
        document.getElementById('qvDesc').textContent = cert.description;
    } else {
        descBox.style.display = 'none';
    }

    var imgUrl = '../' + cert.image_url;
    var isPdf = (cert.image_url || '').toLowerCase().endsWith('.pdf');
    var qvImg = document.getElementById('qvImage');
    var qvPdf = document.getElementById('qvPdfBox');

    if (isPdf) {
        qvImg.style.display = 'none';
        qvPdf.style.display = 'block';
    } else {
        qvImg.src = imgUrl;
        qvImg.style.display = 'block';
        qvPdf.style.display = 'none';
    }

    document.getElementById('qvFullBtn').href = imgUrl;

    var verifyBtn = document.getElementById('qvVerifyBtn');
    if (cert.verification_url) {
        verifyBtn.href = cert.verification_url;
        verifyBtn.style.display = 'inline-flex';
    } else {
        verifyBtn.style.display = 'none';
    }

    document.getElementById('qvEditBtn').href = 'certificates.php?action=edit&id=' + cert.id;

    var modal = document.getElementById('quickViewModal');
    modal.style.display = 'flex';
}

function closeQuickPreview() {
    document.getElementById('quickViewModal').style.display = 'none';
}

// Drag & Drop File Upload with Instant Preview
document.addEventListener('DOMContentLoaded', function() {
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var promptBox = document.getElementById('dropZonePrompt');
    var previewContainer = document.getElementById('filePreviewContainer');
    var imgPreview = document.getElementById('imagePreview');
    var pdfBadge = document.getElementById('pdfPreviewBadge');
    var fileNameSpan = document.getElementById('previewFileName');
    var fileSizeSpan = document.getElementById('previewFileSize');
    var removeBtn = document.getElementById('btnRemovePreview');

    if (!dropZone || !fileInput) return;

    ['dragenter', 'dragover'].forEach(function(evt) {
        dropZone.addEventListener(evt, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--accent)';
            dropZone.style.backgroundColor = 'rgba(251, 191, 36, 0.08)';
        });
    });

    ['dragleave', 'drop'].forEach(function(evt) {
        dropZone.addEventListener(evt, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--border-highlight)';
            dropZone.style.backgroundColor = 'rgba(8, 13, 26, 0.6)';
        });
    });

    dropZone.addEventListener('drop', function(e) {
        if (e.dataTransfer && e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelection(fileInput.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files && fileInput.files[0]) {
            handleFileSelection(fileInput.files[0]);
        }
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileInput.value = '';
            previewContainer.style.display = 'none';
            promptBox.style.display = 'block';
        });
    }

    function handleFileSelection(file) {
        if (!file) return;

        fileNameSpan.textContent = file.name;
        fileSizeSpan.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';

        if (file.type === 'application/pdf') {
            imgPreview.style.display = 'none';
            pdfBadge.style.display = 'block';
            promptBox.style.display = 'none';
            previewContainer.style.display = 'flex';
        } else if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
                pdfBadge.style.display = 'none';
                promptBox.style.display = 'none';
                previewContainer.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>