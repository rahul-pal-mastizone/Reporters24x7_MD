<?php
// Safe session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- DB connection (idempotent) ---
if (!isset($conn) || !($conn instanceof mysqli)) {
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = '';
    $DB_NAME = 'client_website';

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if ($conn->connect_error) {
        die('DB connection failed: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
}

// --- Helpers (idempotent) ---
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('redirect')) {
    function redirect(string $url) { header("Location: {$url}"); exit; }
}

// Optional constants (used by uploads elsewhere)
if (!defined('BASE_PATH'))  define('BASE_PATH', __DIR__);
if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', BASE_PATH . '/uploads');
