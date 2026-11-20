<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

try {
    if (DB_DRIVER === 'sqlite') {
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO subscribers (email) VALUES (?)");
    } else {
        $stmt = $pdo->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
    }
    $stmt->execute([$email]);

    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing to our newsletter!']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Unable to process subscription right now.']);
}
