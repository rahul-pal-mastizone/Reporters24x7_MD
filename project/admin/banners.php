<?php include("includes/header.php"); ?>
<?php require_once("includes/auth.php"); ?>
<?php $showAdd = true; include("includes/topbar.php"); ?>
<?php
include("../config.php");
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $position = $_POST['position'];
    
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);

    $stmt = $conn->prepare("INSERT INTO banners (title, image, position) VALUES (?,?,?)");
    $stmt->bind_param("sss", $title, $image, $position);
    $stmt->execute();
}

$banners = $conn->query("SELECT * FROM banners");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Banners</title></head>
<body>
<a id="add"></a>
<h2>Manage Banners</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Banner Title" required>
    <input type="file" name="image" required>
    <select name="position">
        <option value="top">Top</option>
        <option value="bottom">Bottom</option>
        <option value="sidebar">Sidebar</option>
    </select>
    <button type="submit" name="add">Add Banner</button>
</form>

<h3>Existing Banners</h3>
<ul>
<?php while($row=$banners->fetch_assoc()){ ?>
    <li><?=$row['title']?> <img src="../uploads/<?=$row['image']?>" width="100"></li>
<?php } ?>
</ul>
</body>
</html>
<?php include("includes/footer.php"); ?>
