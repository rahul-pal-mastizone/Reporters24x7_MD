<?php
require_once("includes/auth.php");   // FIRST! No output before this.
require_once("../config.php");
include("includes/header.php");
include("includes/topbar.php");      // ok to output now
?>
<?php
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

$contributions = $conn->query("SELECT * FROM contributions ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Contributions</title></head>
<body>
<h2>Contributions (Sahyog Kare)</h2>
<table border="1" cellpadding="5">
<tr><th>Name</th><th>Email</th><th>Phone</th><th>Amount</th><th>Status</th><th>Date</th></tr>
<?php while($row=$contributions->fetch_assoc()){ ?>
<tr>
    <td><?=$row['name']?></td>
    <td><?=$row['email']?></td>
    <td><?=$row['phone']?></td>
    <td><?=$row['amount']?></td>
    <td><?=$row['payment_status']?></td>
    <td><?=$row['created_at']?></td>
</tr>
<?php } ?>
</table>
</body>
</html>
<?php include("includes/footer.php"); ?>
