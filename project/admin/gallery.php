<?php include("includes/header.php"); ?>
<?php require_once("includes/auth.php"); ?>
<?php $showAdd = true; include("includes/topbar.php"); ?>
<?php
include("../config.php");
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $category = $_POST['category'];
    
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);

    $stmt = $conn->prepare("INSERT INTO gallery (title, image, category) VALUES (?,?,?)");
    $stmt->bind_param("sss", $title, $image, $category);
    $stmt->execute();
}

$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Gallery</title></head>
<body>
<a id="add"></a>
<h2>Gallery</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="category" placeholder="Category (e.g. Movements)" required>
    <input type="file" name="image" required>
    <button type="submit" name="add">Add Image</button>
</form>

<h3>Existing Gallery Images</h3>
<ul>
<?php while($row=$gallery->fetch_assoc()){ ?>
    <li><?=$row['title']?> <img src="../uploads/<?=$row['image']?>" width="100"></li>
<?php } ?>
</ul>
</body>
</html>
<?php include("includes/footer.php"); ?>
