<?php
/**
 * Prime Fleet Services - Database Schema and Seed Initialization
 */

function init_sqlite_database(PDO $pdo) {
    // 1. Admins Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_login DATETIME
    );");

    // 2. Site Settings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");

    // 3. Hero Slides
    $pdo->exec("CREATE TABLE IF NOT EXISTS hero_slides (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        subtitle TEXT,
        badge TEXT,
        bg_image TEXT,
        btn_text TEXT,
        btn_url TEXT,
        btn2_text TEXT,
        btn2_url TEXT,
        sort_order INTEGER DEFAULT 0,
        status INTEGER DEFAULT 1
    );");

    // 4. Services
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT DEFAULT 'bpo',
        title TEXT NOT NULL,
        slug TEXT,
        icon_class TEXT,
        short_desc TEXT,
        full_desc TEXT,
        image_url TEXT,
        sort_order INTEGER DEFAULT 0,
        status INTEGER DEFAULT 1
    );");

    // 5. Why Choose Us / Features
    $pdo->exec("CREATE TABLE IF NOT EXISTS why_choose_us (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        subtitle TEXT,
        description TEXT,
        icon TEXT,
        image_url TEXT,
        sort_order INTEGER DEFAULT 0
    );");

    // 6. Statistics
    $pdo->exec("CREATE TABLE IF NOT EXISTS statistics (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        value TEXT NOT NULL,
        suffix TEXT,
        description TEXT,
        sort_order INTEGER DEFAULT 0
    );");

    // 7. Certificates
    $pdo->exec("CREATE TABLE IF NOT EXISTS certificates (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        image_url TEXT NOT NULL,
        certificate_no TEXT,
        issuing_authority TEXT DEFAULT '',
        issue_date TEXT DEFAULT '',
        expiry_date TEXT DEFAULT '',
        description TEXT DEFAULT '',
        verification_url TEXT DEFAULT '',
        is_featured INTEGER DEFAULT 0,
        sort_order INTEGER DEFAULT 0,
        status INTEGER DEFAULT 1
    );");

    // 8. Challenges & Solutions
    $pdo->exec("CREATE TABLE IF NOT EXISTS challenges_solutions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL, /* 'challenge' or 'solution' */
        title TEXT NOT NULL,
        description TEXT,
        icon TEXT,
        sort_order INTEGER DEFAULT 0
    );");

    // 9. Pages Content (About, CEO Message, etc.)
    $pdo->exec("CREATE TABLE IF NOT EXISTS pages_content (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        page TEXT NOT NULL,
        section_key TEXT NOT NULL,
        title TEXT,
        subtitle TEXT,
        content TEXT,
        image_url TEXT,
        extra_json TEXT
    );");

    // 10. Inquiries (Contact submissions)
    $pdo->exec("CREATE TABLE IF NOT EXISTS inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT,
        subject TEXT,
        message TEXT NOT NULL,
        is_read INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");

    // 11. Newsletter Subscribers
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");

    // Default Admin (admin / admin123)
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO admins (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([
        'admin',
        'connect@primefleetservices.co.in',
        password_hash('admin123', PASSWORD_BCRYPT)
    ]);

    // Default Site Settings
    $settings = [
        'site_name' => 'PFS - Prime Fleet Services',
        'tagline' => 'Your Journey. Our Commitment.',
        'logo_url' => 'assets/img/logo.png',
        'favicon_url' => 'assets/img/favicon.png',
        'phone_in' => '+91-9679897979',
        'phone_us' => '(747) 236-2181',
        'email_support' => 'connect@primefleetservices.co.in',
        'email_info' => 'info@primefleetservices.co.in',
        'address' => 'WEBEL IT PARK, Satellite Township, Kalyanpur, Asansol, West Bengal 713305',
        'working_hours' => '24 X 7 Live',
        'facebook_url' => 'https://www.facebook.com/profile.php?id=61590988964177',
        'instagram_url' => 'https://www.instagram.com/primefleet.services/',
        'twitter_url' => 'https://x.com/primefleetsrv',
        'youtube_url' => 'https://www.youtube.com/@PrimeFleetServices',
        'reddit_url' => 'https://www.reddit.com/user/PrimeFleetServices/',
        'linkedin_url' => 'https://linkedin.com/',
        'whatsapp_number' => '919679897979',
        'whatsapp_message' => "Hi! I'm interested in your services and would love to know more. Could you please provide additional details?",
        'meta_description' => 'Prime Fleet Services - Top tier BPO solutions, customer support, non-voice support, and comprehensive digital marketing agency.',
        'footer_text' => 'We offer scalable and professional customer support that enhances satisfaction while reducing costs. We also ensure that your business delivers exceptional experiences and achieves lasting success.',
        'copyright_text' => 'Copyright &copy; ' . date('Y') . ' Prime Fleet Services. All Rights Reserved.'
    ];

    $stmtSet = $pdo->prepare("INSERT OR REPLACE INTO site_settings (key, value) VALUES (?, ?)");
    foreach ($settings as $k => $v) {
        $stmtSet->execute([$k, $v]);
    }

    // Default Hero Slides
    $slides = [
        [
            'title' => 'Your Journey. Our Commitment.',
            'subtitle' => 'How we help your business thrive globally',
            'badge' => 'BPO SOLUTIONS THAT DRIVE SUCCESS',
            'bg_image' => 'assets/img/background/bg-11.jpg',
            'btn_text' => 'Our Services',
            'btn_url' => 'services.php',
            'btn2_text' => 'Contact Us',
            'btn2_url' => 'contact.php',
            'sort_order' => 1
        ],
        [
            'title' => 'Scale Your Business With 24/7 Support',
            'subtitle' => 'Dedicated offshore teams engineered for peak operational performance',
            'badge' => 'WORLD-CLASS OUTSOURCING',
            'bg_image' => 'assets/img/background/bg-11.jpg',
            'btn_text' => 'Explore Solutions',
            'btn_url' => 'services.php',
            'btn2_text' => 'Get In Touch',
            'btn2_url' => 'contact.php',
            'sort_order' => 2
        ]
    ];

    $stmtSlide = $pdo->prepare("INSERT INTO hero_slides (title, subtitle, badge, bg_image, btn_text, btn_url, btn2_text, btn2_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($slides as $s) {
        $stmtSlide->execute([$s['title'], $s['subtitle'], $s['badge'], $s['bg_image'], $s['btn_text'], $s['btn_url'], $s['btn2_text'], $s['btn2_url'], $s['sort_order']]);
    }

    // Default Services
    $services = [
        // BPO & Support Services
        ['bpo', 'Voice Support', 'voice-support', 'bi bi-headset', 'Clear, professional, and empathetic telephonic support tailored for global enterprises.', 'We provide high-touch voice support solutions including customer inquiries, order processing, outbound customer follow-ups, and omnichannel escalation management with native and bilingual professionals.', 'assets/img/services/srv_voice_support.jpg', 1],
        ['bpo', 'Non-Voice Support', 'non-voice-support', 'bi bi-chat-dots', 'Lightning-fast live chat, email ticketing, and back-office documentation management.', 'Our non-voice team provides rapid, accurate resolution across live chat software, email tickets, CRM updates, and transaction monitoring with zero backlog.', 'assets/img/services/srv_non_voice.jpg', 2],
        ['bpo', 'Professional Team', 'professional-team', 'bi bi-people', 'Highly vetted, rigorously trained domain specialists dedicated to your brand.', 'Every specialist undergoes rigorous training on your workflows, communication style, software systems, and quality frameworks to feel like an in-house extension.', 'assets/img/services/srv_professional_team.jpg', 3],
        ['bpo', 'Quality & Monitoring', 'quality-monitoring', 'bi bi-shield-check', 'Continuous QA evaluations, call calibrations, and performance scorecards.', 'Real-time call listening, customer satisfaction tracking (CSAT, NPS), and actionable reporting to ensure your brand standards are consistently exceeded.', 'assets/img/services/srv_quality_monitoring.jpg', 4],
        ['bpo', 'Scalable Solutions', 'scalable-solutions', 'bi bi-graph-up-arrow', 'Flexible staffing models that grow effortlessly with your business peaks and seasons.', 'Whether you need 2 dedicated agents or 100+ multi-tier specialists, our infrastructure scales up or down with maximum agility and minimal overhead.', 'assets/img/services/srv_scalable_solutions.jpg', 5],
        ['bpo', 'Measurable Results', 'measurable-results', 'bi bi-pie-chart', 'Data-driven insights, transparent KPIs, and constant operational optimization.', 'We deliver detailed analytics, first-contact resolution rates, average handle time optimizations, and executive summary reports.', 'assets/img/services/srv_measurable_results.jpg', 6],
        
        // Digital Marketing Services
        ['digital', 'Search Engine Optimization (SEO)', 'seo-services', 'bi bi-search', 'Dominate Google search results with organic ranking strategies that bring steady qualified leads.', 'Our SEO campaigns cover technical audits, high-intent keyword targeting, on-page optimization, content strategy, and authoritative backlink building.', 'assets/img/digital-services/seo-dashboard.jpg', 7],
        ['digital', 'Google Ads & PPC Management', 'google-ppc', 'bi bi-cursor-fill', 'Targeted pay-per-click campaigns that maximize ROI and drive immediate buyer conversions.', 'From Search and Display to Performance Max campaigns, our certified Google Ads managers optimize bids, negative keywords, and landing page conversions.', 'assets/img/digital-services/google-ppc.jpg', 8],
        ['digital', 'Meta & Social Advertising', 'meta-ads', 'bi bi-share-fill', 'Hyper-targeted Facebook, Instagram, and LinkedIn ads that engage and convert prospects.', 'We design thumb-stopping creatives, test winning audience lookalikes, and optimize full-funnel remarketing loops for consistent sales pipelines.', 'assets/img/digital-services/meta-advertising.jpg', 9],
        ['digital', 'High-Converting Copywriting', 'copywriting', 'bi bi-pen-fill', 'Persuasive messaging, sales funnels, and blog content that moves audiences to take action.', 'Engaging copy that connects with your target persona, clearly conveys your value proposition, and compels visitors to convert into loyal clients.', 'assets/img/digital-services/copywriting.jpg', 10],
        ['digital', 'Vector & Graphic Design', 'vector-design', 'bi bi-palette-fill', 'Modern visual identities, social media creatives, brochures, and brand style guides.', 'Our creative studio designs striking marketing collateral, infographics, presentation decks, and web graphics aligned with your brand identity.', 'assets/img/digital-services/vector-design.jpg', 11],
        ['digital', 'Video Production & Editing', 'video-editing', 'bi bi-camera-reels-fill', 'Short-form reels, explainer videos, customer testimonials, and brand commercials.', 'High-retention video content crafted with seamless transitions, custom sound design, captions, and motion graphics optimized for all social platforms.', 'assets/img/digital-services/video-post.jpg', 12]
    ];

    $stmtServ = $pdo->prepare("INSERT INTO services (category, title, slug, icon_class, short_desc, full_desc, image_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($services as $srv) {
        $stmtServ->execute($srv);
    }

    // Default Why Choose Us Points
    $whyUs = [
        ['PROFESSIONAL WORKSPACE', 'Modern Facilities', 'Equipped with enterprise-grade infrastructure, ergonomic seating, dual-monitor workstations, and noise-isolated calling floors.', 'bi bi-building', 'assets/img/more/1.png', 1],
        ['TRAINED PROFESSIONALS', 'Skilled Specialists', 'Every team member is trained in active listening, conflict resolution, technical empathy, and CRM mastery.', 'bi bi-award', 'assets/img/more/2.png', 2],
        ['STANDARDIZED PROCESSES', 'Operational Rigor', 'Strict SOPs and call flows designed in collaboration with client leadership for uniform customer experience.', 'bi bi-diagram-3', 'assets/img/more/usp1.png', 3],
        ['QUALITY ASSURANCE', 'Continuous Auditing', 'Multi-tier QA monitoring and weekly coaching sessions to maintain industry-leading service levels.', 'bi bi-check2-circle', 'assets/img/more/usp2.png', 4],
        ['SMOOTH OPERATIONS', '24/7 Redundancy', 'Redundant power backups, high-speed leased-line fiber internet, and seamless failover systems.', 'bi bi-gear-wide-connected', 'assets/img/more/car.png', 5],
        ['DATA PRIVACY & SECURITY', 'Enterprise Protection', 'Compliant with modern data security protocols, secure VPNs, NDA enforcement, and clean desk policies.', 'bi bi-shield-lock', 'assets/img/more/5.png', 6]
    ];

    $stmtWhy = $pdo->prepare("INSERT INTO why_choose_us (title, subtitle, description, icon, image_url, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($whyUs as $w) {
        $stmtWhy->execute($w);
    }

    // Default Certificates
    $certs = [
        ['NLA Certificate of Compliance 01', 'pfsadmin/upload/nla-certificate 01).jpeg', 'NLA-01', 1],
        ['NLA Certificate of Quality 02', 'pfsadmin/upload/nla-certificate 02.jpeg', 'NLA-02', 2],
        ['NLA Business Authorization 04', 'pfsadmin/upload/nla-certificate 04.jpeg', 'NLA-04', 3],
        ['NLA Operational Standards 05', 'pfsadmin/upload/nla-certificate 05.jpeg', 'NLA-05', 4],
        ['NLA Global Support Certified 06', 'pfsadmin/upload/nla-certificate 06.jpeg', 'NLA-06', 5],
        ['NLA Security Accreditation 07', 'pfsadmin/upload/nla-certificate 07.jpeg', 'NLA-07', 6]
    ];

    $stmtCert = $pdo->prepare("INSERT INTO certificates (title, image_url, certificate_no, sort_order) VALUES (?, ?, ?, ?)");
    foreach ($certs as $c) {
        $stmtCert->execute($c);
    }

    // Default Statistics
    $stats = [
        ['Client Satisfaction', '100', '%', 'Highest-rated BPO partnership satisfaction', 1],
        ['Service Delivery Result', '100', '%', 'Reliable execution meeting all agreed SLA targets', 2],
        ['Coverage Available', '24', '/7', 'Uninterrupted live operational support worldwide', 3],
        ['Global Reach', '5', ' Continents', 'Serving clients across US, UK, Canada, Australia & Middle East', 4]
    ];

    $stmtStat = $pdo->prepare("INSERT INTO statistics (title, value, suffix, description, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($stats as $st) {
        $stmtStat->execute($st);
    }

    // Default Challenges vs Solutions
    $items = [
        ['challenge', 'SLOW RESPONSE TIMES', 'Overwhelmed in-house staff leads to frustrated customers and long waiting queues.', 'bi bi-hourglass-bottom', 1],
        ['challenge', 'RISING COSTS', 'High recruitment, onboarding, overhead, and retention costs for domestic support teams.', 'bi bi-cash-stack', 2],
        ['challenge', 'CUSTOMER REVIEW', 'Negative online reviews and declining customer loyalty due to inconsistent support.', 'bi bi-star-half', 3],
        ['challenge', 'POOR CUSTOMER SATISFACTION', 'Inflexible support hours and lack of dedicated specialized talent.', 'bi bi-emoji-frown', 4],

        ['solution', 'PROFESSIONAL EXPERTISE', 'Rigorous onboarding and continuous QA to handle calls, emails, and live chats proficiently.', 'bi bi-award-fill', 1],
        ['solution', 'REDUCED COSTS', 'Save up to 60% in overheads while dramatically increasing customer satisfaction and loyalty.', 'bi bi-piggy-bank-fill', 2],
        ['solution', 'SCALABLE SOLUTIONS', 'Ramp agent seats up or down seamlessly based on demand spikes and seasonal volume.', 'bi bi-arrow-up-right-circle-fill', 3],
        ['solution', 'ENHANCED SATISFACTION', 'Instant response times, 24/7 coverage, and delightful customer service interactions.', 'bi bi-emoji-smile-fill', 4]
    ];

    $stmtCS = $pdo->prepare("INSERT INTO challenges_solutions (type, title, description, icon, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $it) {
        $stmtCS->execute($it);
    }

    // Pages Content
    $pages = [
        ['home', 'ceo_message', 'Message From The CEO', 'Our Vision For Your Growth', "At Prime Fleet Services, we believe that world-class customer experience is the cornerstone of sustainable business growth. We do not just act as an outsourced vendor; we embed ourselves as dedicated brand ambassadors for each of our global partners.\n\nOur advanced IT Park setup, rigorous training methodologies, and transparent SLA tracking ensure your customers receive empathetic, lightning-fast, and precise solutions 24 hours a day, 365 days a year.", 'assets/img/services/s1.png', ''],
        ['about', 'company_overview', 'About Prime Fleet Services', 'Empowering Global Enterprises Through Exceptional Customer Experiences', "Established at the heart of the WEBEL IT Park, Prime Fleet Services has grown into a premier provider of BPO, omnichannel customer support, and comprehensive digital marketing solutions. We bridge the gap between businesses and their customers across North America, Europe, Australia, and the Middle East.\n\nWith world-class telecommunications infrastructure, redundant fiber networks, and continuous security compliance, we ensure operational continuity and measurable success for high-growth enterprises.", 'assets/img/more/usp1.png', '']
    ];

    $stmtPage = $pdo->prepare("INSERT INTO pages_content (page, section_key, title, subtitle, content, image_url, extra_json) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($pages as $p) {
        $stmtPage->execute($p);
    }
}
