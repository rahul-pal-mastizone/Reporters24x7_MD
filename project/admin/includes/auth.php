<?php
// admin/includes/auth.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    if (ob_get_level() === 0) { ob_start(); }  // guard in case of stray output
    session_start();
}
if (empty($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
