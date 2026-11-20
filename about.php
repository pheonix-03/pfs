<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/includes/header.php';

$secAboutHero = get_page_section('about', 'hero');
$secAboutIntro = get_page_section('about', 'intro');

// Dynamic Challenges & Solutions
$stmtChall = $pdo->query("SELECT * FROM challenges_solutions ORDER BY sort_order ASC, id ASC");
$allChallenges = $stmtChall ? $stmtChall->fetchAll() : [];
?>
<main class="wrapper" id="main-content">
    <!-- Page Header -->
    <div class="wptb-page-heading" style="background-image: url('<?php echo !empty($secAboutHero['image_url']) ? e($secAboutHero['image_url']) : 'assets/img/background/abouthead.jpg'; ?>');">
        <div class="container">
            <div class="wptb-item--inner">
                <div class="section-badge">
                    <i class="bi bi-shield-check"></i>
                    <span><?php echo !empty($secAboutHero['badge']) ? e($secAboutHero['badge']) : 'About Prime Fleet Services'; ?></span>
                </div>
                <h1 class="wptb-item--title"><?php echo !empty($secAboutHero['title']) ? e($secAboutHero['title']) : 'About Us'; ?></h1>
                <?php if (!empty($secAboutHero['subtitle'])): ?>
                    <p class="text-white-50 mt-2 mb-0" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;"><?php echo e($secAboutHero['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Company Overview / Features -->
    <?php if (!isset($secAboutIntro['is_visible']) || $secAboutIntro['is_visible']): ?>
    <section class="wptb-features-one">
        <div class="wptb-item-layer wptb-item-layer-four slide-top-to-bottom">
            <img src="assets/img/more/object4.png" alt="PFS Modern Background Shape">
        </div>
        <div class="container pd-top-10">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4 p-lg-0">
                    <div class="wptb-image-single wow fadeInUp">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <div class="wptb-item--image-one">
                                    <img src="assets/img/more/object4.png" alt="PFS Decorative Fleet Element">
                                </div>
                                <div class="wptb-item--image-two">
                                    <img src="<?php echo !empty($secAboutIntro['image_url']) ? e($secAboutIntro['image_url']) : 'assets/img/more/5.png'; ?>" alt="About PFS - Dispatch Operations Hub" class="rounded-3 shadow">
                                </div>
                                <div class="wptb-item--image-three">
                                    <img src="assets/img/more/object2.png" alt="PFS Modern Operations Accent">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-8 ps-lg-5 pt-5 pt-md-0">
                    <div class="wptb-features-one-right-col d-flex">
                        <div class="wptb-heading">
                            <div class="wptb-item--inner">
                                <div class="section-badge mb-3">
                                    <i class="bi bi-shield-check"></i>
                                    <span><?php echo !empty($secAboutIntro['badge']) ? e($secAboutIntro['badge']) : 'About Us'; ?></span>
                                </div>
                                <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800; line-height: 1.25;">
                                    <?php echo !empty($secAboutIntro['title']) ? e($secAboutIntro['title']) : 'About Prime Fleet Services'; ?>
                                </h2>
                                
                                <?php if (!empty($secAboutIntro['content'])): ?>
                                    <div class="about-lead-content" style="text-align: justify; font-size: 1.05rem; line-height: 1.8; color: #cbd5e1;">
                                        <?php echo nl2br(e($secAboutIntro['content'])); ?>
                                    </div>
                                <?php else: ?>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">Prime Fleet Services is a specialized transportation support company providing professional Dispatch, Reservation Management, and Customer Service solutions to limousine, chauffeur, executive transportation, and party bus operators.</p>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">Founded with a vision to help transportation companies operate more efficiently, we serve as a trusted operational partner, supporting businesses with the people, processes, and expertise needed to deliver exceptional service to their customers.</p>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">Based in Asansol, India, our team supports transportation providers across North America and other global markets, offering dependable assistance that helps businesses maintain seamless communication, improve operational efficiency, and enhance customer satisfaction.</p>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">Our professionals are trained to handle customer inquiries, reservation requests, dispatch coordination, trip monitoring, driver communication, and after-hours support with the professionalism and attention to detail that today's transportation industry demands.</p>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">At Prime Fleet Services, we understand that transportation is a service-driven business where every customer interaction matters. That's why we focus on delivering responsive, reliable, and customer-centric support that reflects the standards and reputation of the companies we serve.</p>
                                    <p style="text-align: justify; color: #cbd5e1; font-size: 1.05rem; line-height: 1.8;">As our clients grow, we grow with them—providing scalable support solutions designed to meet the evolving needs of modern transportation businesses.</p>
                                <?php endif; ?>

                                <div class="mt-4">
                                    <a href="<?php echo !empty($secAboutIntro['btn_url']) ? e($secAboutIntro['btn_url']) : 'contact.php'; ?>" class="btn-pfs-primary btn-pfs-lg d-inline-flex align-items-center gap-2">
                                        <span><?php echo !empty($secAboutIntro['btn_text']) ? e($secAboutIntro['btn_text']) : 'Contact Us'; ?></span>
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
    <?php endif; ?>

    <!-- Mission, Vision & Executive Strategy -->
    <?php
    $secPurpose = get_page_section('about', 'purpose');
    $purposeBgImg = !empty($secPurpose['image_url']) ? $secPurpose['image_url'] : 'assets/img/background/bg-purpose-trajectory.jpg';
    ?>
    <section class="wptb-intro-one py-5 position-relative pfs-intro-section">
        <div class="container py-4">
            <div class="row align-items-center g-4 mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="section-badge mb-3">
                        <i class="bi bi-bullseye"></i>
                        <span>Purpose &amp; Trajectory</span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                        We Help Transportation Companies Deliver <span class="text-gradient">Exceptional Customer Experiences</span>
                    </h2>
                    <p class="text-white-50 mt-3" style="font-size: 1.05rem; line-height: 1.8;">
                        At Prime Fleet Services, we empower transportation companies to operate more efficiently, respond faster, and provide exceptional customer service around the clock. Every missed call, delayed response, or uncoordinated booking is lost revenue—our pods safeguard your bottom line 24/7/365.
                    </p>
                </div>
            </div>

            <!-- Side-by-Side Mission & Vision Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="p-4 p-md-5 rounded-4 h-100 position-relative overflow-hidden pfs-strategy-card" style="border: 1px solid rgba(59, 130, 246, 0.3); backdrop-filter: blur(14px);">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, var(--pfs-blue-primary), var(--pfs-blue-dark)); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px var(--pfs-blue-glow);">
                                <i class="bi bi-compass-fill" style="font-size: 1.5rem; color: #ffffff;"></i>
                            </div>
                            <div>
                                <small class="text-uppercase fw-bold d-block" style="color: var(--pfs-blue-cyan); letter-spacing: 0.08em; font-size: 0.75rem;">Strategic Commitment</small>
                                <h3 class="m-0 text-white fw-bold" style="font-size: 1.5rem;">Our Mission</h3>
                            </div>
                        </div>
                        <p style="color: var(--pfs-body-color); font-size: 1.02rem; line-height: 1.75;" class="m-0">
                            To provide luxury transportation companies with reliable, stress-free operational support that enhances customer experiences, improves dispatch efficiency, eliminates booking leakage, and drives sustainable business growth.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 p-md-5 rounded-4 h-100 position-relative overflow-hidden pfs-strategy-card" style="border: 1px solid rgba(245, 158, 11, 0.4); backdrop-filter: blur(14px);">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, var(--pfs-yellow-primary), #d97706); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px var(--pfs-yellow-glow);">
                                <i class="bi bi-eye-fill" style="font-size: 1.5rem; color: #000000;"></i>
                            </div>
                            <div>
                                <small class="text-uppercase fw-bold d-block" style="color: var(--pfs-yellow-primary); letter-spacing: 0.08em; font-size: 0.75rem;">Future Outlook</small>
                                <h3 class="m-0 text-white fw-bold" style="font-size: 1.5rem;">Our Vision</h3>
                            </div>
                        </div>
                        <p style="color: var(--pfs-body-color); font-size: 1.02rem; line-height: 1.75;" class="m-0">
                            To become the undisputed preferred dispatch and customer service partner for transportation providers worldwide by setting the industry benchmark for operational excellence, professionalism, and measurable performance.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Core Values -->
            <div class="mb-5 pt-3">
                <div class="text-center mb-4">
                    <div class="section-badge mb-2">
                        <i class="bi bi-heart-pulse-fill"></i>
                        <span>Guiding Principles</span>
                    </div>
                    <h3 class="text-white fw-bold" style="font-size: 1.8rem;">Our Institutional Core Values</h3>
                </div>
                <div class="row g-3">
                    <?php 
                    $values = [
                        ['Service Excellence', 'Unwavering commitment to five-star hospitality standards on every call.', 'bi bi-award-fill'],
                        ['Reliability & Accountability', 'Ownership mindset across all dispatch coordination and flight monitoring.', 'bi bi-shield-check'],
                        ['Customer-First Approach', 'Empathetic, brand-aligned communication tailored to VIP travelers.', 'bi bi-person-check-fill'],
                        ['Professionalism', 'Strict adherence to chauffeur protocols and corporate decorum.', 'bi bi-briefcase-fill'],
                        ['Continuous Improvement', 'Ongoing Six Sigma QA coaching and SOP refinement weekly.', 'bi bi-graph-up-arrow'],
                        ['Long-Term Partnerships', 'Aligning our operational metrics directly with your bottom-line growth.', 'bi bi-hand-thumbs-up-fill']
                    ];
                    foreach ($values as $val):
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="p-3 px-4 rounded-4 h-100 d-flex align-items-center gap-3 pfs-value-card" style="border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px);">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(59, 130, 246, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="<?php echo $val[2]; ?>" style="color: var(--pfs-yellow-primary); font-size: 1.1rem;"></i>
                                </div>
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.95rem;"><?php echo $val[0]; ?></strong>
                                    <small style="color: var(--pfs-muted-color); font-size: 0.8rem; line-height: 1.4; display: block;"><?php echo $val[1]; ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Why Fleets Choose Us Grid -->
            <div class="pt-4">
                <div class="text-center mb-4">
                    <div class="section-badge mb-2">
                        <i class="bi bi-star-fill"></i>
                        <span>Competitive Differentiators</span>
                    </div>
                    <h3 class="text-white fw-bold" style="font-size: 1.8rem;">Why Transportation Leaders Choose Prime Fleet</h3>
                </div>
                <div class="row g-3">
                    <?php 
                    $reasons = [
                        ['24/7 Dispatch & Reservation Support', 'Non-stop coverage ensuring zero missed trips across any timezone.'],
                        ['Faster Response Times & Improved CX', 'Average speed of answer under 12 seconds with live omnichannel routing.'],
                        ['Reduced Missed Calls & Lost Revenue', 'Instant reservation capture directly within your fleet management software.'],
                        ['Professional Driver Coordination', 'Real-time GPS flight tracking, driver check-in verification, and passenger alerts.'],
                        ['Scalable Pods for Growing Fleets', 'Add dedicated or shared dispatch agents seamlessly as your fleet expands.'],
                        ['Dedicated Support Team', 'Trained specialists who understand limo, coach, and chauffeur operations deeply.']
                    ];
                    foreach ($reasons as $r):
                    ?>
                        <div class="col-md-6">
                            <div class="p-3 px-4 rounded-4 h-100 d-flex align-items-center gap-3 pfs-feature-card" style="border: 1px solid rgba(59, 130, 246, 0.22); backdrop-filter: blur(10px);">
                                <i class="bi bi-check-circle-fill text-warning fs-5 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.96rem;"><?php echo $r[0]; ?></strong>
                                    <small style="color: var(--pfs-muted-color); font-size: 0.84rem;"><?php echo $r[1]; ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Challenges & Solutions Section -->
    <section class="wptb-service-two py-5 position-relative">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-shuffle"></i>
                        <span>Strategic Contrast</span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800;">
                        The Challenge <span class="text-gradient">vs. Our Solution</span>
                    </h2>
                    <p class="text-muted mx-auto mt-2" style="max-width: 680px;">
                        How Prime Fleet Services transforms operational friction into five-star passenger experiences and sustainable revenue growth.
                    </p>
                </div>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-4 col-sm-6 d-none d-lg-block">
                    <div class="pfs-car-infographic-wrap text-center p-2">
                        <img src="assets/img/more/car.png" alt="Prime Fleet Logistics" class="img-fluid rounded-4 shadow-lg" style="max-height: 520px; object-fit: contain; filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.22));">
                    </div>
                </div>

                <div class="col-lg-8 col-sm-6 ps-lg-4">
                    <div class="row g-3">
                        <?php if (!empty($allChallenges)): ?>
                            <?php foreach ($allChallenges as $item): ?>
                                <?php $isChallenge = ($item['type'] === 'challenge'); ?>
                                <div class="col-md-6 wow fadeInLeft">
                                    <div class="pfs-cs-card h-100 p-4 rounded-4 position-relative overflow-hidden <?php echo $isChallenge ? 'pfs-cs-card--challenge' : 'pfs-cs-card--solution'; ?>">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="pfs-cs-icon">
                                                <i class="<?php echo !empty($item['icon']) ? e($item['icon']) : (!empty($item['icon_class']) ? e($item['icon_class']) : ($isChallenge ? 'bi bi-exclamation-octagon-fill' : 'bi bi-check-circle-fill')); ?>"></i>
                                            </div>
                                            <span class="pfs-cs-type-pill">
                                                <?php echo $isChallenge ? 'Challenge' : 'Solution'; ?>
                                            </span>
                                        </div>
                                        <h4 class="pfs-cs-title mb-2"><?php echo e($item['title']); ?></h4>
                                        <p class="pfs-cs-desc mb-0"><?php echo e($item['description']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-md-6 wow fadeInLeft">
                                <div class="pfs-cs-card pfs-cs-card--challenge h-100 p-4 rounded-4 position-relative overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="pfs-cs-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                                        <span class="pfs-cs-type-pill">Challenge</span>
                                    </div>
                                    <h4 class="pfs-cs-title mb-2">SLOW RESPONSE TIMES</h4>
                                    <p class="pfs-cs-desc mb-0">Transportation companies often struggle with missed calls, slow response times, and rising operational overhead.</p>
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInLeft">
                                <div class="pfs-cs-card pfs-cs-card--solution h-100 p-4 rounded-4 position-relative overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="pfs-cs-icon"><i class="bi bi-check-circle-fill"></i></div>
                                        <span class="pfs-cs-type-pill">Solution</span>
                                    </div>
                                    <h4 class="pfs-cs-title mb-2">PROFESSIONAL EXPERTISE</h4>
                                    <p class="pfs-cs-desc mb-0">Prime Fleet Services delivers scalable 24/7 support solutions that enhance customer satisfaction and reduce costs.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
