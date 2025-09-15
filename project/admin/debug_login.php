<?php
include("../config.php");
header('Content-Type: text/plain; charset=utf-8');

echo "DB: ";
$r = $conn->query("SELECT DATABASE() d"); echo ($r?$r->fetch_assoc()['d']:"(none)")."\n";

$r = $conn->query("SHOW TABLES LIKE 'admins'");
echo "admins table: ".($r && $r->num_rows ? "FOUND" : "NOT FOUND")."\n";

$r = $conn->query("SELECT id, username, LEFT(password, 7) AS pw_prefix, LENGTH(password) AS pw_len FROM admins");
if(!$r){ echo "SELECT error: ".$conn->error."\n"; exit; }
echo "admins rows:\n";
while($row = $r->fetch_assoc()){ print_r($row); }

