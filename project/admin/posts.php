<?php include("includes/header.php"); ?>
<?php require_once("includes/auth.php"); ?>
<?php $showAdd = true; include("includes/topbar.php"); ?>
<?php
include("../config.php");
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add'])){
    $type = $_POST['type'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = $_FILES['image']['name'];
    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
    }

    $stmt = $conn->prepare("INSERT INTO posts (type, title, content, image) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $type, $title, $content, $image);
    $stmt->execute();
}

$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Posts</title></head>
<body>
<a id="add"></a>
<h2 class="mb-3">Add Post</h2>
<h2>Manage News / Editorial / Press</h2>

<form method="POST" enctype="multipart/form-data">
    <select name="type">
        <option value="news">News</option>
        <option value="editorial">Editorial</option>
        <option value="press">Press Release</option>
    </select>
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="content" placeholder="Content"></textarea>
    <input type="file" name="image">
    <button type="submit" name="add">Add Post</button>
</form>

<h3>Existing Posts</h3>
<ul>
<?php while($row=$posts->fetch_assoc()){ ?>
    <li>[<?=$row['type']?>] <?=$row['title']?> (<?=$row['created_at']?>)</li>
<?php } ?>
</ul>
</body>
</html>
<?php include("includes/footer.php"); ?>
