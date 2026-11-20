<?php
$siteName = get_setting('site_name', 'Prime Fleet Services');
$logoUrl = get_setting('logo_url', 'assets/img/logo.png');
$phoneIn = get_setting('phone_in', '+91-9679897979');
$phoneUs = get_setting('phone_us', '(747) 236-2181');
$address = get_setting('address', 'WEBEL IT PARK, Satellite Township, Kalyanpur, Asansol, West Bengal 713305');
$fbUrl = get_setting('facebook_url', '#');
$instaUrl = get_setting('instagram_url', '#');
$xUrl = get_setting('twitter_url', '#');
$ytUrl = get_setting('youtube_url', '#');
$redditUrl = get_setting('reddit_url', '#');
$waNumber = get_setting('whatsapp_number', '919679897979');
$waMsg = get_setting('whatsapp_message', "Hi! I'm interested in your services and would love to know more.");
$copyright = get_setting('copyright_text', 'Copyright &copy; ' . date('Y') . ' Prime Fleet Services. All Rights Reserved.');
?>
        <!-- Executive Site Footer -->
        <footer class="footer position-relative overflow-hidden" style="background-color: var(--pfs-footer-bg); border-top: 1px solid var(--pfs-footer-border);">
            <!-- Ambient Glow Overlay -->
            <div style="position: absolute; top: 0; left: 15%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(208, 159, 69, 0.08) 0%, transparent 70%); pointer-events: none;"></div>
            <div style="position: absolute; bottom: 0; right: 10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(245, 158, 11, 0.06) 0%, transparent 70%); pointer-events: none;"></div>

            <!-- Pre-Footer Assistance & CTA Strip -->
            <div class="footer-pre-cta py-5 position-relative" style="border-bottom: 1px solid rgba(255, 255, 255, 0.07); background: var(--pfs-footer-pre-bg);">
                <div class="container">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <div class="section-badge mb-2">
                                <i class="bi bi-headset"></i>
                                <span>24/7 Global Fleet Desk</span>
                            </div>
                            <h3 class="text-white mb-2" style="font-size: clamp(1.5rem, 2.5vw, 2.1rem); font-weight: 800; letter-spacing: -0.02em;">
                                Ready to Scale Your Fleet Operations? <span class="text-gradient">Talk with our specialists.</span>
                            </h3>
                            <p class="text-white-50 m-0" style="font-size: 0.96rem; line-height: 1.6;">
                                Instant pod onboarding, dedicated chauffeur dispatchers, and guaranteed 99.9% uptime SLA.
                            </p>
                        </div>
                        <div class="col-lg-5">
                            <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-3">
                                <div class="p-3 px-4 rounded-4 d-flex align-items-center gap-3" style="background: var(--pfs-footer-card-bg); border: 1px solid rgba(208, 159, 69, 0.35); backdrop-filter: blur(10px);">
                                    <div style="width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(135deg, #FBE4A8 0%, #D09F45 100%); display: flex; align-items: center; justify-content: center; color: #030d1d;">
                                        <i class="bi bi-telephone-fill"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-uppercase fw-bold" style="color: var(--pfs-yellow-primary); font-size: 0.72rem; letter-spacing: 0.08em;">Direct USA Desk</small>
                                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phoneUs); ?>" class="text-white fw-bold text-decoration-none" style="font-size: 1.05rem;"><?php echo e($phoneUs); ?></a>
                                    </div>
                                </div>
                                <a href="contact.php" class="btn-two">
                                    <span>Get In Touch</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Footer 4-Column Grid -->
            <div class="footer-main py-5 position-relative">
                <div class="container py-3">
                    <div class="row g-5">
                        <!-- Column 1: Brand & Identity -->
                        <div class="col-lg-4 col-md-6">
                            <div class="footer-widget pe-lg-4">
                                <div class="footer-logo mb-4">
                                    <a href="index.php"><img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>" style="max-height: 48px;"></a>
                                </div>
                                <p style="color: #94a3b8; font-size: 0.92rem; line-height: 1.7; margin-bottom: 1.5rem;">
                                    Prime Fleet Services is a premier global Business Process Outsourcing company specializing in round-the-clock limousine dispatch, reservation management, flight tracking, and digital solutions for elite fleet operators worldwide.
                                </p>

                                <!-- Trust Accreditations -->
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <span class="badge footer-trust-badge">
                                        <i class="bi bi-patch-check-fill text-warning me-1"></i> ISO 9001:2015
                                    </span>
                                    <span class="badge footer-trust-badge">
                                        <i class="bi bi-shield-lock-fill text-warning me-1"></i> ISO 27001
                                    </span>
                                    <span class="badge footer-trust-badge footer-trust-badge-muted">
                                        MSME &amp; Startup India
                                    </span>
                                </div>

                                <!-- Social Media Links -->
                                <div class="footer-social d-flex gap-2">
                                    <?php if ($fbUrl && $fbUrl !== '#'): ?>
                                        <a href="<?php echo e($fbUrl); ?>" target="_blank" rel="noopener" class="footer-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    <?php endif; ?>
                                    <?php if ($instaUrl && $instaUrl !== '#'): ?>
                                        <a href="<?php echo e($instaUrl); ?>" target="_blank" rel="noopener" class="footer-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if ($xUrl && $xUrl !== '#'): ?>
                                        <a href="<?php echo e($xUrl); ?>" target="_blank" rel="noopener" class="footer-social-btn" title="X / Twitter"><i class="bi bi-twitter-x"></i></a>
                                    <?php endif; ?>
                                    <?php if ($ytUrl && $ytUrl !== '#'): ?>
                                        <a href="<?php echo e($ytUrl); ?>" target="_blank" rel="noopener" class="footer-social-btn" title="YouTube"><i class="bi bi-youtube"></i></a>
                                    <?php endif; ?>
                                    <?php if ($redditUrl && $redditUrl !== '#'): ?>
                                        <a href="<?php echo e($redditUrl); ?>" target="_blank" rel="noopener" class="footer-social-btn" title="Reddit"><i class="bi bi-reddit"></i></a>
                                    <?php endif; ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $waNumber); ?>" target="_blank" rel="noopener" class="footer-social-btn whatsapp-hover" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Core Solutions -->
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Core Solutions</h5>
                                <ul class="footer-menu-links list-unstyled mb-0">
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> 24/7 Fleet Dispatch</a></li>
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> Reservation Control</a></li>
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> Chauffeur Support</a></li>
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> VIP Flight Tracking</a></li>
                                    <li><a href="digital.php"><i class="bi bi-chevron-right"></i> Digital Marketing</a></li>
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> Inbound / Outbound BPO</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Column 3: Company Links -->
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Navigation</h5>
                                <ul class="footer-menu-links list-unstyled mb-0">
                                    <li><a href="index.php"><i class="bi bi-chevron-right"></i> Home Overview</a></li>
                                    <li><a href="about.php"><i class="bi bi-chevron-right"></i> About Us</a></li>
                                    <li><a href="services.php"><i class="bi bi-chevron-right"></i> Enterprise Services</a></li>
                                    <li><a href="digital.php"><i class="bi bi-chevron-right"></i> Digital Growth</a></li>
                                    <li><a href="contact.php"><i class="bi bi-chevron-right"></i> Contact &amp; Support</a></li>
                                    <li><a href="admin/login.php"><i class="bi bi-lock-fill"></i> Admin Portal</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Column 4: Global HQ & Newsletter -->
                        <div class="col-lg-4 col-md-6">
                            <div class="footer-widget">
                                <h5 class="widget-title">Global Headquarters</h5>
                                <div class="footer-contact-details mb-4">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="footer-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                        <div>
                                            <small class="footer-contact-label d-block text-uppercase fw-bold">Operations Facility</small>
                                            <span class="footer-contact-val"><?php echo e($address); ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="footer-contact-icon"><i class="bi bi-envelope-fill"></i></div>
                                        <div>
                                            <small class="footer-contact-label d-block text-uppercase fw-bold">Email Inquiries</small>
                                            <a href="mailto:<?php echo e(get_setting('email_support')); ?>" class="footer-contact-val footer-contact-link"><?php echo e(get_setting('email_support')); ?></a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="footer-contact-icon"><i class="bi bi-telephone-fill"></i></div>
                                        <div>
                                            <small class="footer-contact-label d-block text-uppercase fw-bold">India Line</small>
                                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phoneIn); ?>" class="footer-contact-val footer-contact-link"><?php echo e($phoneIn); ?></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Newsletter Form -->
                                <h6 class="text-white fw-bold mb-2" style="font-size: 0.95rem;">Join Our Industry Newsletter</h6>
                                <p style="color: #94a3b8; font-size: 0.82rem; margin-bottom: 12px;">Get monthly fleet optimization strategies and operational benchmarks.</p>
                                <form class="newsletter-form" id="newsletterForm" method="post" action="subscribe.php">
                                    <div id="newsletterAlert" class="form-message-alert mb-2"></div>
                                    <div class="newsletter-input-wrap d-flex align-items-stretch">
                                        <input type="email" name="email" class="newsletter-input" placeholder="Work email address..." required autocomplete="email">
                                        <button type="submit" class="newsletter-submit-btn" aria-label="Subscribe to newsletter">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom Bar -->
            <div class="footer-bottom py-4" style="border-top: 1px solid rgba(255, 255, 255, 0.07); background: #03050b;">
                <div class="container">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
                        <div class="copyright" style="color: #94a3b8; font-size: 0.86rem;">
                            <?php echo $copyright; ?> <span class="d-none d-lg-inline text-muted">| Last Updated: <?php echo date('F Y'); ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-3 text-white-50" style="font-size: 0.82rem;">
                            <span><i class="bi bi-shield-check text-warning me-1"></i> Enterprise NDA Protection</span>
                            <span>•</span>
                            <span><i class="bi bi-clock-history text-primary me-1"></i> 24/7/365 Continuous Shifts</span>
                            <span>•</span>
                            <span><i class="bi bi-activity text-success me-1"></i> 99.9% Uptime SLA</span>
                        </div>
                        <div class="footer-nav-bottom">
                            <ul class="list-unstyled d-flex align-items-center gap-3 m-0" style="font-size: 0.86rem;">
                                <li><a href="about.php" style="color: #94a3b8; text-decoration: none;">Privacy Policy</a></li>
                                <li><a href="about.php" style="color: #94a3b8; text-decoration: none;">Terms of Use</a></li>
                                <li><a href="contact.php" style="color: #94a3b8; text-decoration: none;">Support SLA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Back to Top -->
        <div class="totop">
            <a href="#"><i class="bi bi-chevron-up"></i></a>
        </div>

        <!-- WhatsApp Floating Chat Button (ESLLP Luxury Style) -->
        <div class="wabtn" id="wabutton">
            <a class="wabtn-modern" target="_blank" rel="noopener" href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $waNumber); ?>?text=<?php echo urlencode($waMsg); ?>" aria-label="Chat on WhatsApp">
                <span class="pulse-dot"></span>
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8759 4.06939C21.4959 1.68839 18.3316 0.253548 14.9723 0.0320463C11.613 -0.189455 8.28774 0.817483 5.61565 2.86535C2.94357 4.91323 1.10682 7.86244 0.447451 11.1638C-0.21192 14.4652 0.351026 17.8937 2.03146 20.8109L0.0625 28.0004L7.42006 26.0712C9.45505 27.1794 11.7353 27.7601 14.0524 27.7602H14.0583C16.8029 27.7599 19.4859 26.946 21.768 25.4212C24.0502 23.8965 25.829 21.7294 26.8798 19.1939C27.9305 16.6583 28.206 13.8682 27.6713 11.1761C27.1367 8.48406 25.8159 6.01095 23.8759 4.06939Z" fill="#22C55E"/>
                    <path d="M10.6291 7.98363C10.3723 7.41271 10.1019 7.40123 9.85771 7.39143C9.65779 7.38275 9.42903 7.38331 9.20083 7.38331C9.0271 7.3879 8.8562 7.42837 8.69887 7.5022C8.54154 7.57602 8.40119 7.68159 8.28663 7.81227C7.899 8.17929 7.59209 8.62305 7.38547 9.11526C7.17884 9.60747 7.07704 10.1373 7.08655 10.6711C7.08655 12.3578 8.31519 13.9877 8.48655 14.2164C8.65791 14.4452 10.8581 18.0169 14.3425 19.3908C17.2382 20.5327 17.8276 20.3056 18.4562 20.2485C19.0848 20.1913 20.4843 19.4194 20.7701 18.6189C21.056 17.8183 21.0557 17.1323 20.9701 16.989C20.8844 16.8456 20.6559 16.7605 20.3129 16.5889C19.9699 16.4172 18.2849 15.5879 17.9704 15.4736C17.656 15.3594 17.4275 15.3023 17.199 15.6455C16.9705 15.9888 16.3139 16.7602 16.1137 16.9895C15.9135 17.2189 15.7136 17.2471 15.3709 17.0758C14.3603 16.6729 13.4275 16.0972 12.6143 15.3745C11.8648 14.6818 11.2221 13.8819 10.7072 13.0007C10.5073 12.6579 10.6857 12.472 10.8579 12.3007C11.0119 12.1472 11.2006 11.9005 11.3722 11.7003C11.5129 11.5271 11.6282 11.3346 11.7147 11.1289C11.7603 11.0343 11.7817 10.9299 11.7768 10.825C11.7719 10.7201 11.7409 10.6182 11.6867 10.5283C11.6001 10.3566 10.9337 8.66151 10.6291 7.98363Z" fill="white"/>
                </svg>
                <span>Chat with us</span>
            </a>
        </div>

        <!-- Core JS Scripts -->
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="plugins/wow/wow.min.js"></script>
        <script src="plugins/swiper/swiper-bundle.min.js"></script>
        <script src="plugins/odometer/appear.js"></script>
        <script src="plugins/odometer/odometer.js"></script>
        <script src="plugins/fancybox/jquery.fancybox.min.js"></script>
        <script src="plugins/flatpickr/flatpickr.min.js"></script>
        <script src="plugins/nice-select/jquery.nice-select.min.js"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/pricetable-toggler.js"></script>
        <script src="assets/js/theme-toggle.js"></script>

        <script>
        // Dynamic Swiper Autoplay & Effect from Admin CMS
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function() {
                var swiperElement = document.querySelector('.swiper-main-slider');
                if (swiperElement && swiperElement.swiper) {
                    var isAutoplay = swiperElement.getAttribute('data-autoplay') !== '0';
                    var delay = parseInt(swiperElement.getAttribute('data-delay')) || 5000;
                    var effect = swiperElement.getAttribute('data-effect') || 'slide';

                    swiperElement.swiper.params.effect = effect;
                    if (isAutoplay) {
                        swiperElement.swiper.params.autoplay = {
                            delay: delay, 
                            disableOnInteraction: false
                        };
                        swiperElement.swiper.autoplay.start();
                    } else {
                        swiperElement.swiper.autoplay.stop();
                    }
                }
            }, 800);
        });

        // Newsletter AJAX Form
        $(document).ready(function() {
            $('#newsletterForm').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $alert = $('#newsletterAlert');
                var $btn = $form.find('button[type="submit"]');

                $btn.prop('disabled', true);
                $alert.removeClass('success error').hide();

                $.ajax({
                    url: 'subscribe.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(resp) {
                        $alert.addClass(resp.success ? 'success' : 'error').text(resp.message).show();
                        if (resp.success) {
                            $form[0].reset();
                        }
                    },
                    error: function() {
                        $alert.addClass('error').text('An error occurred. Please try again later.').show();
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
        </script>
        <!-- Theme Toggle Controller -->
        <script src="assets/js/theme-toggle.js?v=<?php echo file_exists(__DIR__ . '/../assets/js/theme-toggle.js') ? filemtime(__DIR__ . '/../assets/js/theme-toggle.js') : time(); ?>"></script>
    </body>
</html>
