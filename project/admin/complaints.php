<?php
require_once("includes/auth.php");   // FIRST! No output before this.
require_once("../config.php");
include("includes/header.php");
include("includes/topbar.php");      // ok to output now
?>
<?php
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_GET['resolve'])){
    $id = intval($_GET['resolve']);
    $conn->query("UPDATE complaints SET status='resolved' WHERE id=$id");
}

$complaints = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Complaints</title></head>
<body>
<h2>Complaints</h2>
<table border="1" cellpadding="5">
<tr><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Action</th></tr>
<?php while($row=$complaints->fetch_assoc()){ ?>
<tr>
    <td><?=$row['name']?></td>
    <td><?=$row['email']?></td>
    <td><?=$row['message']?></td>
    <td><?=$row['status']?></td>
    <td>
        <?php if($row['status']=='pending'){ ?>
            <a href="?resolve=<?=$row['id']?>">Mark Resolved</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>
<?php include("includes/footer.php"); ?>
