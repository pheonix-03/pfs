<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name    = sanitize($_POST['name'] ?? '');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone   = sanitize($_POST['phone'] ?? '');
$subject = sanitize($_POST['subject'] ?? 'General Inquiry');
$message = sanitize($_POST['message'] ?? '');

if (empty($name) || !$email || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields with a valid email address.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $subject, $message]);

    echo json_encode(['success' => true, 'message' => 'Thank you! Your inquiry has been sent successfully. Our team will get back to you shortly.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: Unable to submit inquiry at this moment.']);
}
