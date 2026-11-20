<?php
$pageTitle = 'Our Services';
require_once __DIR__ . '/includes/header.php';

$secServicesHero = get_page_section('services', 'hero');

// Fetch active BPO services
$stmtSrv = $pdo->query("SELECT * FROM services WHERE category = 'bpo' AND status = 1 ORDER BY sort_order ASC, id ASC");
$activeServices = $stmtSrv ? $stmtSrv->fetchAll() : [];
?>
<main class="wrapper" id="main-content">
    <!-- Page Header -->
    <div class="wptb-page-heading" style="background-image: url('<?php echo !empty($secServicesHero['image_url']) ? e($secServicesHero['image_url']) : 'assets/img/background/services-limo-fleet.jpg'; ?>');">
        <div class="container">
            <div class="wptb-item--inner">
                <div class="section-badge">
                    <i class="bi bi-gear-wide-connected"></i>
                    <span><?php echo !empty($secServicesHero['badge']) ? e($secServicesHero['badge']) : 'Enterprise BPO Solutions'; ?></span>
                </div>
                <h1 class="wptb-item--title"><?php echo !empty($secServicesHero['title']) ? e($secServicesHero['title']) : 'Our Services'; ?></h1>
                <?php if (!empty($secServicesHero['subtitle'])): ?>
                    <p class="text-white-50 mt-2 mb-0" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;"><?php echo e($secServicesHero['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Our Services Section -->
    <section class="pfs-services-section py-5">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-gear-fill"></i>
                        <span><?php echo !empty($secServicesHero['badge']) ? e($secServicesHero['badge']) : 'Why Choose Us'; ?></span>
                    </div>
                    <h2 class="wptb-item--title fs-1">OUR <span class="text-gradient">SERVICES</span></h2>
                    <p class="text-muted mx-auto mt-2" style="max-width: 650px;">
                        Scalable, professional, and empathetic customer solutions designed for peak fleet efficiency and 24/7 client satisfaction.
                    </p>
                </div>
            </div>

            <div class="pfs-services-showcase-wrapper">
                <!-- Left Media Showcase Card -->
                <div class="pfs-services-media-card" style="background-image: url('assets/img/background/bg-service.jpg');">
                    <div class="pfs-services-media-badge">
                        <div class="badge-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="badge-title">Enterprise Infrastructure</div>
                            <div class="badge-sub">24/7 Dedicated Teams & Six Sigma QA</div>
                        </div>
                    </div>
                </div>

                <!-- Right Services Cards Grid -->
                <div class="pfs-service-grid-cards">
                    <?php if (!empty($activeServices)): ?>
                        <?php foreach ($activeServices as $srv): ?>
                            <div class="pfs-service-card-item">
                                <div class="card-icon">
                                    <i class="<?php echo !empty($srv['icon_class']) ? e($srv['icon_class']) : 'bi bi-check2-circle'; ?>"></i>
                                </div>
                                <h3 class="card-title"><?php echo strtoupper(e($srv['title'])); ?></h3>
                                <p class="card-desc">
                                    <?php echo e(!empty($srv['short_desc']) ? $srv['short_desc'] : $srv['full_desc']); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="pfs-service-card-item">
                            <div class="card-icon"><i class="bi bi-headset"></i></div>
                            <h3 class="card-title">24/7 DISPATCH SUPPORT</h3>
                            <p class="card-desc">Our dispatch team ensures seamless coordination between customers, drivers, and operations around the clock.</p>
                        </div>
                        <div class="pfs-service-card-item">
                            <div class="card-icon"><i class="bi bi-calendar-check"></i></div>
                            <h3 class="card-title">RESERVATION MANAGEMENT</h3>
                            <p class="card-desc">From initial inquiries to confirmed bookings, we handle the entire reservation process with accuracy and professionalism.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Our USP Section -->
    <section class="wptb-features-one py-5" style="margin-bottom: 80px;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 col-md-12">
                    <div class="pfs-usp-media-frame position-relative rounded-4 overflow-hidden shadow-lg" style="border: 1px solid rgba(56, 189, 248, 0.28); background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px);">
                        <img src="assets/img/more/usp1.png" alt="Prime Fleet Services USP" class="img-fluid w-100" style="display: block; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(180deg, transparent 0%, rgba(3, 7, 18, 0.92) 100%);">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(37, 99, 235, 0.3); border: 1px solid rgba(56, 189, 248, 0.5); color: #38bdf8; font-size: 0.8rem; letter-spacing: 0.05em; font-weight: 700;">
                                <i class="bi bi-patch-check-fill text-warning me-1"></i> SIX SIGMA DMAIC METHODOLOGY
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-md-12">
                    <div class="wptb-heading">
                        <div class="wptb-item--inner">
                            <div class="section-badge mb-3">
                                <i class="bi bi-award-fill"></i>
                                <span>OUR USP</span>
                            </div>
                            <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800; line-height: 1.25;">
                                Continuous <span class="text-gradient">Operational Excellence</span>
                            </h2>
                            <p style="font-size: 1.05rem; line-height: 1.8; color: #cbd5e1; margin-top: 1rem;">
                                Most service providers handle tasks. Prime Fleet Services goes further by analysing workflows, identifying operational inefficiencies, and implementing solutions that improve customer satisfaction, increase booking opportunities, and strengthen overall business performance by using the globally recognized Six Sigma DMAIC methodology.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DMAIC 5-Phase Horizontal Pipeline -->
            <div class="pfs-dmaic-pipeline mt-5">
                <div class="pfs-dmaic-step">
                    <div class="step-badge">Phase 01</div>
                    <h4 class="step-title">DEFINE</h4>
                    <p class="step-desc">Workflow &amp; SLA Goals</p>
                    <div class="step-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>

                <div class="pfs-dmaic-step">
                    <div class="step-badge">Phase 02</div>
                    <h4 class="step-title">MEASURE</h4>
                    <p class="step-desc">Call &amp; Trip Latency</p>
                    <div class="step-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>

                <div class="pfs-dmaic-step">
                    <div class="step-badge">Phase 03</div>
                    <h4 class="step-title">ANALYSE</h4>
                    <p class="step-desc">Root-Cause Auditing</p>
                    <div class="step-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>

                <div class="pfs-dmaic-step">
                    <div class="step-badge">Phase 04</div>
                    <h4 class="step-title">IMPROVE</h4>
                    <p class="step-desc">SOP &amp; Pod Upskilling</p>
                    <div class="step-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>

                <div class="pfs-dmaic-step">
                    <div class="step-badge">Phase 05</div>
                    <h4 class="step-title">CONTROL</h4>
                    <p class="step-desc">24/7 Quality Gates</p>
                    <div class="step-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expandable FAQ Section with Schema Markup -->
    <section class="wptb-faq-section py-5 position-relative" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="container py-4">
            <div class="wptb-heading text-center mb-5">
                <div class="wptb-item--inner">
                    <div class="section-badge mb-3">
                        <i class="bi bi-question-circle-fill"></i>
                        <span>FREQUENTLY ASKED QUESTIONS</span>
                    </div>
                    <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800;">
                        Got Questions? <span class="text-gradient">We've Got Answers.</span>
                    </h2>
                    <p class="text-muted mx-auto mt-2" style="max-width: 680px;">
                        Everything you need to know about our 24/7 limousine dispatching, livery software integrations, and onboarding protocols.
                    </p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion custom-pfs-accordion" id="pfsFaqAccordion">
                        <div class="accordion-item mb-3" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 12px; overflow: hidden;">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="background: transparent; color: inherit; box-shadow: none;">
                                    <i class="bi bi-gear-wide-connected text-primary me-2"></i> What dispatch software and livery platforms do you support?
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#pfsFaqAccordion">
                                <div class="accordion-body text-muted" style="border-top: 1px solid rgba(255, 255, 255, 0.05); line-height: 1.7;">
                                    Our dispatchers are certified and highly experienced in Limo Anywhere, Livery Coach, Santa Cruz, FASTTRAK, Hudson Ground, and custom proprietary enterprise dispatch systems. We adapt seamlessly to your existing workflows without disruptive software migrations.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 12px; overflow: hidden;">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background: transparent; color: inherit; box-shadow: none;">
                                    <i class="bi bi-clock-history text-primary me-2"></i> How fast can Prime Fleet Services onboard and begin live dispatching?
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#pfsFaqAccordion">
                                <div class="accordion-body text-muted" style="border-top: 1px solid rgba(255, 255, 255, 0.05); line-height: 1.7;">
                                    Standard fleet onboarding takes 48 to 72 hours. During this period, our operations team documents your specific SOPs, vehicle configurations, pricing rules, and chauffeur communication preferences, followed by shadow dispatch sessions before full cutover.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 12px; overflow: hidden;">
                            <h3 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background: transparent; color: inherit; box-shadow: none;">
                                    <i class="bi bi-airplane text-primary me-2"></i> How do you handle airport arrivals and flight delays?
                                </button>
                            </h3>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#pfsFaqAccordion">
                                <div class="accordion-body text-muted" style="border-top: 1px solid rgba(255, 255, 255, 0.05); line-height: 1.7;">
                                    We utilize integrated live radar and FlightAware systems to track tail numbers, inbound flight schedules, and gate holds in real time. Chauffeurs are notified automatically of delays to eliminate unnecessary staging and parking fees while guaranteeing timely curbside meet-and-greets.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 12px; overflow: hidden;">
                            <h3 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="background: transparent; color: inherit; box-shadow: none;">
                                    <i class="bi bi-shield-lock text-primary me-2"></i> What data security protocols protect our customer and booking records?
                                </button>
                            </h3>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#pfsFaqAccordion">
                                <div class="accordion-body text-muted" style="border-top: 1px solid rgba(255, 255, 255, 0.05); line-height: 1.7;">
                                    We adhere to strict ISO 27001 data protection and security frameworks. Dispatch agents operate within isolated terminal networks with restricted USB/clipboard access, role-based CRM permissions, encrypted VoIP lines, and rigorous NDAs.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Schema FAQPage Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What dispatch software and livery platforms do you support?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Our dispatchers are certified and highly experienced in Limo Anywhere, Livery Coach, Santa Cruz, FASTTRAK, Hudson Ground, and custom proprietary enterprise dispatch systems."
          }
        },
        {
          "@type": "Question",
          "name": "How fast can Prime Fleet Services onboard and begin live dispatching?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Standard fleet onboarding takes 48 to 72 hours, including SOP documentation, pricing rules calibration, and shadow dispatch sessions."
          }
        },
        {
          "@type": "Question",
          "name": "How do you handle airport arrivals and flight delays?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We utilize integrated live radar and FlightAware systems to track flight status in real time, notifying chauffeurs automatically to eliminate staging fees and guarantee punctual pickups."
          }
        },
        {
          "@type": "Question",
          "name": "What data security protocols protect our customer and booking records?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We adhere to ISO 27001 information security standards with isolated networks, role-based CRM permissions, encrypted VoIP lines, and strict non-disclosure agreements."
          }
        }
      ]
    }
    </script>
</main>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
