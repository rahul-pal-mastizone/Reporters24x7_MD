<?php
// Start session early, no output before this
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Load app config (db + helpers)
require_once __DIR__ . '/../../config.php';

// Simple auth check
if (empty($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit;
}
