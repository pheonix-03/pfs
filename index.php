<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

// Fetch dynamic hero slides
$stmtSlide = $pdo->query("SELECT * FROM hero_slides WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$heroSlides = $stmtSlide->fetchAll();

// Global slider settings
$sliderAutoplay = get_setting('slider_autoplay', '1');
$sliderDelay    = get_setting('slider_delay', '5000');
$sliderEffect   = get_setting('slider_effect', 'slide');

// Fetch active BPO services
$stmtSrv = $pdo->query("SELECT * FROM services WHERE category = 'bpo' AND status = 1 ORDER BY sort_order ASC, id ASC");
$bpoServices = $stmtSrv->fetchAll();

// Fetch statistics & KPIs
$stmtStats = $pdo->query("SELECT * FROM statistics ORDER BY sort_order ASC, id ASC");
$statistics = $stmtStats->fetchAll();

// Fetch why choose us points
$stmtWhy = $pdo->query("SELECT * FROM why_choose_us ORDER BY sort_order ASC, id ASC");
$whyPoints = $stmtWhy->fetchAll();

// Fetch challenges & solutions
$stmtChall = $pdo->query("SELECT * FROM challenges_solutions WHERE type = 'challenge' ORDER BY sort_order ASC");
$challenges = $stmtChall->fetchAll();
$stmtSol = $pdo->query("SELECT * FROM challenges_solutions WHERE type = 'solution' ORDER BY sort_order ASC");
$solutions = $stmtSol->fetchAll();

// Fetch certificates
$stmtCert = $pdo->query("SELECT * FROM certificates WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$certificates = $stmtCert->fetchAll();

// Fetch CEO message
$stmtCEO = $pdo->prepare("SELECT * FROM pages_content WHERE section_key = 'ceo_message' LIMIT 1");
$stmtCEO->execute();
$ceoMessage = $stmtCEO->fetch();

// Fetch Testimonials
$stmtTest = $pdo->query("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$testimonialsList = $stmtTest->fetchAll();
?>

<main class="wrapper" id="main-content">

    <!-- 1. MODERN SPLIT HTML HERO SECTION (PFS ORIGINAL CONTENT) -->
    <?php if (is_section_visible('home', 'hero_slider')): 
        $heroSec = get_page_section('home', 'hero_slider');
        $primarySlide = !empty($heroSlides) ? $heroSlides[0] : null;
        
        $heroBadge     = !empty($heroSec['badge']) ? $heroSec['badge'] : (!empty($primarySlide['badge']) ? $primarySlide['badge'] : 'BPO SOLUTIONS THAT DRIVE SUCCESS');
        $heroBadgeIcon = !empty($primarySlide['badge_icon']) ? $primarySlide['badge_icon'] : 'bi bi-stars';
        $heroTitle     = !empty($heroSec['title']) ? $heroSec['title'] : (!empty($primarySlide['title']) ? $primarySlide['title'] : 'Your Journey. Our Commitment.');
        $heroSubtitle  = !empty($heroSec['subtitle']) ? $heroSec['subtitle'] : (!empty($primarySlide['subtitle']) ? $primarySlide['subtitle'] : 'How we help your business thrive globally');
        $heroBtn1Text  = !empty($heroSec['btn_text']) ? $heroSec['btn_text'] : (!empty($primarySlide['btn_text']) ? $primarySlide['btn_text'] : 'Our Services');
        $heroBtn1Url   = !empty($heroSec['btn_url']) ? $heroSec['btn_url'] : (!empty($primarySlide['btn_url']) ? $primarySlide['btn_url'] : 'services.php');
        $heroBtn2Text  = !empty($primarySlide['btn2_text']) ? $primarySlide['btn2_text'] : 'Contact Us';
        $heroBtn2Url   = !empty($primarySlide['btn2_url']) ? $primarySlide['btn2_url'] : 'contact.php';
        $heroImg       = !empty($heroSec['image_url']) ? $heroSec['image_url'] : (!empty($primarySlide['bg_image']) ? $primarySlide['bg_image'] : 'assets/img/slider/slide-1.jpg');
    ?>
    <section class="section-hero">
        <div class="container">
            <div class="responsive-grid-hero">
                <div>
                    <div class="section-badge">
                        <i class="<?php echo e($heroBadgeIcon); ?>"></i>
                        <span><?php echo e($heroBadge); ?></span>
                    </div>
                    <h1 class="hero-main-title">
                        <?php 
                            $titleClean = trim($heroTitle);
                            if (strpos($titleClean, '.') !== false) {
                                $parts = explode('.', $titleClean, 2);
                                $firstPart = e(trim($parts[0]));
                                $firstPart = preg_replace('/\b(Dispatch)\b/i', '<span class="text-gold">$1</span>', $firstPart);
                                echo $firstPart . '.<br><span class="text-gradient">' . e(trim($parts[1])) . '</span>';
                            } else {
                                echo nl2br(e($heroTitle));
                            }
                        ?>
                    </h1>
                    <p class="hero-lead-text">
                        <?php echo e($heroSubtitle); ?>
                    </p>

                    <!-- CTA Buttons -->
                    <div class="hero-cta-group">
                        <?php if (!empty($heroBtn1Text)): ?>
                        <a href="<?php echo e($heroBtn1Url); ?>" class="btn-two">
                            <span><?php echo e($heroBtn1Text); ?></span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($heroBtn2Text)): ?>
                        <a href="<?php echo e($heroBtn2Url); ?>" class="btn-three">
                            <span><?php echo e($heroBtn2Text); ?></span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- 4 Reference Pillars (Fast, Real-Time, Reliable, 24/7 Support) -->
                    <div class="hero-reference-pillars mt-4 pt-2 d-flex flex-wrap align-items-center gap-3">
                        <div class="pfs-pillar-item d-flex align-items-center gap-2">
                            <i class="bi bi-truck text-gold fs-5"></i>
                            <span class="pillar-label text-white fw-bold" style="font-size: 0.82rem; letter-spacing: 0.02em;">Fast &amp; On-Time Dispatch</span>
                        </div>
                        <div class="pfs-pillar-item d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-gold fs-5"></i>
                            <span class="pillar-label text-white fw-bold" style="font-size: 0.82rem; letter-spacing: 0.02em;">Real-Time Tracking</span>
                        </div>
                        <div class="pfs-pillar-item d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-gold fs-5"></i>
                            <span class="pillar-label text-white fw-bold" style="font-size: 0.82rem; letter-spacing: 0.02em;">Reliable &amp; Secure</span>
                        </div>
                        <div class="pfs-pillar-item d-flex align-items-center gap-2">
                            <i class="bi bi-headset text-gold fs-5"></i>
                            <span class="pillar-label text-white fw-bold" style="font-size: 0.82rem; letter-spacing: 0.02em;">24/7 Dedicated Support</span>
                        </div>
                    </div>
                </div>

                <!-- Right Photo Showcase -->
                <div class="hero-image-wrapper">
                    <div class="hero-image-frame">
                        <img src="<?php echo e($heroImg); ?>" alt="Prime Fleet Services" class="img-fluid">
                    </div>
                    
                    <!-- Floating Stat Pill using PFS's genuine 24/7 Live Support -->
                    <div class="hero-floating-pill">
                        <div class="d-flex align-items-center gap-3">
                            <div class="hero-floating-stat">24/7</div>
                            <div class="hero-floating-label">Live Operational Support Worldwide</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 2. BPO SERVICES GRID SECTION -->
    <?php if (is_section_visible('home', 'services_grid')): 
        $secServices = get_page_section('home', 'services_grid');
    ?>
    <section class="wptb-service-one pt-4 pb-5 position-relative">
        <div class="container pt-3 pb-4 pfs-services-container">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span><?php echo e($secServices['badge'] ?: 'How We Help Your Business'); ?></span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        Your Journey. <span class="text-gradient">Our Commitment.</span>
                    </h2>
                    <p class="text-muted mx-auto mt-2" style="max-width: 720px; font-size: 1.02rem; line-height: 1.7;">
                        <?php echo (!empty($secServices['content']) && strtolower(trim($secServices['content'])) !== 'view all services') 
                            ? e($secServices['content']) 
                            : 'Scalable 24/7 limousine dispatch, reservation management, chauffeur coordination, and omnichannel customer support engineered for premium transportation brands.'; ?>
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <?php 
                $serviceBadges = [
                    1 => '24/7 Inbound & Outbound',
                    2 => 'Omnichannel Chat & Email',
                    3 => 'Dedicated Domain Specialists',
                    4 => 'Six Sigma QA & Calibration',
                    5 => 'Flexible Enterprise Staffing',
                    6 => 'Data-Driven KPIs & Analytics'
                ];
                foreach ($bpoServices as $srv): 
                    $badgeText = $serviceBadges[$srv['sort_order']] ?? 'Enterprise BPO';
                    $serviceImg = !empty($srv['image_url']) ? $srv['image_url'] : 'assets/img/services/srv_voice_support.jpg';
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="wptb-item--inner p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="pfs-service-card-thumb mb-3 overflow-hidden rounded-3 position-relative">
                                    <a href="services.php" class="d-block overflow-hidden">
                                        <img src="<?php echo e($serviceImg); ?>" alt="<?php echo e($srv['title']); ?>" class="w-100 h-100" loading="lazy">
                                    </a>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
                                    <span class="pfs-service-badge" title="<?php echo e($badgeText); ?>">
                                        <i class="<?php echo e($srv['icon_class'] ?: 'bi bi-patch-check'); ?> me-1"></i>
                                        <?php echo e($badgeText); ?>
                                    </span>
                                    <span class="pfs-service-order">#0<?php echo (int)$srv['sort_order']; ?></span>
                                </div>
                                <h4 class="wptb-item--title mb-2">
                                    <a href="services.php"><?php echo e($srv['title']); ?></a>
                                </h4>
                                <p class="wptb-item--description" style="color: #94a3b8; font-size: 0.92rem; line-height: 1.6;">
                                    <?php echo e($srv['short_desc']); ?>
                                </p>
                            </div>
                            <div class="mt-4 pt-3 pfs-service-explore-wrap">
                                <a href="services.php" class="pfs-service-explore-link">
                                    <span>Explore Service</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 3. SLA PERFORMANCE & KEY METRICS COUNTER -->
    <?php if (is_section_visible('home', 'marquee_model')): 
        $secModel = get_page_section('home', 'marquee_model');
    ?>
    <section class="wptb-funfacts-one py-5 position-relative">
        <div class="container py-2">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="wptb-counter1 style1">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--holder">
                                <div class="wptb-item--value">0<span class="suffix">%</span></div>
                                <div class="wptb-item--text">Downtime Commitment</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="wptb-counter1 style1">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--holder">
                                <div class="wptb-item--value">99.9<span class="suffix">%</span></div>
                                <div class="wptb-item--text">High SLA Availability</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="wptb-counter1 style1">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--holder">
                                <div class="wptb-item--value">24<span class="suffix">/7</span></div>
                                <div class="wptb-item--text">Live Chauffeur Dispatch</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="wptb-counter1 style1">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--holder">
                                <div class="wptb-item--value">100<span class="suffix">%</span></div>
                                <div class="wptb-item--text">Dedicated Quality Control</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. ABOUT PRIME FLEET & CEO MESSAGE -->
    <section class="wptb-about-company-one py-5 position-relative">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="wptb-heading">
                        <div class="wptb-item--inner">
                            <h6 class="wptb-item--subtitle">
                                <i class="bi bi-shield-check me-1"></i>
                                About Prime Fleet Services
                            </h6>
                            <h2 class="wptb-item--title">Leading Global BPO for Limousine & Transportation Networks</h2>
                            <p class="wptb-item--description mt-3">
                                <?php echo !empty($ceoMessage['content']) ? nl2br(e($ceoMessage['content'])) : 'Prime Fleet Services is a premier Business Process Outsourcing company specializing in round-the-clock limousine and chauffeur dispatch, live call handling, customer service, and digital solutions for luxury transport operators across North America and Europe.'; ?>
                            </p>
                            <div class="mt-4 pt-2 d-flex flex-wrap gap-3">
                                <a href="about.php" class="btn-two">
                                    <span>Company Profile</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="contact.php" class="btn-three">
                                    <span>Schedule a Call</span>
                                    <i class="bi bi-telephone"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 p-md-5 rounded-4 pfs-commitment-card">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="pfs-commitment-icon">
                                <i class="bi bi-quote"></i>
                            </div>
                            <div>
                                <h5 class="pfs-commitment-title">Executive Commitment</h5>
                                <span class="pfs-commitment-subtitle">Zero Compromise on Quality & Speed</span>
                            </div>
                        </div>
                        <p class="pfs-commitment-quote">
                            "Our mission is simple: to make fleet operators in the United States and globally completely confident that every call, every reservation, and every dispatch requirement is handled flawlessly 24 hours a day, 365 days a year."
                        </p>
                        <div class="mt-4 pt-3 d-flex align-items-center justify-content-between pfs-commitment-divider">
                            <div>
                                <strong class="pfs-commitment-leader">Prime Fleet Services Leadership</strong>
                                <small style="color: var(--pfs-yellow-primary); font-weight: 600;">Asansol | Kolkata | USA Operations</small>
                            </div>
                            <span class="badge pfs-iso-badge">ISO 9001:2015</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. WHY CHOOSE US -->
    <?php if (!empty($whyPoints)): 
        $secWhy = get_page_section('home', 'why_choose_us');
        $whyBgImg = !empty($secWhy['image_url']) ? $secWhy['image_url'] : 'assets/img/background/bg-why-choose.jpg';
    ?>
    <section class="wptb-why-choose-one py-5 position-relative">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-award-fill"></i>
                        <span>Why Global Fleets Trust Us</span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        Built for <span class="text-gradient">Reliability, Scale & Precision</span>
                    </h2>
                    <p class="pfs-section-subtitle mt-2 mx-auto" style="max-width: 680px; font-size: 0.98rem; line-height: 1.6;">
                        High-availability operations backed by enterprise infrastructure, rigorous training, and strict SOP compliance.
                    </p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach ($whyPoints as $why): 
                    $cardIcon = !empty($why['icon']) ? $why['icon'] : (!empty($why['icon_class']) ? $why['icon_class'] : 'bi bi-check-circle');
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="pfs-feature-card h-100 p-4 rounded-4 position-relative overflow-hidden text-center">
                            <div class="wptb-item--icon mx-auto mb-3">
                                <i class="<?php echo e($cardIcon); ?>"></i>
                            </div>
                            <h4 class="wptb-item--title mb-2" style="font-size: 1.15rem; font-weight: 700; letter-spacing: -0.01em;">
                                <?php echo e($why['title']); ?>
                            </h4>
                            <p class="wptb-item--description m-0" style="font-size: 0.92rem; line-height: 1.65;">
                                <?php echo e($why['description']); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 6. CHALLENGES VS SOLUTIONS SPLIT CARDS -->
    <section class="wptb-service-two py-5 position-relative">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <h6 class="wptb-item--subtitle">
                        <i class="bi bi-sliders me-1"></i>
                        Operational Contrast
                    </h6>
                    <h2 class="wptb-item--title">Traditional Roadblocks vs. The PFS Advantage</h2>
                </div>
            </div>
            <div class="row g-4">
                <!-- Left: Challenges -->
                <div class="col-lg-6">
                    <div class="challenge-card-dark h-100">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(239, 68, 68, 0.15); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-octagon-fill" style="font-size: 1.3rem; color: #f87171;"></i>
                            </div>
                            <div>
                                <h3 class="m-0" style="font-size: 1.3rem;">Common Fleet Challenges</h3>
                                <small style="color: #94a3b8;">Bottlenecks facing growing transportation operators</small>
                            </div>
                        </div>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <?php if (!empty($challenges)): ?>
                                <?php foreach ($challenges as $c): ?>
                                    <li class="d-flex align-items-start gap-3">
                                        <i class="bi bi-x-circle-fill text-danger mt-1"></i>
                                        <div>
                                            <strong class="text-white d-block"><?php echo e($c['title']); ?></strong>
                                            <span style="color: #94a3b8; font-size: 0.9rem;"><?php echo e($c['description']); ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="d-flex align-items-start gap-3">
                                    <i class="bi bi-x-circle-fill text-danger mt-1"></i>
                                    <div>
                                        <strong class="text-white d-block">High Chauffeur & Dispatch Turnover</strong>
                                        <span style="color: #94a3b8; font-size: 0.9rem;">Excessive recruitment costs and frequent missed trips during night shifts.</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start gap-3">
                                    <i class="bi bi-x-circle-fill text-danger mt-1"></i>
                                    <div>
                                        <strong class="text-white d-block">Inconsistent SLA Resolution</strong>
                                        <span style="color: #94a3b8; font-size: 0.9rem;">Delayed customer follow-ups and uncoordinated flight tracking lead to lost VIP accounts.</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Right: PFS Solutions -->
                <div class="col-lg-6">
                    <div class="solution-card-dark h-100">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid rgba(59, 130, 246, 0.3);">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(37, 99, 235, 0.25); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check2-circle" style="font-size: 1.4rem; color: var(--pfs-yellow-primary);"></i>
                            </div>
                            <div>
                                <h3 class="m-0" style="font-size: 1.3rem;">The Prime Fleet Solution</h3>
                                <small style="color: var(--pfs-blue-cyan);">Dedicated 24/7 limousine dispatch coordination</small>
                            </div>
                        </div>
                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                            <?php if (!empty($solutions)): ?>
                                <?php foreach ($solutions as $s): ?>
                                    <li class="d-flex align-items-start gap-3">
                                        <i class="bi bi-check-circle-fill mt-1" style="color: var(--pfs-yellow-primary);"></i>
                                        <div>
                                            <strong class="text-white d-block"><?php echo e($s['title']); ?></strong>
                                            <span style="color: #cbd5e1; font-size: 0.9rem;"><?php echo e($s['description']); ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="d-flex align-items-start gap-3">
                                    <i class="bi bi-check-circle-fill mt-1" style="color: var(--pfs-yellow-primary);"></i>
                                    <div>
                                        <strong class="text-white d-block">Seamless 24/7 Global Dispatchers</strong>
                                        <span style="color: #cbd5e1; font-size: 0.9rem;">Trained dispatch specialists on Livery Coach, Limo Anywhere, Santa Cruz, and FASTTRAK.</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start gap-3">
                                    <i class="bi bi-check-circle-fill mt-1" style="color: var(--pfs-yellow-primary);"></i>
                                    <div>
                                        <strong class="text-white d-block">DMAIC & Six Sigma Audited Quality</strong>
                                        <span style="color: #cbd5e1; font-size: 0.9rem;">Rigorous call recording reviews, real-time live flight radar monitoring, and 100% on-time dispatch.</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. OFFICIAL ACCREDITATIONS & CERTIFICATES -->
    <!-- 7. OFFICIAL ACCREDITATIONS & CERTIFICATES -->
    <?php 
    $certSec = get_page_section('home', 'certificates_section');
    $isCertVisible = is_section_visible('home', 'certificates_section');
    if ($isCertVisible && !empty($certificates)): 
        $secBadge = !empty($certSec['badge']) ? $certSec['badge'] : 'Certified Excellence';
        $secTitle = !empty($certSec['title']) ? $certSec['title'] : 'Accreditations & Compliance Credentials';
        $secDesc  = !empty($certSec['content']) ? $certSec['content'] : 'Audited and certified by leading quality assurance bodies ensuring strict SLA compliance.';
    ?>
    <section class="wptb-product-categories-one py-5 position-relative">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-patch-check-fill"></i>
                        <span><?php echo e($secBadge); ?></span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        <?php echo nl2br(e($secTitle)); ?>
                    </h2>
                    <?php if (!empty($secDesc)): ?>
                        <p class="text-white-50 mt-2 mx-auto" style="max-width: 680px; font-size: 0.98rem; line-height: 1.6;">
                            <?php echo e($secDesc); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php 
                $colClass = count($certificates) === 1 ? 'col-lg-6 col-md-8 mx-auto' : (count($certificates) === 2 ? 'col-md-6' : 'col-xl-4 col-md-6');
                foreach ($certificates as $cert): 
                    $isPdf = str_ends_with(strtolower($cert['image_url']), '.pdf');
                    $authority = !empty($cert['issuing_authority']) ? $cert['issuing_authority'] : 'National Limousine Association (NLA)';
                    $code = !empty($cert['certificate_no']) ? $cert['certificate_no'] : '';
                ?>
                    <div class="<?php echo $colClass; ?>">
                        <div class="pfs-certificate-card" onclick="openCertLightbox(<?php echo htmlspecialchars(json_encode($cert), ENT_QUOTES, 'UTF-8'); ?>);" role="button" tabindex="0">
                            <div>
                                <div class="pfs-cert-thumb-box">
                                    <?php if ($isPdf): ?>
                                        <div class="text-center p-3">
                                            <i class="bi bi-file-earmark-pdf-fill" style="font-size: 3.2rem; color: #ef4444;"></i>
                                            <div style="font-size: 0.8rem; color: #fff; font-weight: 600; margin-top: 4px;">PDF Document</div>
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo e($cert['image_url']); ?>" alt="<?php echo e($cert['title']); ?>" class="pfs-cert-thumb-img" loading="lazy">
                                    <?php endif; ?>
                                    <div class="pfs-cert-hover-overlay">
                                        <i class="bi bi-zoom-in"></i>
                                        <span>Click to Inspect Document</span>
                                    </div>
                                </div>

                                <span class="pfs-cert-badge-authority">
                                    <i class="bi bi-patch-check-fill me-1"></i>
                                    <?php echo e($authority); ?>
                                </span>

                                <h4 class="pfs-cert-card-title"><?php echo e($cert['title']); ?></h4>

                                <?php if (!empty($cert['description'])): ?>
                                    <p class="pfs-cert-card-desc"><?php echo e($cert['description']); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="pfs-cert-card-footer">
                                <?php if (!empty($code)): ?>
                                    <span class="pfs-cert-card-code">Code: <?php echo e($code); ?></span>
                                <?php else: ?>
                                    <span style="font-size: 0.78rem; color: var(--pfs-muted-color);">Official Accreditation</span>
                                <?php endif; ?>
                                <span class="pfs-cert-inspect-link">
                                    View Proof <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Certificate Inspection Lightbox Modal -->
    <div id="pfsCertModal" class="pfs-lightbox-modal" onclick="if(event.target===this) closeCertLightbox();" role="dialog" aria-modal="true" aria-labelledby="modalCertTitle">
        <div class="pfs-lightbox-dialog">
            <div class="p-3 p-md-4 d-flex align-items-center justify-content-between border-bottom" style="border-color: var(--pfs-glass-border) !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-patch-check-fill" style="color: var(--pfs-yellow-primary); font-size: 1.3rem;"></i>
                    <h5 id="modalCertTitle" class="m-0 font-weight-bold" style="color: var(--pfs-heading-color); font-size: 1.2rem;"></h5>
                </div>
                <button type="button" class="btn-close" onclick="closeCertLightbox();" aria-label="Close" style="filter: var(--pfs-close-filter, invert(1)); opacity: 0.8;"></button>
            </div>
            
            <div class="p-3 p-md-4">
                <div class="text-center p-2 mb-4 rounded-3" style="background: #000; border: 1px solid rgba(59, 130, 246, 0.25); min-height: 280px; max-height: 520px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img id="modalCertImage" src="" alt="Certificate Proof" style="max-height: 480px; max-width: 100%; object-fit: contain; border-radius: 6px;">
                    <div id="modalCertPdf" style="display: none; padding: 40px; text-align: center;">
                        <i class="bi bi-file-earmark-pdf-fill" style="font-size: 4.5rem; color: #ef4444;"></i>
                        <h4 class="text-white mt-3">PDF Accreditation Document</h4>
                        <p style="color: #94a3b8;">This official certificate document is stored in PDF format.</p>
                    </div>
                </div>

                <div class="p-3 rounded-3 mb-3" style="background: var(--pfs-glass-bg); border: 1px solid var(--pfs-glass-border);">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <small class="d-block" style="color: var(--pfs-muted-color); font-size: 0.78rem;">Issuing Body</small>
                            <strong id="modalCertAuthority" style="color: var(--pfs-blue-cyan); font-size: 0.95rem;"></strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="d-block" style="color: var(--pfs-muted-color); font-size: 0.78rem;">Accreditation Code</small>
                            <code id="modalCertCode" style="color: var(--pfs-yellow-primary); font-size: 0.92rem; font-weight: 700;"></code>
                        </div>
                        <div class="col-sm-4">
                            <small class="d-block" style="color: var(--pfs-muted-color); font-size: 0.78rem;">Validity Status</small>
                            <span id="modalCertValidity" style="color: var(--pfs-heading-color); font-weight: 600; font-size: 0.9rem;"></span>
                        </div>
                    </div>
                    <div id="modalCertDescBox" class="mt-3 pt-3 border-top" style="border-color: var(--pfs-glass-border) !important;">
                        <small class="d-block mb-1" style="color: var(--pfs-muted-color); font-size: 0.78rem;">Accreditation Scope</small>
                        <p id="modalCertDesc" class="m-0" style="color: var(--pfs-body-color); font-size: 0.9rem; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>

            <div class="p-3 p-md-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: var(--pfs-glass-border) !important;">
                <div class="d-flex gap-2">
                    <a id="modalCertOpenBtn" href="#" target="_blank" class="btn-two btn-sm" style="padding: 8px 18px; font-size: 0.85rem;">
                        <span>Open Full Size</span>
                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                    <a id="modalCertVerifyBtn" href="#" target="_blank" class="btn-three btn-sm" style="padding: 8px 18px; font-size: 0.85rem; display: none;">
                        <span>External Verification</span>
                        <i class="bi bi-shield-check ms-1"></i>
                    </a>
                </div>
                <button type="button" class="btn-three btn-sm" onclick="closeCertLightbox();" style="padding: 8px 18px; font-size: 0.85rem;">
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>

    <script>
    function openCertLightbox(cert) {
        document.getElementById('modalCertTitle').textContent = cert.title || 'Official Certificate';
        document.getElementById('modalCertAuthority').textContent = cert.issuing_authority || 'National Limousine Association (NLA)';
        document.getElementById('modalCertCode').textContent = cert.certificate_no || 'VERIFIED';
        
        var valText = 'Lifetime Active';
        if (cert.expiry_date) {
            valText = (cert.issue_date ? cert.issue_date + ' to ' : 'Valid until ') + cert.expiry_date;
        } else if (cert.issue_date) {
            valText = 'Issued: ' + cert.issue_date;
        }
        document.getElementById('modalCertValidity').textContent = valText;

        var descEl = document.getElementById('modalCertDesc');
        var descBox = document.getElementById('modalCertDescBox');
        if (cert.description) {
            descEl.textContent = cert.description;
            descBox.style.display = 'block';
        } else {
            descBox.style.display = 'none';
        }

        var isPdf = (cert.image_url || '').toLowerCase().endsWith('.pdf');
        var imgEl = document.getElementById('modalCertImage');
        var pdfEl = document.getElementById('modalCertPdf');
        if (isPdf) {
            imgEl.style.display = 'none';
            pdfEl.style.display = 'block';
        } else {
            imgEl.src = cert.image_url;
            imgEl.style.display = 'block';
            pdfEl.style.display = 'none';
        }

        document.getElementById('modalCertOpenBtn').href = cert.image_url;

        var vBtn = document.getElementById('modalCertVerifyBtn');
        if (cert.verification_url) {
            vBtn.href = cert.verification_url;
            vBtn.style.display = 'inline-flex';
        } else {
            vBtn.style.display = 'none';
        }

        var modal = document.getElementById('pfsCertModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCertLightbox() {
        var modal = document.getElementById('pfsCertModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCertLightbox();
        }
    });
    </script>
    <?php endif; ?>

    <!-- 8. CLIENT TESTIMONIALS CAROUSEL -->
    <?php if (!empty($testimonialsList)): ?>
    <section class="wptb-testimonials-one py-5 position-relative">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <h6 class="wptb-item--subtitle">
                        <i class="bi bi-chat-quote-fill me-1"></i>
                        Partner Endorsements
                    </h6>
                    <h2 class="wptb-item--title">What Luxury Fleet Operators Say</h2>
                </div>
            </div>
            <div class="row g-4">
                <?php foreach (array_slice($testimonialsList, 0, 3) as $test): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="wptb-testimonial-card">
                            <div class="wptb-testimonial-rating">
                                <?php 
                                $r = (int)($test['rating'] ?? 5); 
                                for ($i = 0; $i < $r; $i++): 
                                ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="wptb-testimonial-quote">
                                "<?php echo e($test['review_text']); ?>"
                            </p>
                            <div class="wptb-testimonial-author">
                                <img src="<?php echo e($test['avatar_url'] ?: 'assets/img/author.png'); ?>" alt="<?php echo e($test['client_name']); ?>" class="wptb-testimonial-avatar">
                                <div class="wptb-testimonial-meta">
                                    <h5><?php echo e($test['client_name']); ?></h5>
                                    <span><?php echo e($test['role'] ?? 'Fleet Executive'); ?><?php echo !empty($test['company']) ? ' &bull; ' . e($test['company']) : ''; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 9. QUICK CONTACT & LEAD CAPTURE FORM -->
    <section class="wptb-contact-one py-5 position-relative">
        <div class="container py-4">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <div class="wptb-heading">
                        <div class="wptb-item--inner">
                            <h6 class="wptb-item--subtitle">
                                <i class="bi bi-headset me-1"></i>
                                24/7 Operations Desk
                            </h6>
                            <h2 class="wptb-item--title">Let's Discuss Your Fleet Requirements</h2>
                            <p class="wptb-item--description mt-3">
                                Connect with our senior dispatch directors to formulate a bespoke limousine BPO, back-office, or digital marketing agreement tailored to your dispatch platform.
                            </p>
                            <div class="d-flex flex-column gap-3 mt-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pfs-contact-round-icon">
                                        <i class="bi bi-telephone-fill"></i>
                                    </div>
                                    <div>
                                        <small class="pfs-contact-label">Direct Telephone Support</small>
                                        <a href="tel:+919679897979" class="pfs-contact-detail-link">+91-9679897979 / (747) 236-2181</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pfs-contact-round-icon">
                                        <i class="bi bi-envelope-fill"></i>
                                    </div>
                                    <div>
                                        <small class="pfs-contact-label">Email Inquiries</small>
                                        <a href="mailto:connect@primefleetservices.co.in" class="pfs-contact-detail-link">connect@primefleetservices.co.in</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="p-4 p-md-5 rounded-4 pfs-contact-form-card">
                        <h4 class="font-weight-bold mb-1">Request Operational Proposal</h4>
                        <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 24px;">Complete the form below for a rapid response from our executive dispatch team.</p>

                        <div id="formMessageAlert" class="form-message-alert"></div>

                        <form id="contactForm" method="post" action="submit-inquiry.php">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small">Your Full Name *</label>
                                    <input type="text" name="name" class="form-control pfs-form-input" placeholder="e.g. Michael Vance" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Work Email *</label>
                                    <input type="email" name="email" class="form-control pfs-form-input" placeholder="michael@fleetlimo.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Phone Number</label>
                                    <input type="text" name="phone" class="form-control pfs-form-input" placeholder="+1 (555) 019-2834">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Subject / Fleet Software</label>
                                    <input type="text" name="subject" class="form-control pfs-form-input" placeholder="e.g. 24/7 Limo Anywhere Dispatch">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small">Project / Requirement Details *</label>
                                    <textarea name="message" rows="4" class="form-control pfs-form-input" placeholder="Describe your fleet size, operational shift hours, and dispatch requirements..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-two w-100 justify-content-center" style="padding: 16px;">
                                        <span>Submit Operational Request</span>
                                        <i class="bi bi-send-fill ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
// Contact Form AJAX Handler
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $alert = $('#formMessageAlert');
        var $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true);
        $alert.removeClass('success error').hide();

        $.ajax({
            url: 'submit-inquiry.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $alert.addClass('success').text(resp.message).fadeIn();
                    $form[0].reset();
                } else {
                    $alert.addClass('error').text(resp.message).fadeIn();
                }
            },
            error: function() {
                $alert.addClass('error').text('An error occurred submitting your request. Please call our 24/7 desk directly.').fadeIn();
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
