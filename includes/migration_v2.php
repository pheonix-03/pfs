<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

echo "Running migration...\n";

// 1. Extend hero_slides columns if not existing
try {
    $pdo->exec("ALTER TABLE hero_slides ADD COLUMN alignment TEXT DEFAULT 'left'");
} catch (Exception $e) {}
try {
    $pdo->exec("ALTER TABLE hero_slides ADD COLUMN overlay_opacity REAL DEFAULT 0.65");
} catch (Exception $e) {}
try {
    $pdo->exec("ALTER TABLE hero_slides ADD COLUMN badge_icon TEXT DEFAULT 'bi bi-stars'");
} catch (Exception $e) {}

// 2. Create page_sections table
$pdo->exec("CREATE TABLE IF NOT EXISTS page_sections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page TEXT NOT NULL,
    section_key TEXT NOT NULL,
    section_name TEXT NOT NULL,
    badge TEXT,
    title TEXT,
    subtitle TEXT,
    content TEXT,
    btn_text TEXT,
    btn_url TEXT,
    image_url TEXT,
    is_visible INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0,
    UNIQUE(page, section_key)
);");

// 3. Create testimonials table
$pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_name TEXT NOT NULL,
    company TEXT,
    role TEXT,
    avatar_url TEXT,
    rating INTEGER DEFAULT 5,
    review_text TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1
);");

// 4. Default global slider settings
$sliderDefaults = [
    'slider_autoplay' => '1',
    'slider_delay'    => '5000',
    'slider_effect'   => 'slide'
];
$stmtSet = $pdo->prepare("INSERT OR IGNORE INTO site_settings (key, value) VALUES (?, ?)");
foreach ($sliderDefaults as $k => $v) {
    $stmtSet->execute([$k, $v]);
}

// 5. Seed default page sections for ALL pages
$sections = [
    // HOME PAGE SECTIONS
    [
        'home', 'hero_slider', 'Homepage - Main Hero Slider',
        'BPO SOLUTIONS THAT DRIVE SUCCESS', 'Your Journey. Our Commitment.',
        'How we help your business thrive globally', '',
        'Our Services', 'services.php', '', 1, 1
    ],
    [
        'home', 'services_grid', 'Homepage - Services Intro',
        'How we help your business', 'Your Journey. Our Commitment.',
        'High-impact BPO & customer experience solutions engineered to enhance customer satisfaction while dramatically lowering overhead costs.',
        'View All Services', 'services.php', 'assets/img/background/bg-1.png', 1, 2
    ],
    [
        'home', 'marquee_model', 'Homepage - Global Reach & Business Model',
        'SLA PROMISE', 'Our Business Model',
        'Serving high-growth enterprises worldwide with dedicated multi-shift offshore teams.',
        'Learn More', 'about.php', '', 1, 3
    ],
    [
        'home', 'why_choose_us', 'Homepage - Why Choose Us',
        'Why Choose Us', 'RELIABLE OFFICE SETUP',
        'Enterprise-grade IT infrastructure, trained specialists, standardized processes, and 24/7 power and network redundancy.',
        'Our Infrastructure', 'about.php', '', 1, 4
    ],
    [
        'home', 'certificates_section', 'Homepage - Quality & Certifications',
        'ACCREDITATIONS', 'Our Certificates & Quality Monitoring',
        'Audited and certified by leading quality assurance bodies ensuring strict SLA compliance.',
        'View Proof', 'about.php', '', 1, 5
    ],
    [
        'home', 'challenges_solutions', 'Homepage - Challenge vs Solution',
        'Our strategy of work', 'The Challenge Vs Our Solution',
        'See the difference between struggling with in-house support bottlenecks and partnering with a dedicated offshore powerhouse.',
        'Get Solutions', 'contact.php', 'assets/img/background/bg-11.png', 1, 6
    ],
    [
        'home', 'bpo_growth', 'Homepage - BPO Solutions That Drive Success',
        'BPO SOLUTIONS THAT DRIVE SUCCESS', 'Your Growth. Our Support.',
        'From omnichannel voice customer service to deep technical troubleshooting and transaction monitoring.',
        'Get In Touch', 'contact.php', '', 1, 7
    ],
    [
        'home', 'ceo_message', 'Homepage - Message From The CEO',
        'LEADERSHIP VISION', 'Message From The CEO',
        'At Prime Fleet Services, we believe that world-class customer experience is the cornerstone of sustainable business growth. We do not just act as an outsourced vendor; we embed ourselves as dedicated brand ambassadors for each of our global partners.',
        'About Our Mission', 'about.php', 'assets/img/services/s1.png', 1, 8
    ],
    [
        'home', 'testimonials_section', 'Homepage - Client Testimonials',
        'CLIENT FEEDBACK', 'What Global Leaders Say About Us',
        'Read genuine reviews and success stories from companies scaling their support with Prime Fleet Services.',
        'Work With Us', 'contact.php', '', 1, 9
    ],
    [
        'home', 'quick_contact', 'Homepage - Quick Inquiry Form',
        'CONNECT WITH US', 'Feel Free To Ask Us Anything About Our Services',
        'Send us a message and one of our client directors will contact you within 2 hours.',
        'Send Message', 'contact.php', 'assets/img/background/bg-4.png', 1, 10
    ],

    // ABOUT PAGE SECTIONS
    [
        'about', 'hero', 'About Page - Hero Banner',
        'WHO WE ARE', 'About Prime Fleet Services',
        'Bridging the gap between global enterprises and their customers through exceptional omnichannel support.',
        'Our Story', '#about-story', '', 1, 1
    ],
    [
        'about', 'intro', 'About Page - Company Overview',
        'OPERATIONAL EXCELLENCE', 'Empowering Global Brands',
        'Established at the heart of the WEBEL IT Park, Prime Fleet Services has grown into a premier provider of BPO, customer support, and comprehensive digital marketing solutions.',
        'Contact Us', 'contact.php', 'assets/img/more/usp1.png', 1, 2
    ],

    // SERVICES PAGE SECTIONS
    [
        'services', 'hero', 'Services Page - Hero Header',
        'WHAT WE DO', 'Omnichannel BPO & Support Services',
        'Scalable, professional, and empathetic customer solutions designed for high performance.',
        'Contact Us', 'contact.php', '', 1, 1
    ],

    // DIGITAL MARKETING PAGE SECTIONS
    [
        'digital', 'hero', 'Digital Marketing - Hero Header',
        'DIGITAL AGENCY', 'High-Impact Digital Marketing & Growth',
        'From high-intent SEO and Google Ads to viral social campaigns, copywriting, and multimedia production.',
        'Request Proposal', 'contact.php', '', 1, 1
    ],

    // CONTACT PAGE SECTIONS
    [
        'contact', 'hero', 'Contact Page - Hero Header',
        'GET IN TOUCH', 'Let\'s Discuss Your Growth Strategy',
        'Our team is available 24x7 to answer your questions, demo our workflows, or craft a tailored pilot program.',
        'Call Now', 'tel:+919679897979', '', 1, 1
    ]
];

$stmtSec = $pdo->prepare("INSERT OR REPLACE INTO page_sections (page, section_key, section_name, badge, title, subtitle, content, btn_text, btn_url, image_url, is_visible, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($sections as $sec) {
    $stmtSec->execute($sec);
}

// 6. Seed default testimonials
$testimonials = [
    [
        'Michael Vance', 'Nexus Logix Inc.', 'VP of Operations', 'assets/img/services/s1.png', 5,
        'Prime Fleet Services completely revolutionized our support desk. Response times dropped by 75% within the first 30 days, and our customer satisfaction scores jumped to 98%. Their agents feel like an authentic in-house team!', 1
    ],
    [
        'Sarah Jenkins', 'Aura Retail Group', 'Head of Customer Experience', 'assets/img/services/s2.png', 5,
        'The transition to Prime Fleet Services was seamless. Their team handles all our peak holiday volume without a single backlog. Professional, reliable, and exceptionally cost-effective.', 2
    ],
    [
        'David Chen', 'Apex Mobility Ltd.', 'Managing Director', 'assets/img/services/s3.png', 5,
        'Their digital marketing and non-voice chat support teams are world-class. We scaled from 5 to 30 dedicated agents in just four months with zero downtime.', 3
    ]
];

$stmtTest = $pdo->prepare("INSERT OR IGNORE INTO testimonials (client_name, company, role, avatar_url, rating, review_text, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($testimonials as $t) {
    $stmtTest->execute($t);
}

echo "Migration completed successfully!\n";
