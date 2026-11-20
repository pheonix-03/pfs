<?php
$pageTitle = 'Dashboard Overview';
require_once __DIR__ . '/includes/header.php';

// Fetch statistics
$totalInquiries = (int)$pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$unreadInquiries = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE is_read = 0")->fetchColumn();
$totalServices = (int)$pdo->query("SELECT COUNT(*) FROM services WHERE status = 1")->fetchColumn();
$totalCerts = (int)$pdo->query("SELECT COUNT(*) FROM certificates WHERE status = 1")->fetchColumn();
$totalSubscribers = (int)$pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
$totalSlides = (int)$pdo->query("SELECT COUNT(*) FROM hero_slides WHERE status = 1")->fetchColumn();

// Fetch 5 latest inquiries
$latestInquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 6")->fetchAll();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Inquiries Card -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Total Inquiries</p>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin-top: 5px;"><?php echo $totalInquiries; ?></h3>
                <span style="font-size: 0.8rem; color: <?php echo $unreadInquiries > 0 ? '#f85149' : '#3fb950'; ?>;">
                    <i class="bi bi-circle-fill" style="font-size: 0.5rem; vertical-align: middle;"></i> <?php echo $unreadInquiries; ?> Unread
                </span>
            </div>
            <div style="background: rgba(255,94,20,0.12); color: var(--primary); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="bi bi-envelope-fill"></i>
            </div>
        </div>
    </div>

    <!-- Services Card -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Active Services</p>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin-top: 5px;"><?php echo $totalServices; ?></h3>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Voice, BPO &amp; Digital</span>
            </div>
            <div style="background: rgba(56,139,253,0.12); color: var(--info); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="bi bi-briefcase-fill"></i>
            </div>
        </div>
    </div>

    <!-- Certificates Card -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Certificates</p>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin-top: 5px;"><?php echo $totalCerts; ?></h3>
                <span style="font-size: 0.8rem; color: #3fb950;"><i class="bi bi-check2-circle"></i> NLA Accredited</span>
            </div>
            <div style="background: rgba(35,134,54,0.12); color: var(--success); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
        </div>
    </div>

    <!-- Subscribers Card -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Subscribers</p>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin-top: 5px;"><?php echo $totalSubscribers; ?></h3>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Newsletter Audience</span>
            </div>
            <div style="background: rgba(210,153,34,0.12); color: var(--warning); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-bottom: 30px;">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-lightning-charge-fill me-2" style="color: var(--primary);"></i> Quick Actions</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="services.php?action=add" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add New Service</a>
            <a href="sliders.php?action=add" class="btn btn-secondary"><i class="bi bi-image"></i> Add Hero Slide</a>
            <a href="certificates.php?action=add" class="btn btn-secondary"><i class="bi bi-file-earmark-arrow-up"></i> Upload Certificate</a>
            <a href="settings.php" class="btn btn-secondary"><i class="bi bi-gear"></i> Update Site Details</a>
            <a href="inquiries.php" class="btn btn-secondary"><i class="bi bi-inbox"></i> View Inquiries Inbox</a>
        </div>
    </div>
</div>

<!-- Latest Inquiries -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-envelope-open-fill me-2" style="color: var(--info);"></i> Recent Inquiries</h3>
        <a href="inquiries.php" class="btn btn-secondary btn-sm">View All Inquiries</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestInquiries)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No inquiries received yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($latestInquiries as $inq): ?>
                            <tr>
                                <td>
                                    <?php if ($inq['is_read'] == 0): ?>
                                        <span class="badge badge-danger">Unread</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 600; color: #fff;"><?php echo e($inq['name']); ?></td>
                                <td><a href="mailto:<?php echo e($inq['email']); ?>" style="color: var(--info); text-decoration: none;"><?php echo e($inq['email']); ?></a></td>
                                <td><?php echo e($inq['phone'] ?: '-'); ?></td>
                                <td><?php echo e(mb_strimwidth($inq['subject'] ?: 'General Inquiry', 0, 30, '...')); ?></td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo date('M d, Y H:i', strtotime($inq['created_at'])); ?></td>
                                <td style="text-align: right;">
                                    <a href="inquiries.php?view=<?php echo $inq['id']; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-eye"></i> View</a>
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
