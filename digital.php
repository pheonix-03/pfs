<?php
$pageTitle = 'Digital Marketing Services';
require_once __DIR__ . '/includes/header.php';

$secDigitalHero = get_page_section('digital', 'hero');

// Fetch digital services from DB
$stmtDig = $pdo->query("SELECT * FROM services WHERE category = 'digital' AND status = 1 ORDER BY sort_order ASC, id ASC");
$digitalServices = $stmtDig ? $stmtDig->fetchAll() : [];
?>
<main class="wrapper" id="main-content">
    <div class="wptb-page-heading" style="background-image: url('<?php echo !empty($secDigitalHero['image_url']) ? e($secDigitalHero['image_url']) : 'assets/img/background/digital-growth-tech.jpg'; ?>');">
        <div class="container">
            <div class="wptb-item--inner">
                <div class="section-badge">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span><?php echo !empty($secDigitalHero['badge']) ? e($secDigitalHero['badge']) : 'Growth & Market Dominance'; ?></span>
                </div>
                <h1 class="wptb-item--title"><?php echo !empty($secDigitalHero['title']) ? e($secDigitalHero['title']) : 'Digital Marketing'; ?></h1>
                <?php if (!empty($secDigitalHero['subtitle'])): ?>
                    <p class="text-white-50 mt-2 mb-0" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;"><?php echo e($secDigitalHero['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="blog-details pd-bottom-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="blog-details-inner">
                        <div class="post-content">
                            <div class="post-header text-center mb-4">
                                <h2 class="post-title fs-1">Elevating Your Digital Footprint</h2>
                                <p class="lead text-white-50 mx-auto" style="max-width: 780px;">
                                    In today's landscape, a strong operational backend needs an equally strong digital presence. While our primary focus remains on delivering top-tier fleet support, we utilize cutting-edge digital marketing strategies to help our partners scale, optimize, and dominate their local markets.
                                </p>
                            </div>

                            <figure class="block-gallery mt-4 mb-5 text-center">
                                <img src="assets/img/digital-services/marketing-overview.jpg" alt="Strategic Marketing Integration" class="rounded-4 shadow-lg img-fluid border border-secondary">
                            </figure>

                            <div class="fulltext">
                                <div class="wptb-heading text-center mb-5">
                                    <div class="section-badge mb-3">
                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                        <span>Accelerate Market Capture</span>
                                    </div>
                                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                                        Digital Growth <span class="text-gradient">&amp; Demand Generation</span>
                                    </h2>
                                    <div class="wptb-item--divider"></div>
                                </div>
                                
                                <div class="row g-4">
                                    <?php if (!empty($digitalServices)): ?>
                                        <?php foreach ($digitalServices as $dig): ?>
                                            <div class="col-lg-6 col-md-12 wow fadeInUp">
                                                <div class="pfs-feature-card h-100 rounded-4 overflow-hidden position-relative">
                                                    <?php if (!empty($dig['image_url'])): ?>
                                                        <div style="overflow: hidden; height: 230px;">
                                                            <img src="<?php echo e($dig['image_url']); ?>" alt="<?php echo e($dig['title']); ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="p-4 p-md-5">
                                                        <span class="badge mb-3" style="background: rgba(37, 99, 235, 0.16); color: var(--pfs-blue-cyan); border: 1px solid rgba(56, 189, 248, 0.35); font-weight: 700; border-radius: 9999px; padding: 6px 14px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                            <?php echo e($dig['slug'] ?? 'Marketing'); ?>
                                                        </span>
                                                        <h4 class="text-white mb-2" style="font-size: 1.35rem; font-weight: 700; letter-spacing: -0.01em;">
                                                            <?php echo e($dig['title']); ?>
                                                        </h4>
                                                        <p class="m-0" style="color: #94a3b8; font-size: 0.94rem; line-height: 1.7;">
                                                            <?php echo e(!empty($dig['short_desc']) ? $dig['short_desc'] : $dig['full_desc']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Static Fallback Cards -->
                                        <div class="col-lg-6 col-md-12 wow fadeInUp">
                                            <div class="pfs-feature-card h-100 rounded-4 overflow-hidden position-relative">
                                                <div style="overflow: hidden; height: 230px;">
                                                    <img src="assets/img/digital-services/seo-dashboard.jpg" alt="SEO" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
                                                </div>
                                                <div class="p-4 p-md-5">
                                                    <span class="badge mb-3" style="background: rgba(37, 99, 235, 0.16); color: var(--pfs-blue-cyan); border: 1px solid rgba(56, 189, 248, 0.35); font-weight: 700; border-radius: 9999px; padding: 6px 14px; text-transform: uppercase; letter-spacing: 0.05em;">SEO</span>
                                                    <h4 class="text-white mb-2" style="font-size: 1.35rem; font-weight: 700;">Search Engine Optimization</h4>
                                                    <p class="m-0" style="color: #94a3b8; font-size: 0.94rem; line-height: 1.7;">Data-driven organic search strategies designed to elevate target keywords, optimize local business authority, and capture high-volume transactional search volume traffic permanently.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 wow fadeInUp">
                                            <div class="pfs-feature-card h-100 rounded-4 overflow-hidden position-relative">
                                                <div style="overflow: hidden; height: 230px;">
                                                    <img src="assets/img/digital-services/meta-advertising.jpg" alt="Paid Media" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
                                                </div>
                                                <div class="p-4 p-md-5">
                                                    <span class="badge mb-3" style="background: rgba(251, 191, 36, 0.14); color: var(--pfs-yellow-primary); border: 1px solid rgba(251, 191, 36, 0.35); font-weight: 700; border-radius: 9999px; padding: 6px 14px; text-transform: uppercase; letter-spacing: 0.05em;">PAID MEDIA</span>
                                                    <h4 class="text-white mb-2" style="font-size: 1.35rem; font-weight: 700;">Meta Ads (Facebook &amp; Instagram)</h4>
                                                    <p class="m-0" style="color: #94a3b8; font-size: 0.94rem; line-height: 1.7;">High-converting social advertising campaigns laser-focused on demographics, behaviors, and retargeting high-intent prospects across Meta platforms.</p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="text-center mt-5 pt-3">
                                    <a href="contact.php" class="btn-two">
                                        <span>Request A Custom Marketing Proposal</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
