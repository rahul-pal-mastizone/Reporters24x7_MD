<?php
include("../config.php");
$r = $conn->query("SELECT id, username, password FROM admins WHERE username='admin' LIMIT 1");
$u = $r->fetch_assoc();
echo "Hash: ".$u['password']."\n";
echo "Verify(admin123): ".(password_verify('admin123', $u['password']) ? 'TRUE' : 'FALSE');
