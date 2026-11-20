<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$canonicalUrl = 'https://primefleetservices.co.in/' . ($currentPage === 'index.php' ? '' : $currentPage);

// SEO Page-specific Title & Description defaults
$defaultMeta = [
    'index.php' => [
        'title' => 'Prime Fleet Services | 24/7 Limousine Dispatch & BPO Solutions',
        'desc' => 'Leading 24/7 limousine dispatch, reservation management, and BPO operations engineered for luxury transportation and enterprise fleets worldwide.'
    ],
    'about.php' => [
        'title' => 'About Us | Prime Fleet Services - Trusted BPO Partner',
        'desc' => 'Learn about Prime Fleet Services, our state-of-the-art dispatch center in Asansol, IT infrastructure, leadership, and operational excellence.'
    ],
    'services.php' => [
        'title' => 'Our Services | 24/7 Limo Dispatch & BPO Management',
        'desc' => 'Explore PFS specialized dispatching, chauffeur tracking, reservation booking, VIP concierge, and customer support for luxury fleet operators.'
    ],
    'digital.php' => [
        'title' => 'Digital Marketing Solutions | Fleet Growth by PFS',
        'desc' => 'Custom digital marketing, local SEO, PPC lead generation, and social media branding tailored for transportation and limousine providers.'
    ],
    'contact.php' => [
        'title' => 'Contact Us | Prime Fleet Services - 24/7 Global Support',
        'desc' => 'Get in touch with Prime Fleet Services for inquiries, custom fleet dispatch proposals, or 24/7 operational support.'
    ]
];

$pageMeta = isset($defaultMeta[$currentPage]) ? $defaultMeta[$currentPage] : [
    'title' => get_setting('site_name', 'PFS - Prime Fleet Services'),
    'desc' => get_setting('meta_description', 'Prime Fleet Services - Global BPO & Digital Marketing Solutions')
];

$resolvedTitle = isset($pageTitle) && $currentPage !== 'index.php' ? $pageTitle . ' - ' . get_setting('site_name', 'PFS') : $pageMeta['title'];
$resolvedDesc = !empty($metaDescription) ? $metaDescription : $pageMeta['desc'];

$siteName = get_setting('site_name', 'PFS - Prime Fleet Services');
$logoUrl = get_setting('logo_url', 'assets/img/logo.png');
$faviconUrl = get_setting('favicon_url', 'assets/img/favicon.png');
$phoneIn = get_setting('phone_in', '+91-9679897979');
$phoneUs = get_setting('phone_us', '(747) 236-2181');
$emailSupport = get_setting('email_support', 'connect@primefleetservices.co.in');
$emailInfo = get_setting('email_info', 'info@primefleetservices.co.in');
$address = get_setting('address', 'WEBEL IT PARK, Satellite Township, Kalyanpur, Asansol, West Bengal 713305');
$workingHours = get_setting('working_hours', '24 X 7 Live');
$fbUrl = get_setting('facebook_url', '#');
$instaUrl = get_setting('instagram_url', '#');
$xUrl = get_setting('twitter_url', '#');
$ytUrl = get_setting('youtube_url', '#');
$redditUrl = get_setting('reddit_url', '#');
$linkedinUrl = get_setting('linkedin_url', '#');
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="color-scheme" content="dark light">
    <script>
        (function() {
            try {
                var urlParams = new URLSearchParams(window.location.search);
                var forcedTheme = urlParams.get('theme');
                var s = forcedTheme || localStorage.getItem('pfs-theme') || localStorage.getItem('pfs_theme');
                var t = (s === 'light') ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', t);
                if (t === 'light') {
                    document.documentElement.classList.add('theme-style--light');
                } else {
                    document.documentElement.classList.remove('theme-style--light');
                }
                var meta = document.querySelector('meta[name="color-scheme"]');
                if (meta) meta.content = t;
            } catch(e) {}
        })();
    </script>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    
    <!-- Meta Descriptions & Author -->
    <meta name="description" content="<?php echo e($resolvedDesc); ?>">
    <meta name="author" content="Prime Fleet Services">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:title" content="<?php echo e($resolvedTitle); ?>">
    <meta property="og:description" content="<?php echo e($resolvedDesc); ?>">
    <meta property="og:image" content="https://primefleetservices.co.in/assets/img/slider/slide-1.jpg">
    <meta property="og:site_name" content="Prime Fleet Services">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e($canonicalUrl); ?>">
    <meta name="twitter:title" content="<?php echo e($resolvedTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($resolvedDesc); ?>">
    <meta name="twitter:image" content="https://primefleetservices.co.in/assets/img/slider/slide-1.jpg">

    <!-- Favicon -->
    <link href="<?php echo e($faviconUrl); ?>" rel="shortcut icon" type="image/png">
    <link href="<?php echo e($faviconUrl); ?>" rel="apple-touch-icon">

    <!-- Page Title -->
    <title><?php echo e($resolvedTitle); ?></title>

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "https://primefleetservices.co.in/#organization",
          "name": "Prime Fleet Services",
          "url": "https://primefleetservices.co.in/",
          "logo": "https://primefleetservices.co.in/assets/img/logo.png",
          "description": "24/7 Limousine Dispatch, Fleet Management, and Omnichannel BPO Services.",
          "contactPoint": [
            {
              "@type": "ContactPoint",
              "telephone": "+91-9679897979",
              "contactType": "customer service",
              "areaServed": ["IN", "US", "CA", "GB"],
              "availableLanguage": ["English", "Hindi"]
            },
            {
              "@type": "ContactPoint",
              "telephone": "+1-747-236-2181",
              "contactType": "sales",
              "areaServed": "US",
              "availableLanguage": "English"
            }
          ],
          "sameAs": [
            "https://www.facebook.com/",
            "https://www.linkedin.com/",
            "https://www.instagram.com/"
          ]
        },
        {
          "@type": "LocalBusiness",
          "@id": "https://primefleetservices.co.in/#localbusiness",
          "name": "Prime Fleet Services",
          "image": "https://primefleetservices.co.in/assets/img/slider/slide-1.jpg",
          "telephone": "+91-9679897979",
          "email": "connect@primefleetservices.co.in",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "WEBEL IT PARK, Satellite Township, Kalyanpur",
            "addressLocality": "Asansol",
            "addressRegion": "West Bengal",
            "postalCode": "713305",
            "addressCountry": "IN"
          },
          "openingHours": "Mo-Su 00:00-24:00"
        }
      ]
    }
    </script>

    <!-- Core Web Vitals: Preconnect to Font CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles Include -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/custom-ui.css?v=<?php echo file_exists(__DIR__ . '/../assets/css/custom-ui.css') ? filemtime(__DIR__ . '/../assets/css/custom-ui.css') : time(); ?>">
    
    <style>
        /* Modern Enhancements & Clean Polish */
        body { transition: opacity ease-in 0.2s; }
        .wptb-cart-box { min-width: 260px; }
        .wptb-item--holder h5 a { color: inherit; text-decoration: none; }
        .form-message-alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; display: none; }
        .form-message-alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; display: block; }
        .form-message-alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; display: block; }

        /* Accessibility Skip-to-content */
        .skip-to-content {
            position: absolute;
            top: -999px;
            left: 1rem;
            background: #2563eb;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            border-radius: 8px;
            z-index: 99999;
            text-decoration: none;
            transition: top 0.2s ease;
        }
        .skip-to-content:focus {
            top: 1rem;
        }
        /* Top Scroll Progress Bar */
        .pfs-scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #2563eb, #38bdf8, #f59e0b);
            z-index: 9999;
            pointer-events: none;
            transition: width 0.1s linear;
        }
    </style>

    <!-- Anti-flicker Theme Initializer -->
    <script>
        (function() {
            var urlParams = new URLSearchParams(window.location.search);
            var forcedTheme = urlParams.get('theme');
            var theme = forcedTheme || localStorage.getItem('pfs-theme') || localStorage.getItem('pfs_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'light') {
                document.documentElement.classList.add('theme-style--light');
            } else {
                document.documentElement.classList.remove('theme-style--light');
            }
        })();
    </script>
</head>
<body>
    <script>
        if (document.documentElement.getAttribute('data-theme') === 'light') {
            document.body.classList.add('theme-style--light');
        }
    </script>
    <!-- Skip to Content Link for Accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- Top Scroll Progress Bar -->
    <div id="scroll-progress-bar" class="pfs-scroll-progress"></div>

    <?php require __DIR__ . '/preloader.php'; ?>

    <!-- Main Header -->
    <header class="header">
        <!-- Top Bar -->
        <div class="header-top">
            <div class="container-fluid pe-4">
                <div class="header-top--inner d-none d-xl-flex justify-content-between align-items-center flex-wrap">
                    <!-- Left Box -->
                    <div class="left-box d-flex align-items-center">
                        <ul class="info-list">
                            <li><a href="mailto:<?php echo e($emailSupport); ?>"><span class="icon bi bi-envelope-fill"></span><?php echo e($emailSupport); ?></a></li>
                            <li><a href="#"><span class="icon bi bi-clock"></span><?php echo e($workingHours); ?></a></li>
                            <li><a href="#"><span class="icon bi bi-geo-alt-fill"></span><?php echo e($address); ?></a></li>
                        </ul>
                    </div>
                    
                    <!-- Right Box -->
                    <div class="right-box d-flex align-items-center">
                        <div class="social-box">
                            <ul>
                                <?php if ($fbUrl && $fbUrl !== '#'): ?>
                                    <li><a href="<?php echo e($fbUrl); ?>" class="bi bi-facebook" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                                <?php if ($instaUrl && $instaUrl !== '#'): ?>
                                    <li><a href="<?php echo e($instaUrl); ?>" class="bi bi-instagram" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                                <?php if ($xUrl && $xUrl !== '#'): ?>
                                    <li><a href="<?php echo e($xUrl); ?>" class="bi bi-twitter-x" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                                <?php if ($ytUrl && $ytUrl !== '#'): ?>
                                    <li><a href="<?php echo e($ytUrl); ?>" class="bi bi-youtube" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                                <?php if ($redditUrl && $redditUrl !== '#'): ?>
                                    <li><a href="<?php echo e($redditUrl); ?>" class="bi bi-reddit" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                                <?php if ($linkedinUrl && $linkedinUrl !== '#'): ?>
                                    <li><a href="<?php echo e($linkedinUrl); ?>" class="bi bi-linkedin" target="_blank" rel="noopener"></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Integrated Executive Theme Pill Switch -->
                        <div class="theme-top-switch ms-3">
                            <button type="button" class="pfs-theme-pill-toggle" id="theme-toggle" aria-label="Toggle Color Theme" title="Toggle Light/Dark Mode">
                                <i class="bi bi-sun-fill theme-icon-sun"></i>
                                <i class="bi bi-moon-stars-fill theme-icon-moon"></i>
                                <span class="theme-label-text ms-1">Mode</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lower Bar -->
        <div class="header-inner">
            <div class="container-fluid pe-0">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Left Part (Logo) -->
                    <div class="header_left_part d-flex align-items-center">
                        <div class="logo">
                            <a href="index.php" class="light_logo"><img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>"></a>
                        </div>
                    </div>

                    <!-- Right Part -->
                    <div class="header_right_part d-flex align-items-center">
                        <div class="mainnav d-none d-xl-block">
                            <ul class="main-menu">
                                <li class="menu-item <?php echo in_array($currentPage, ['index.php', '']) ? 'active' : ''; ?>"><a href="index.php">Home</a></li>
                                <li class="menu-item <?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>"><a href="about.php">About Us</a></li>
                                <li class="menu-item <?php echo ($currentPage === 'services.php') ? 'active' : ''; ?>"><a href="services.php">Services</a></li>
                                <li class="menu-item <?php echo ($currentPage === 'digital.php') ? 'active' : ''; ?>"><a href="digital.php">Digital Marketing</a></li>
                                <li class="menu-item <?php echo ($currentPage === 'contact.php') ? 'active' : ''; ?>"><a href="contact.php">Contact</a></li>
                            </ul>
                        </div>

                        <div class="wptb-header--cart">
                            <a href="contact.php" class="wptb-cart-icon" title="Quick Consultation"><i class="bi bi-arrow-up-right"></i></a>
                            <div class="wptb-cart-box">
                                <div class="wptb-grand-total">
                                    <ul>
                                        <li><a href="services.php">Enhanced Satisfaction</a></li>
                                        <li><a href="services.php">Reduced Cost</a></li>
                                        <li><a href="services.php">Scalable Solutions</a></li>
                                        <li><a href="services.php">Professional Expertise</a></li>
                                    </ul>
                                    <div class="wptb-item--button">
                                        <a href="contact.php" class="btn-two w-100 justify-content-center" style="padding: 12px 24px; font-size: 0.88rem;">
                                            <span>Get Consultation</span>
                                            <i class="bi bi-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wptb-icon-box1 live-chat d-none d-md-block">
                            <div class="wptb-item--inner flex-start">
                                <div class="wptb-item--icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="35" viewBox="0 0 36 35" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9268 20.5991C22.0141 19.6831 20.5336 19.6823 19.6201 20.5991L19.6172 20.602C19.143 21.0779 18.3743 21.0784 17.9003 20.6027C17.0858 19.7854 16.0287 18.7244 15.2142 17.907C14.7402 17.4313 14.7407 16.6599 15.215 16.1839L15.2179 16.181C16.1313 15.2643 16.1306 13.7785 15.2179 12.8625C14.8047 12.4478 14.34 11.9815 13.9055 11.5454C13.379 11.0159 12.6635 10.719 11.9183 10.7189C11.1726 10.7193 10.4578 11.0162 9.93062 11.5452L9.0091 12.4701C7.85245 13.6309 7.53171 15.3883 8.20291 16.8846L8.20482 16.8899C10.2592 21.2294 14.4347 25.4234 18.9171 27.5713L18.9197 27.5729C20.4104 28.2635 22.167 27.9506 23.3269 26.7907C23.6323 26.5134 23.9421 26.2035 24.2394 25.9053C24.7665 25.3762 25.0623 24.6589 25.0628 23.9105C25.0626 23.1626 24.7668 22.4445 24.2392 21.9161C23.8047 21.4801 23.34 21.0137 22.9268 20.5991ZM22.1323 21.3964L23.4447 22.7135C23.7612 23.0311 23.9383 23.4616 23.9382 23.9103C23.9386 24.3596 23.7603 24.7899 23.4441 25.1072C23.1573 25.3951 22.8583 25.6962 22.558 25.9683L22.5374 25.988C21.7113 26.817 20.4586 27.0414 19.3974 26.551C15.1422 24.511 11.1759 20.5317 9.22594 16.417C8.74891 15.3487 8.97887 14.0955 9.80433 13.2682L10.7259 12.3433C11.042 12.026 11.4708 11.8471 11.9185 11.8475C12.3656 11.8474 12.7945 12.0251 13.111 12.3428L14.4234 13.6599C14.8974 14.1356 14.8969 14.907 14.4226 15.383L14.4197 15.3858C13.5063 16.3026 13.5065 17.789 14.4197 18.7044L17.1058 21.4001C18.0179 22.3166 19.499 22.3168 20.4125 21.4001L20.4154 21.3972C20.8896 20.9212 21.6577 20.9213 22.1323 21.3964Z" fill="white"></path>
                                    </svg>
                                </div>
                                <div class="wptb-item--holder">
                                    <p class="wptb-item--description">Need Help</p>
                                    <h5 class="wptb-item--title"><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phoneIn); ?>"><?php echo e($phoneIn); ?></a></h5>
                                </div>
                            </div>
                        </div>

                        <div class="aside_open d-none d-xl-block">
                            <div class="aside-open--inner">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>

                        <button type="button" class="mr_menu_toggle d-xl-none">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Responsive Menu -->
    <div class="mr_menu">
        <button type="button" class="mr_menu_close"><i class="bi bi-x-lg"></i></button>
        <div class="logo">
            <a href="index.php" class="light_logo"><img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>"></a>
        </div>
        <div class="mr_navmenu">
            <ul class="main-menu">
                <li class="menu-item <?php echo in_array($currentPage, ['index.php', '']) ? 'active' : ''; ?>"><a href="index.php">Home</a></li>
                <li class="menu-item <?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>"><a href="about.php">About Us</a></li>
                <li class="menu-item <?php echo ($currentPage === 'services.php') ? 'active' : ''; ?>"><a href="services.php">Services</a></li>
                <li class="menu-item <?php echo ($currentPage === 'digital.php') ? 'active' : ''; ?>"><a href="digital.php">Digital Marketing</a></li>
                <li class="menu-item <?php echo ($currentPage === 'contact.php') ? 'active' : ''; ?>"><a href="contact.php">Contact</a></li>
            </ul>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
                <button type="button" class="pfs-theme-toggle mobile-toggle" aria-label="Toggle Light/Dark Theme">
                    <i class="bi bi-sun-fill icon-sun"></i>
                    <i class="bi bi-moon-stars-fill icon-moon"></i>
                    <span>Switch Theme</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Aside Drawer Information -->
    <div class="aside_info_wrapper">
        <button class="aside_close"><i class="bi bi-x-lg"></i></button>
        <div class="aside_logo">
            <a href="index.php"><img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>"></a>
        </div>
        <div class="aside_info_inner">
            <!-- Appearance / Theme Selector inside Drawer -->
            <div class="mb-4 d-flex align-items-center justify-content-between p-3 rounded-3" style="background: var(--pfs-glass-bg); border: 1px solid var(--pfs-glass-border);">
                <div>
                    <strong class="d-block" style="color: var(--pfs-heading-color); font-size: 0.92rem;">Theme Mode</strong>
                    <small style="color: var(--pfs-muted-color); font-size: 0.78rem;">Toggle Light / Dark interface</small>
                </div>
                <button type="button" class="pfs-theme-toggle m-0" aria-label="Toggle Light/Dark Theme">
                    <i class="bi bi-sun-fill icon-sun"></i>
                    <i class="bi bi-moon-stars-fill icon-moon"></i>
                </button>
            </div>

            <p style="text-align: justify;"><?php echo e(get_setting('footer_text')); ?></p>

            <div class="aside_info_inner_box">
                <div class="wptb-office">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--subtitle">Call Us Anytime</div>
                        <h5 class="wptb-item--title"><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phoneIn); ?>"><?php echo e($phoneIn); ?></a></h5>
                        <h5 class="wptb-item--title"><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phoneUs); ?>"><?php echo e($phoneUs); ?></a></h5>
                    </div>
                </div>

                <div class="wptb-office">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--subtitle">SEND US MAIL</div>
                        <h5 class="wptb-item--title"><a href="mailto:<?php echo e($emailInfo); ?>"><?php echo e($emailInfo); ?></a></h5>
                    </div>
                </div>

                <div class="wptb-office">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--subtitle">VISIT OUR WORKSHOP</div>
                        <h5 class="wptb-item--title"><?php echo e($address); ?></h5>
                    </div>
                </div>
            </div>

            <div class="social_sites">
                <ul class="d-flex align-items-center">
                    <?php if ($fbUrl && $fbUrl !== '#'): ?>
                        <li><a href="<?php echo e($fbUrl); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a></li>
                    <?php endif; ?>
                    <?php if ($xUrl && $xUrl !== '#'): ?>
                        <li><a href="<?php echo e($xUrl); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a></li>
                    <?php endif; ?>
                    <?php if ($instaUrl && $instaUrl !== '#'): ?>
                        <li><a href="<?php echo e($instaUrl); ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a></li>
                    <?php endif; ?>
                    <?php if ($linkedinUrl && $linkedinUrl !== '#'): ?>
                        <li><a href="<?php echo e($linkedinUrl); ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
