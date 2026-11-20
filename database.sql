-- Prime Fleet Services (PFS) Database Schema
-- Compatible with MySQL 5.7+ / MariaDB 10.3+ and SQLite

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin (admin / admin123)
INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'connect@primefleetservices.co.in', '$2y$10$wH60s7iFh2v3cK8d7jC0/O9Q9Y7O4t.9K6P1f2E.e5c/v1z4y6M8W')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 2. Site Settings Table
CREATE TABLE IF NOT EXISTS `site_settings` (
  `key` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site_settings` (`key`, `value`) VALUES
('site_name', 'PFS - Prime Fleet Services'),
('tagline', 'Your Journey. Our Commitment.'),
('logo_url', 'assets/img/logo.png'),
('favicon_url', 'assets/img/favicon.png'),
('phone_in', '+91-9679897979'),
('phone_us', '(747) 236-2181'),
('email_support', 'connect@primefleetservices.co.in'),
('email_info', 'info@primefleetservices.co.in'),
('address', 'WEBEL IT PARK, Satellite Township, Kalyanpur, Asansol, West Bengal 713305'),
('working_hours', '24 X 7 Live'),
('facebook_url', 'https://www.facebook.com/profile.php?id=61590988964177'),
('instagram_url', 'https://www.instagram.com/primefleet.services/'),
('twitter_url', 'https://x.com/primefleetsrv'),
('youtube_url', 'https://www.youtube.com/@PrimeFleetServices'),
('reddit_url', 'https://www.reddit.com/user/PrimeFleetServices/'),
('linkedin_url', 'https://linkedin.com/'),
('whatsapp_number', '919679897979'),
('whatsapp_message', 'Hi! I am interested in your services and would love to know more. Could you please provide additional details?'),
('meta_description', 'Prime Fleet Services - Top tier BPO solutions, customer support, non-voice support, and comprehensive digital marketing agency.'),
('footer_text', 'We offer scalable and professional customer support that enhances satisfaction while reducing costs. We also ensure that your business delivers exceptional experiences and achieves lasting success.'),
('copyright_text', 'Copyright &copy; 2026 Prime Fleet Services. All Rights Reserved.'),
('slider_autoplay', '1'),
('slider_delay', '5000'),
('slider_effect', 'slide')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

-- 3. Hero Slides Table (with deep alignment & overlay customization)
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `badge_icon` varchar(50) DEFAULT 'bi bi-shield-check',
  `alignment` varchar(20) DEFAULT 'left',
  `overlay_opacity` int(11) DEFAULT 70,
  `bg_image` varchar(255) DEFAULT NULL,
  `btn_text` varchar(50) DEFAULT NULL,
  `btn_url` varchar(255) DEFAULT NULL,
  `btn2_text` varchar(50) DEFAULT NULL,
  `btn2_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `badge`, `badge_icon`, `alignment`, `overlay_opacity`, `bg_image`, `btn_text`, `btn_url`, `btn2_text`, `btn2_url`, `sort_order`, `status`) VALUES
(1, 'Your Journey. Our Commitment.', 'How we help your business thrive globally with omnichannel BPO support', 'BPO SOLUTIONS THAT DRIVE SUCCESS', 'bi bi-shield-check', 'left', 75, 'assets/img/slider/slide-1.jpg', 'Our Services', 'services.php', 'Contact Us', 'contact.php', 1, 1),
(2, 'Scale Your Fleet With 24/7 Dispatch', 'Dedicated offshore dispatch and customer support specialists ready to integrate', 'WORLD-CLASS DISPATCHING', 'bi bi-headset', 'left', 70, 'assets/img/slider/slide-2.jpg', 'Explore Solutions', 'services.php', 'Get In Touch', 'contact.php', 2, 1),
(3, 'High-Impact Digital Marketing Agency', 'SEO, PPC campaigns, Meta Ads, and visual creative content that accelerates growth', 'DIGITAL GROWTH AGENCY', 'bi bi-graph-up-arrow', 'left', 70, 'assets/img/slider/slide-3.jpg', 'Digital Services', 'digital.php', 'Contact Us', 'contact.php', 3, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `bg_image`=VALUES(`bg_image`);

-- 4. Services Table
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT 'bpo',
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `icon_class` varchar(100) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `full_desc` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`id`, `category`, `title`, `slug`, `icon_class`, `short_desc`, `full_desc`, `image_url`, `sort_order`, `status`) VALUES
(1, 'bpo', 'Voice Support', 'voice-support', 'bi bi-headset', 'Clear, professional, and empathetic telephonic support tailored for global enterprises.', 'We provide high-touch voice support solutions including customer inquiries, order processing, outbound customer follow-ups, and omnichannel escalation management with native and bilingual professionals.', 'assets/img/services/1.jpg', 1, 1),
(2, 'bpo', 'Non-Voice Support', 'non-voice-support', 'bi bi-chat-dots', 'Lightning-fast live chat, email ticketing, and back-office documentation management.', 'Our non-voice team provides rapid, accurate resolution across live chat software, email tickets, CRM updates, and transaction monitoring with zero backlog.', 'assets/img/services/2.jpg', 2, 1),
(3, 'bpo', 'Professional Team', 'professional-team', 'bi bi-people', 'Highly vetted, rigorously trained domain specialists dedicated to your brand.', 'Every specialist undergoes rigorous training on your workflows, communication style, software systems, and quality frameworks to feel like an in-house extension.', 'assets/img/services/3.jpg', 3, 1),
(4, 'bpo', 'Quality & Monitoring', 'quality-monitoring', 'bi bi-shield-check', 'Continuous QA evaluations, call calibrations, and performance scorecards.', 'Real-time call listening, customer satisfaction tracking (CSAT, NPS), and actionable reporting to ensure your brand standards are consistently exceeded.', 'assets/img/services/4.jpg', 4, 1),
(5, 'bpo', 'Scalable Solutions', 'scalable-solutions', 'bi bi-graph-up-arrow', 'Flexible staffing models that grow effortlessly with your business peaks and seasons.', 'Whether you need 2 dedicated agents or 100+ multi-tier specialists, our infrastructure scales up or down with maximum agility and minimal overhead.', 'assets/img/services/5.jpg', 5, 1),
(6, 'bpo', 'Measurable Results', 'measurable-results', 'bi bi-pie-chart', 'Data-driven insights, transparent KPIs, and constant operational optimization.', 'We deliver detailed analytics, first-contact resolution rates, average handle time optimizations, and executive summary reports.', 'assets/img/services/6.jpg', 6, 1),
(7, 'digital', 'Search Engine Optimization (SEO)', 'seo-services', 'bi bi-search', 'Dominate Google search results with organic ranking strategies that bring steady qualified leads.', 'Our SEO campaigns cover technical audits, high-intent keyword targeting, on-page optimization, content strategy, and authoritative backlink building.', 'assets/img/digital-services/seo-dashboard.jpg', 7, 1),
(8, 'digital', 'Google Ads & PPC Management', 'google-ppc', 'bi bi-cursor-fill', 'Targeted pay-per-click campaigns that maximize ROI and drive immediate buyer conversions.', 'From Search and Display to Performance Max campaigns, our certified Google Ads managers optimize bids, negative keywords, and landing page conversions.', 'assets/img/digital-services/google-ppc.jpg', 8, 1),
(9, 'digital', 'Social Media Marketing (SMM)', 'social-media', 'bi bi-share', 'Amplify brand awareness and create community engagement across Meta, LinkedIn, and Instagram.', 'End-to-end social media handling including high-production content calendars, community moderation, trend research, and targeted viral reel campaigns.', 'assets/img/digital-services/meta-advertising.jpg', 9, 1),
(10, 'digital', 'Content Writing & Copywriting', 'content-writing', 'bi bi-pencil-square', 'Persuasive, conversion-driven copy that engages visitors and converts them into loyal clients.', 'Conversion copywriting for landing pages, technical fleet articles, whitepapers, email newsletters, and ad creatives designed to resonate with executives.', 'assets/img/digital-services/content-writing.jpg', 10, 1),
(11, 'digital', 'Graphic Design & Branding', 'graphic-design', 'bi bi-palette', 'Stunning corporate branding, marketing collateral, pitch decks, and digital banners.', 'Professional vector designs, complete brand identity kits, social media templates, brochures, trade show banners, and presentation decks.', 'assets/img/digital-services/graphic-design.jpg', 11, 1),
(12, 'digital', 'Video Editing & Production', 'video-editing', 'bi bi-camera-video', 'High-retention video production for YouTube, TikTok, Reels, and corporate promos.', 'Cinematic video cutting, subtitles, sound design, motion graphics, and commercial reel assembly to tell your brand story memorably.', 'assets/img/digital-services/video-editing.jpg', 12, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 5. Certificates Table
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `issuer` varchar(150) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `certificates` (`id`, `title`, `image_url`, `issuer`, `sort_order`, `status`) VALUES
(1, 'Certificate of Incorporation', 'assets/img/more/incorporation.jpg', 'Ministry of Corporate Affairs, Govt of India', 1, 1),
(2, 'MSME / Udyam Registration', 'assets/img/more/udyam.jpg', 'Ministry of MSME, Govt of India', 2, 1),
(3, 'ISO 9001:2015 Quality Management', 'assets/img/more/iso.jpg', 'International Quality Certification', 3, 1),
(4, 'Startup India Recognition', 'assets/img/more/startup.jpg', 'DPIIT, Govt of India', 4, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 6. Statistics Table
CREATE TABLE IF NOT EXISTS `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `metric_value` varchar(50) NOT NULL,
  `prefix` varchar(10) DEFAULT '',
  `suffix` varchar(10) DEFAULT '+',
  `icon_class` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `statistics` (`id`, `title`, `metric_value`, `prefix`, `suffix`, `icon_class`, `sort_order`) VALUES
(1, 'SLA Resolution Rate', '99.8', '', '%', 'bi bi-patch-check-fill', 1),
(2, 'Customer Interactions', '1.2', '', 'M+', 'bi bi-headset', 2),
(3, 'Enterprise Retention Rate', '98', '', '%', 'bi bi-shield-fill-check', 3),
(4, 'Average Cost Savings', '60', '', '%', 'bi bi-graph-up-arrow', 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 7. Challenges & Solutions Table
CREATE TABLE IF NOT EXISTS `challenges_solutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('challenge','solution') NOT NULL DEFAULT 'challenge',
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `challenges_solutions` (`id`, `type`, `title`, `description`, `icon`, `sort_order`) VALUES
(1, 'challenge', 'SLOW RESPONSE TIMES', 'Overwhelmed in-house staff leads to frustrated customers and long waiting queues.', 'bi bi-hourglass-bottom', 1),
(2, 'challenge', 'RISING COSTS', 'High recruitment, onboarding, overhead, and retention costs for domestic support teams.', 'bi bi-cash-stack', 2),
(3, 'challenge', 'CUSTOMER REVIEW', 'Negative online reviews and declining customer loyalty due to inconsistent support.', 'bi bi-star-half', 3),
(4, 'challenge', 'POOR CUSTOMER SATISFACTION', 'Inflexible support hours and lack of dedicated specialized talent.', 'bi bi-emoji-frown', 4),
(5, 'solution', 'PROFESSIONAL EXPERTISE', 'Rigorous onboarding and continuous QA to handle calls, emails, and live chats proficiently.', 'bi bi-award-fill', 1),
(6, 'solution', 'REDUCED COSTS', 'Save up to 60% in overheads while dramatically increasing customer satisfaction and loyalty.', 'bi bi-piggy-bank-fill', 2),
(7, 'solution', 'SCALABLE SOLUTIONS', 'Ramp agent seats up or down seamlessly based on demand spikes and seasonal volume.', 'bi bi-arrow-up-right-circle-fill', 3),
(8, 'solution', 'ENHANCED SATISFACTION', 'Instant response times, 24/7 coverage, and delightful customer service interactions.', 'bi bi-emoji-smile-fill', 4)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 8. Universal Page Sections Table (Full Dynamic Control Across All Pages)
CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `section_key` varchar(50) NOT NULL,
  `section_name` varchar(150) NOT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `btn_text` varchar(100) DEFAULT NULL,
  `btn_url` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_section_idx` (`page`, `section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `page_sections` (`page`, `section_key`, `section_name`, `badge`, `title`, `subtitle`, `content`, `btn_text`, `btn_url`, `image_url`, `is_visible`, `sort_order`) VALUES
('home', 'hero_slider', 'Homepage - Main Hero Slider', 'BPO SOLUTIONS THAT DRIVE SUCCESS', 'Your Journey. Our Commitment.', 'How we help your business thrive globally', '', 'Our Services', 'services.php', '', 1, 1),
('home', 'services_grid', 'Homepage - Services Intro', 'How we help your business', 'Your Journey. Our Commitment.', 'High-impact BPO & customer experience solutions engineered to enhance customer satisfaction while dramatically lowering overhead costs.', '', 'View All Services', 'services.php', 'assets/img/background/bg-1.png', 1, 2),
('home', 'marquee_model', 'Homepage - Global Reach & Business Model', 'SLA PROMISE', 'Our Business Model', 'Serving high-growth enterprises worldwide with dedicated multi-shift offshore teams.', '', 'Learn More', 'about.php', '', 1, 3),
('home', 'why_choose_us', 'Homepage - Why Choose Us', 'Why Choose Us', 'RELIABLE OFFICE SETUP', 'Enterprise-grade IT infrastructure, trained specialists, standardized processes, and 24/7 power and network redundancy.', '', 'Our Infrastructure', 'about.php', 'assets/img/background/bg-why-choose.jpg', 1, 4),
('home', 'certificates_section', 'Homepage - Quality & Certifications', 'ACCREDITATIONS', 'Our Certificates & Quality Monitoring', 'Audited and certified by leading quality assurance bodies ensuring strict SLA compliance.', '', 'View Proof', 'about.php', '', 1, 5),
('home', 'challenges_solutions', 'Homepage - Challenge vs Solution', 'Our strategy of work', 'The Challenge Vs Our Solution', 'See the difference between struggling with in-house support bottlenecks and partnering with a dedicated offshore powerhouse.', '', 'Get Solutions', 'contact.php', 'assets/img/background/bg-11.png', 1, 6),
('home', 'bpo_growth', 'Homepage - BPO Solutions That Drive Success', 'BPO SOLUTIONS THAT DRIVE SUCCESS', 'Your Growth. Our Support.', 'From omnichannel voice customer service to deep technical troubleshooting and transaction monitoring.', '', 'Get In Touch', 'contact.php', '', 1, 7),
('home', 'ceo_message', 'Homepage - Message From The CEO', 'LEADERSHIP VISION', 'Message From The CEO', 'At Prime Fleet Services, we believe that world-class customer experience is the cornerstone of sustainable business growth. We do not just act as an outsourced vendor; we embed ourselves as dedicated brand ambassadors for each of our global partners.', 'Our advanced IT Park setup, rigorous training methodologies, and transparent SLA tracking ensure your customers receive empathetic, lightning-fast, and precise solutions 24 hours a day, 365 days a year.', 'About Our Mission', 'about.php', 'assets/img/services/s1.png', 1, 8),
('home', 'testimonials_section', 'Homepage - Client Testimonials', 'CLIENT FEEDBACK', 'What Global Leaders Say About Us', 'Read genuine reviews and success stories from companies scaling their support with Prime Fleet Services.', '', 'Work With Us', 'contact.php', '', 1, 9),
('home', 'quick_contact', 'Homepage - Quick Inquiry Form', 'CONNECT WITH US', 'Feel Free To Ask Us Anything About Our Services', 'Send us a message and one of our client directors will contact you within 2 hours.', '', 'Send Message', 'contact.php', 'assets/img/background/bg-4.png', 1, 10),
('about', 'hero', 'About Page - Hero Banner', 'WHO WE ARE', 'About Prime Fleet Services', 'Bridging the gap between global enterprises and their customers through exceptional omnichannel support.', '', 'Our Story', '#about-story', 'assets/img/background/abouthead.jpg', 1, 1),
('about', 'intro', 'About Page - Company Overview', 'About us', 'About Prime Fleet Services', 'Prime Fleet Services is a specialized transportation support company providing professional Dispatch, Reservation Management, and Customer Service solutions to limousine, chauffeur, executive transportation, and party bus operators.', '', 'Contact Us', 'contact.php', 'assets/img/more/5.png', 1, 2),
('services', 'hero', 'Services Page - Hero Header', 'Why Choose Us', 'Our Services', 'Scalable, professional, and empathetic customer solutions designed for high performance.', '', 'Contact Us', 'contact.php', 'assets/img/background/servicehead.jpg', 1, 1),
('digital', 'hero', 'Digital Marketing - Hero Header', 'DIGITAL AGENCY', 'Digital Marketing', 'From high-intent SEO and Google Ads to viral social campaigns, copywriting, and multimedia production.', '', 'Request Proposal', 'contact.php', 'assets/img/background/digital-background-page-header.jpg', 1, 1),
('contact', 'hero', 'Contact Page - Hero Header', 'GET IN TOUCH', 'Contact Us', 'Our team is available 24x7 to answer your questions, demo our workflows, or craft a tailored pilot program.', '', 'Call Now', 'tel:+919679897979', 'assets/img/background/contacthead.jpg', 1, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `subtitle`=VALUES(`subtitle`);

-- 9. Testimonials Table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `review_text` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `testimonials` (`id`, `client_name`, `company`, `role`, `avatar_url`, `rating`, `review_text`, `sort_order`, `status`) VALUES
(1, 'Michael Vance', 'Nexus Logix Inc.', 'VP of Operations', 'assets/img/services/s1.png', 5, 'Prime Fleet Services completely revolutionized our support desk. Response times dropped by 75% within the first 30 days, and our customer satisfaction scores jumped to 98%. Their agents feel like an authentic in-house team!', 1, 1),
(2, 'Sarah Jenkins', 'Aura Retail Group', 'Head of Customer Experience', 'assets/img/services/s2.png', 5, 'The transition to Prime Fleet Services was seamless. Their team handles all our peak holiday volume without a single backlog. Professional, reliable, and exceptionally cost-effective.', 2, 1),
(3, 'David Chen', 'Apex Mobility Ltd.', 'Managing Director', 'assets/img/services/s3.png', 5, 'Their digital marketing and non-voice chat support teams are world-class. We scaled from 5 to 30 dedicated agents in just four months with zero downtime.', 3, 1)
ON DUPLICATE KEY UPDATE `client_name`=VALUES(`client_name`);

-- 10. Inquiries Table
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Newsletter Subscribers Table
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL UNIQUE,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
