<?php
/**
 * Prime Fleet Services - Automated Unit Test Suite
 * Tests functions, database integrity, CRUD operations, security checks.
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;

function assert_test($description, $condition) {
    global $totalTests, $passedTests, $failedTests;
    $totalTests++;
    if ($condition) {
        $passedTests++;
        echo "[PASS] " . $description . "\n";
    } else {
        $failedTests++;
        echo "[FAIL] " . $description . "\n";
    }
}

echo "=======================================================\n";
echo "    PRIME FLEET SERVICES - COMPREHENSIVE UNIT TESTS    \n";
echo "=======================================================\n\n";

// --- SUITE 1: Helper Functions ---
echo "--- Suite 1: Helper Functions ---\n";

// 1. HTML Escaping e()
assert_test("e() escapes special HTML characters", e('<script>alert("xss")</script>') === '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;');
assert_test("e() handles null gracefully without warning", e(null) === '');
assert_test("e() handles single and double quotes", e('John\'s "Car"') === 'John&#039;s &quot;Car&quot;');

// 2. Sanitize
assert_test("sanitize() strips HTML tags", sanitize('<b>Hello</b> <script>bad()</script>') === 'Hello bad()');
assert_test("sanitize() trims whitespace", sanitize("   test string   ") === 'test string');
assert_test("sanitize() handles array of strings recursively", sanitize(['<p>one</p>', ' <div>two</div> ']) === ['one', 'two']);

// 3. Slugify
assert_test("slugify() converts strings to URL slugs", slugify('24/7 Dispatch Support!') === '24-7-dispatch-support');
assert_test("slugify() handles multiple spaces and hyphens", slugify('Special   --  Offer') === 'special-offer');
assert_test("slugify() returns fallback for empty input", slugify('???') === 'n-a');

// 4. CSRF Generation & Verification
$token = csrf_token();
assert_test("csrf_token() generates 64-char hex string", is_string($token) && strlen($token) === 64);
assert_test("verify_csrf() returns true for matching token", verify_csrf($token));
assert_test("verify_csrf() returns false for invalid token", !verify_csrf('invalid_token_123'));
assert_test("csrf_field() outputs valid hidden input", strpos(csrf_field(), 'name="csrf_token"') !== false && strpos(csrf_field(), $token) !== false);

// --- SUITE 2: Database Schema & Integrity ---
echo "\n--- Suite 2: Database Schema & Integrity ---\n";

assert_test("PDO connection is active and valid", $pdo instanceof PDO);

// Check required tables
$tables = [
    'admins', 'site_settings', 'hero_slides', 'services', 'certificates',
    'statistics', 'challenges_solutions', 'pages_content', 'page_sections',
    'testimonials', 'inquiries', 'subscribers'
];

$existingTables = [];
if (DB_DRIVER === 'sqlite') {
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    while ($row = $stmt->fetch()) {
        $existingTables[] = $row['name'];
    }
} else {
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $existingTables[] = $row[0];
    }
}

foreach ($tables as $t) {
    assert_test("Table '{$t}' exists in database", in_array($t, $existingTables));
}

// Check hero_slides has new columns: alignment, overlay_opacity, badge_icon
$slideCols = [];
if (DB_DRIVER === 'sqlite') {
    foreach ($pdo->query("PRAGMA table_info(hero_slides)") as $col) {
        $slideCols[] = $col['name'];
    }
}
assert_test("hero_slides table has 'alignment' column", in_array('alignment', $slideCols));
assert_test("hero_slides table has 'overlay_opacity' column", in_array('overlay_opacity', $slideCols));
assert_test("hero_slides table has 'badge_icon' column", in_array('badge_icon', $slideCols));

// Check admin credentials exist
$stmtAdmin = $pdo->query("SELECT * FROM admins WHERE username = 'admin' LIMIT 1");
$adminUser = $stmtAdmin->fetch();
assert_test("Default admin account 'admin' exists", !empty($adminUser));
assert_test("Default admin password verifies with 'admin123'", !empty($adminUser) && password_verify('admin123', $adminUser['password']));

// --- SUITE 3: Site Settings System ---
echo "\n--- Suite 3: Site Settings System ---\n";

$siteName = get_setting('site_name');
assert_test("get_setting('site_name') returns non-empty value", !empty($siteName));
assert_test("get_setting('non_existent_key', 'default_val') returns default value", get_setting('non_existent_test_key', 'default_val') === 'default_val');

// Test updating a setting
$testKey = 'unit_test_metric_' . time();
$testVal = 'test_value_12345';
update_setting($testKey, $testVal);

// Query DB directly to verify persistence
$stmtCheck = $pdo->prepare("SELECT value FROM site_settings WHERE key = ?");
$stmtCheck->execute([$testKey]);
$savedVal = $stmtCheck->fetchColumn();
assert_test("update_setting() successfully writes to database", $savedVal === $testVal);

// Clean up
$pdo->prepare("DELETE FROM site_settings WHERE key = ?")->execute([$testKey]);

// --- SUITE 4: Page Sections CMS & Visibility ---
echo "\n--- Suite 4: Page Sections CMS & Visibility ---\n";

$homeHero = get_page_section('home', 'hero_slider');
assert_test("get_page_section('home', 'hero_slider') returns section data array", is_array($homeHero) && !empty($homeHero['section_name']));
assert_test("is_section_visible('home', 'hero_slider') returns true by default", is_section_visible('home', 'hero_slider') === true);

$aboutHero = get_page_section('about', 'hero');
assert_test("get_page_section('about', 'hero') returns valid about banner data", is_array($aboutHero) && !empty($aboutHero['title']));

// Nonexistent section fallback structure
$dummySection = get_page_section('nonexistent_page', 'dummy_key');
assert_test("get_page_section() on missing key returns default array with all required keys", 
    isset($dummySection['badge'], $dummySection['title'], $dummySection['subtitle'], $dummySection['content'], $dummySection['btn_text'], $dummySection['btn_url'], $dummySection['image_url'], $dummySection['is_visible'])
);

// --- SUITE 5: Database CRUD Operations ---
echo "\n--- Suite 5: Database CRUD Operations ---\n";

// 1. Inquiries CRUD
$testEmail = 'unittest_' . time() . '@primefleetservices.test';
$stmtInq = $pdo->prepare("INSERT INTO inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
$stmtInq->execute(['Unit Tester', $testEmail, '+1-555-0100', 'Automated Test', 'This is a test message.']);
$inquiryId = $pdo->lastInsertId();
assert_test("Inquiry successfully inserted into database (ID: {$inquiryId})", $inquiryId > 0);

// Mark read
$pdo->prepare("UPDATE inquiries SET is_read = 1 WHERE id = ?")->execute([$inquiryId]);
$checkRead = $pdo->query("SELECT is_read FROM inquiries WHERE id = {$inquiryId}")->fetchColumn();
assert_test("Inquiry is_read flag updated successfully", (int)$checkRead === 1);

// Delete inquiry
$pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([$inquiryId]);
$deletedInq = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE id = {$inquiryId}")->fetchColumn();
assert_test("Inquiry record cleaned up successfully", (int)$deletedInq === 0);

// 2. Newsletter Subscribers CRUD
$stmtSub = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
$stmtSub->execute([$testEmail]);
$subId = $pdo->lastInsertId();
assert_test("Subscriber successfully inserted into database", $subId > 0);

// Duplicate subscription test (INSERT OR IGNORE)
$stmtSubDup = $pdo->prepare("INSERT OR IGNORE INTO subscribers (email) VALUES (?)");
$stmtSubDup->execute([$testEmail]);
$subCount = $pdo->prepare("SELECT COUNT(*) FROM subscribers WHERE email = ?");
$subCount->execute([$testEmail]);
assert_test("Subscribers table enforces email uniqueness without crashing", (int)$subCount->fetchColumn() === 1);

// Clean up subscriber
$pdo->prepare("DELETE FROM subscribers WHERE email = ?")->execute([$testEmail]);

// 3. Testimonials CRUD
$stmtTest = $pdo->prepare("INSERT INTO testimonials (client_name, company, role, rating, review_text) VALUES (?, ?, ?, ?, ?)");
$stmtTest->execute(['Test Client', 'Test Logistics Inc.', 'Director', 5, 'Outstanding service and 24/7 reliability.']);
$testId = $pdo->lastInsertId();
assert_test("Testimonial successfully created (ID: {$testId})", $testId > 0);

// Update testimonial rating
$pdo->prepare("UPDATE testimonials SET rating = 4 WHERE id = ?")->execute([$testId]);
$updatedRating = $pdo->query("SELECT rating FROM testimonials WHERE id = {$testId}")->fetchColumn();
assert_test("Testimonial rating updated to 4", (int)$updatedRating === 4);

// Clean up testimonial
$pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$testId]);

// 4. Services CRUD
$stmtSrv = $pdo->prepare("INSERT INTO services (category, title, slug, icon_class, short_desc, full_desc, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmtSrv->execute(['bpo', 'Automated QA Testing Service', 'qa-testing', 'bi bi-cpu', 'Short description', 'Full description', 1]);
$srvId = $pdo->lastInsertId();
assert_test("Service item successfully created (ID: {$srvId})", $srvId > 0);

// Toggle service status
$pdo->prepare("UPDATE services SET status = 0 WHERE id = ?")->execute([$srvId]);
$srvStatus = $pdo->query("SELECT status FROM services WHERE id = {$srvId}")->fetchColumn();
assert_test("Service status toggled to 0 (inactive)", (int)$srvStatus === 0);

// Clean up service
$pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$srvId]);

// 5. Hero Slides CRUD with Alignment and Opacity
$stmtSlide = $pdo->prepare("INSERT INTO hero_slides (title, subtitle, badge, alignment, overlay_opacity, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmtSlide->execute(['Custom Test Slide', 'Test Subtitle', 'TEST BADGE', 'center', 80, 1]);
$slideId = $pdo->lastInsertId();
assert_test("Hero slide created with alignment 'center' and opacity 80 (ID: {$slideId})", $slideId > 0);

$checkSlide = $pdo->query("SELECT alignment, overlay_opacity FROM hero_slides WHERE id = {$slideId}")->fetch();
assert_test("Hero slide alignment persists correctly as 'center'", $checkSlide['alignment'] === 'center');
assert_test("Hero slide overlay_opacity persists correctly as 80", (int)$checkSlide['overlay_opacity'] === 80);

// Clean up slide
$pdo->prepare("DELETE FROM hero_slides WHERE id = ?")->execute([$slideId]);

echo "\n=======================================================\n";
echo "SUMMARY: Total: {$totalTests} | Passed: {$passedTests} | Failed: {$failedTests}\n";
echo "=======================================================\n";

if ($failedTests > 0) {
    exit(1);
}
exit(0);
