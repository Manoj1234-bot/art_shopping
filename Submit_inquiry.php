<?php

// ======================================================
// BHEEMA ART GALLERY
// INQUIRY FORM HANDLER
// Saves customer name / email / phone / message into the
// "inquiries" table so it can be viewed on admin.php.
// ======================================================

session_start();

require_once __DIR__ . "/includes/db.php";

function back_to_form($status) {
    header("Location: index.php?status=" . urlencode($status) . "#contact");
    exit;
}

// Only accept real form posts.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back_to_form('error');
}

// Honeypot: a real visitor never fills this hidden field.
// Bots often do — quietly pretend success so they don't retry.
if (!empty($_POST['website'])) {
    back_to_form('success');
}

// CSRF check.
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    back_to_form('error');
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$message = trim($_POST['message'] ?? '');

// Basic required-field + length checks.
if ($name === '' || $email === '' || $phone === '') {
    back_to_form('error');
}

if (strlen($name) > 150 || strlen($email) > 190 || strlen($phone) > 30 || strlen($message) > 2000) {
    back_to_form('error');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_to_form('error');
}

// DB connection missing/failed — see includes/db.php.
if (!$conn) {
    back_to_form('error');
}

$stmt = $conn->prepare(
    "INSERT INTO inquiries (name, email, phone, message) VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    back_to_form('error');
}

$stmt->bind_param("ssss", $name, $email, $phone, $message);

$ok = $stmt->execute();
$stmt->close();

back_to_form($ok ? 'success' : 'error');