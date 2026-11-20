<?php
/**
 * Prime Fleet Services - Helper Functions
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Escape string for HTML output
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize string input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim(strip_tags($data ?? ''));
}

/**
 * Get setting value from database
 */
function get_setting($key, $default = '') {
    global $pdo;
    static $settings = null;

    if ($settings === null) {
        $settings = [];
        try {
            $stmt = $pdo->query("SELECT key, value FROM site_settings");
            while ($row = $stmt->fetch()) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (Exception $e) {
            // Table might not exist yet
        }
    }

    return $settings[$key] ?? $default;
}

/**
 * Update or insert setting
 */
function update_setting($key, $value) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO site_settings (key, value, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)");
    return $stmt->execute([$key, $value]);
}

/**
 * CSRF Protection
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf($token = null) {
    $token = $token ?? ($_POST['csrf_token'] ?? '');
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Auth checks
 */
function is_logged_in() {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function current_user() {
    return $_SESSION['admin_user'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Upload helper
 */
function upload_file($file, $subfolder = '') {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed with error code ' . ($file['error'] ?? 'missing')];
    }

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
        'image/gif'  => 'gif',
        'application/pdf' => 'pdf'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!array_key_exists($mime, $allowedMimes)) {
        return ['success' => false, 'error' => 'Invalid file format. Allowed: JPG, PNG, WEBP, SVG, GIF, PDF.'];
    }

    // 10MB limit
    if ($file['size'] > 10 * 1024 * 1024) {
        return ['success' => false, 'error' => 'File size exceeds maximum allowed 10MB.'];
    }

    $targetDir = UPLOADS_PATH . ($subfolder ? trim($subfolder, '/\\') . DIRECTORY_SEPARATOR : '');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $extension = $allowedMimes[$mime];
    $filename = uniqid('img_', true) . '.' . $extension;
    $destination = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $publicUrl = UPLOADS_URL . ($subfolder ? trim($subfolder, '/') . '/' : '') . $filename;
        return ['success' => true, 'url' => $publicUrl, 'filename' => $filename];
    }

    return ['success' => false, 'error' => 'Failed to save uploaded file.'];
}

/**
 * Flash messages
 */
function flash_message($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flash_messages() {
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/**
 * Slugify
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Universal Page Section Helper
 */
function get_page_section($page, $sectionKey) {
    global $pdo;
    static $pageSectionsCache = [];

    if (!isset($pageSectionsCache[$page])) {
        $pageSectionsCache[$page] = [];
        try {
            $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page = ?");
            $stmt->execute([$page]);
            while ($row = $stmt->fetch()) {
                $pageSectionsCache[$page][$row['section_key']] = $row;
            }
        } catch (Exception $e) {}
    }

    return $pageSectionsCache[$page][$sectionKey] ?? [
        'badge'      => '',
        'title'      => '',
        'subtitle'   => '',
        'content'    => '',
        'btn_text'   => '',
        'btn_url'    => '',
        'image_url'  => '',
        'is_visible' => 1
    ];
}

/**
 * Check if a section is visible
 */
function is_section_visible($page, $sectionKey) {
    $sec = get_page_section($page, $sectionKey);
    return !empty($sec['is_visible']) && (int)$sec['is_visible'] >= 1;
}

