<?php
// admin/includes/init.php
// This file guarantees correct include order on every admin page.
// 1) auth (starts session & protects)
// 2) config (DB)
// 3) header (prints layout shell)

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/header.php';
