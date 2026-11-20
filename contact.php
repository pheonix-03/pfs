<?php
$pageTitle = 'Contact Us';
require_once __DIR__ . '/includes/header.php';

$secContactHero = get_page_section('contact', 'hero');
?>
<main class="wrapper" id="main-content">
    <!-- Page Header -->
    <div class="wptb-page-heading" style="background-image: url('<?php echo !empty($secContactHero['image_url']) ? e($secContactHero['image_url']) : 'assets/img/background/contact-dispatch-center.jpg'; ?>');">
        <div class="container">
            <div class="wptb-item--inner">
                <div class="section-badge">
                    <i class="bi bi-headset"></i>
                    <span><?php echo !empty($secContactHero['badge']) ? e($secContactHero['badge']) : '24/7 Global Support'; ?></span>
                </div>
                <h1 class="wptb-item--title"><?php echo !empty($secContactHero['title']) ? e($secContactHero['title']) : 'Contact Us'; ?></h1>
                <?php if (!empty($secContactHero['subtitle'])): ?>
                    <p class="text-white-50 mt-2 mb-0" style="max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;"><?php echo e($secContactHero['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contact Details & Form -->
    <section class="pd-bottom-120 pt-5">
        <div class="container">
            <div class="wptb-office-address mr-bottom-60">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="pfs-feature-card rounded-4 p-4 p-md-5 h-100 text-center position-relative overflow-hidden">
                            <div class="mx-auto mb-3 pfs-contact-round-icon pfs-contact-round-icon-lg">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <small class="text-uppercase fw-bold d-block mb-1 pfs-contact-label" style="letter-spacing: 0.08em; font-size: 0.76rem;">Operations Facility</small>
                            <h4 class="text-white mb-0 pfs-card-title" style="font-size: 1.05rem; line-height: 1.6; font-weight: 700;"><?php echo e(get_setting("address")); ?></h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pfs-feature-card rounded-4 p-4 p-md-5 h-100 text-center position-relative overflow-hidden">
                            <div class="mx-auto mb-3 pfs-contact-round-icon pfs-contact-round-icon-lg">
                                <i class="bi bi-envelope-open-fill"></i>
                            </div>
                            <small class="text-uppercase fw-bold d-block mb-1 pfs-contact-label" style="letter-spacing: 0.08em; font-size: 0.76rem;">Email Inquiries</small>
                            <h5 class="fs-6 mb-1"><a href="mailto:<?php echo e(get_setting("email_support")); ?>" class="text-white text-decoration-none fw-bold pfs-contact-detail-link"><?php echo e(get_setting("email_support")); ?></a></h5>
                            <h5 class="fs-6 mb-0"><a href="mailto:<?php echo e(get_setting("email_info")); ?>" style="color: #94a3b8; text-decoration: none;" class="pfs-contact-detail-sublink"><?php echo e(get_setting("email_info")); ?></a></h5>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pfs-feature-card rounded-4 p-4 p-md-5 h-100 text-center position-relative overflow-hidden">
                            <div class="mx-auto mb-3 pfs-contact-round-icon pfs-contact-round-icon-lg">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <small class="text-uppercase fw-bold d-block mb-1 pfs-contact-label" style="letter-spacing: 0.08em; font-size: 0.76rem;">24/7 Global Hotlines</small>
                            <h5 class="fs-6 mb-1"><a href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', get_setting("phone_in"))); ?>" class="text-white text-decoration-none fw-bold pfs-contact-detail-link"><?php echo e(get_setting("phone_in")); ?> (India)</a></h5>
                            <h5 class="fs-6 mb-0"><a href="tel:<?php echo e(str_replace([' ', '-', '(', ')'], '', get_setting("phone_us"))); ?>" class="text-warning text-decoration-none fw-bold pfs-contact-detail-highlight"><?php echo e(get_setting("phone_us")); ?> (USA Desk)</a></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inquiries Form -->
            <div class="wptb-contact-form-wrap">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="pfs-contact-form-card rounded-4 p-4 p-md-5 shadow-lg" style="background: var(--pfs-glass-bg); border: 1px solid var(--pfs-glass-border); backdrop-filter: blur(16px);">
                            <div class="text-center mb-5">
                                <div class="section-badge mb-2">
                                    <i class="bi bi-chat-square-dots-fill"></i>
                                    <span>Direct Consultation</span>
                                </div>
                                <h2 class="wptb-item--title" style="font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 800;">
                                    Start Your <span class="text-gradient">Fleet Partnership</span>
                                </h2>
                                <p class="text-white-50 mt-2 mx-auto" style="max-width: 640px; font-size: 0.98rem; line-height: 1.6;">
                                    Have questions or want to discuss outsourcing your dispatch &amp; customer support operations? Fill out the brief form below and our team will respond within 30 minutes.
                                </p>
                            </div>

                            <form id="contactForm" action="submit-inquiry.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token'] ?? ''); ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="name" class="form-control" id="inquiryName" placeholder="Your Full Name" required>
                                            <label for="inquiryName">Your Full Name *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control" id="inquiryEmail" placeholder="Your Email Address" required>
                                            <label for="inquiryEmail">Your Email Address *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control" id="inquiryPhone" placeholder="Phone Number">
                                            <label for="inquiryPhone">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="subject" class="form-control" id="inquirySubject" placeholder="Subject">
                                            <label for="inquirySubject">Subject</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <textarea name="message" class="form-control" id="inquiryMsg" placeholder="Tell us about your requirements..." style="height: 140px" required></textarea>
                                            <label for="inquiryMsg">Your Message *</label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <button type="submit" class="btn btn-lg fw-bold" style="background: linear-gradient(135deg, var(--pfs-yellow-primary), #d97706); color: #000000; border: none; border-radius: 9999px; font-weight: 800; padding: 14px 44px; box-shadow: 0 0 25px var(--pfs-yellow-glow); transition: all 0.3s ease;">
                                            Send Message Now <i class="bi bi-send-fill ms-2"></i>
                                        </button>
                                        <div id="contactFormStatus" class="mt-3"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('contactForm');
    var statusDiv = document.getElementById('contactFormStatus');
    if (form && statusDiv) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            statusDiv.innerHTML = '<div class="alert alert-info">Sending your message...</div>';
            var formData = new FormData(form);
            fetch('submit-inquiry.php', {
                method: 'POST',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    statusDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    form.reset();
                } else {
                    statusDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(function() {
                statusDiv.innerHTML = '<div class="alert alert-danger">An unexpected error occurred. Please try again or call us directly.</div>';
            });
        });
    }
});
</script>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
