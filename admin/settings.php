<?php
$pageTitle = 'Global Site Settings';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        flash_message('error', 'Security check failed. Please refresh and try again.');
        header('Location: settings.php');
        exit;
    }

    $textFields = [
        'site_name', 'tagline', 'meta_description',
        'phone_in', 'phone_us', 'email_support', 'email_info',
        'address', 'working_hours',
        'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url', 'reddit_url', 'linkedin_url',
        'whatsapp_number', 'whatsapp_message', 'footer_text', 'copyright_text'
    ];

    foreach ($textFields as $field) {
        if (isset($_POST[$field])) {
            update_setting($field, trim($_POST[$field]));
        }
    }

    // Handle Logo Upload
    if (!empty($_FILES['logo_file']['name'])) {
        $upload = upload_file($_FILES['logo_file'], 'branding');
        if ($upload['success']) {
            update_setting('logo_url', $upload['url']);
        } else {
            flash_message('error', 'Logo upload error: ' . $upload['error']);
        }
    }

    // Handle Favicon Upload
    if (!empty($_FILES['favicon_file']['name'])) {
        $upload = upload_file($_FILES['favicon_file'], 'branding');
        if ($upload['success']) {
            update_setting('favicon_url', $upload['url']);
        } else {
            flash_message('error', 'Favicon upload error: ' . $upload['error']);
        }
    }

    flash_message('success', 'Site settings successfully updated!');
    header('Location: settings.php');
    exit;
}

$logoUrl = get_setting('logo_url', 'assets/img/logo.png');
$faviconUrl = get_setting('favicon_url', 'assets/img/favicon.png');
?>

<form method="post" action="settings.php" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <!-- Branding & Identity -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-badge-ad me-2" style="color: var(--primary);"></i> Identity &amp; Branding</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Website Name</label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo e(get_setting('site_name')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tagline / Slogan</label>
                    <input type="text" name="tagline" class="form-control" value="<?php echo e(get_setting('tagline')); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Main Logo</label>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 8px;">
                        <img src="../<?php echo e($logoUrl); ?>" alt="Logo Preview" style="max-height: 40px; background: #21262d; padding: 4px 8px; border-radius: 4px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo e($logoUrl); ?></span>
                    </div>
                    <input type="file" name="logo_file" class="form-control" accept="image/*">
                    <p class="form-hint">Recommended format: PNG or SVG with transparent background.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Favicon</label>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 8px;">
                        <img src="../<?php echo e($faviconUrl); ?>" alt="Favicon Preview" style="max-height: 32px; background: #21262d; padding: 4px; border-radius: 4px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo e($faviconUrl); ?></span>
                    </div>
                    <input type="file" name="favicon_file" class="form-control" accept="image/png,image/x-icon,image/svg+xml">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">SEO Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?php echo e(get_setting('meta_description')); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Details -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-telephone-fill me-2" style="color: var(--info);"></i> Contact &amp; Office Information</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">India Support Phone</label>
                    <input type="text" name="phone_in" class="form-control" value="<?php echo e(get_setting('phone_in')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">US Direct Phone</label>
                    <input type="text" name="phone_us" class="form-control" value="<?php echo e(get_setting('phone_us')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Support Email Address</label>
                    <input type="email" name="email_support" class="form-control" value="<?php echo e(get_setting('email_support')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Inquiry / Info Email Address</label>
                    <input type="email" name="email_info" class="form-control" value="<?php echo e(get_setting('email_info')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Working Hours</label>
                    <input type="text" name="working_hours" class="form-control" value="<?php echo e(get_setting('working_hours')); ?>" placeholder="e.g. 24 X 7 Live">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Physical Office / Workshop Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo e(get_setting('address')); ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp & Social Links -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-share-fill me-2" style="color: var(--success);"></i> Social Channels &amp; WhatsApp</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">WhatsApp Number (with country code)</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="<?php echo e(get_setting('whatsapp_number')); ?>" placeholder="919679897979">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp Pre-filled Message</label>
                    <input type="text" name="whatsapp_message" class="form-control" value="<?php echo e(get_setting('whatsapp_message')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook Profile URL</label>
                    <input type="url" name="facebook_url" class="form-control" value="<?php echo e(get_setting('facebook_url')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram Profile URL</label>
                    <input type="url" name="instagram_url" class="form-control" value="<?php echo e(get_setting('instagram_url')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter / X URL</label>
                    <input type="url" name="twitter_url" class="form-control" value="<?php echo e(get_setting('twitter_url')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">YouTube Channel URL</label>
                    <input type="url" name="youtube_url" class="form-control" value="<?php echo e(get_setting('youtube_url')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Reddit Profile URL</label>
                    <input type="url" name="reddit_url" class="form-control" value="<?php echo e(get_setting('reddit_url')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn Page URL</label>
                    <input type="url" name="linkedin_url" class="form-control" value="<?php echo e(get_setting('linkedin_url')); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Content -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-card-text me-2" style="color: var(--warning);"></i> Footer &amp; About Drawer Text</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Company Overview Snippet (Sidebar Drawer)</label>
                <textarea name="footer_text" class="form-control" rows="3"><?php echo e(get_setting('footer_text')); ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Copyright Notice</label>
                <input type="text" name="copyright_text" class="form-control" value="<?php echo e(get_setting('copyright_text')); ?>">
            </div>
        </div>
    </div>

    <div style="margin-bottom: 40px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem;">
            <i class="bi bi-check2-circle"></i> Save All Site Settings
        </button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
