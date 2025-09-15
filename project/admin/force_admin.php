<?php
session_start();
$_SESSION['admin_id'] = 1;
header("Location: dashboard.php");
exit;
