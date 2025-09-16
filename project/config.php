<?php
/**
 * Global config – safe to include multiple times
 * Fixes: "Cannot redeclare e()" + session warnings + missing uploads dir
 */
if (!defined('APP_STARTED')) {
    define('APP_STARTED', true);

    // ---- DB ---------------------------------------------------------------
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli('localhost', 'root', '', 'client_website');
    $conn->set_charset('utf8mb4');

    // ---- Session ----------------------------------------------------------
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ---- Paths / URLs -----------------------------------------------------
    define('APP_ROOT', __DIR__);                       // filesystem path to /project
    define('UPLOAD_DIR', APP_ROOT . '/uploads/');      // /project/uploads/
    if (!is_dir(UPLOAD_DIR)) { @mkdir(UPLOAD_DIR, 0777, true); }

    // ---- Helpers ----------------------------------------------------------
    if (!function_exists('e')) {
        function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
    }
}
