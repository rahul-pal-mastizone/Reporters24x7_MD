<?php require_once __DIR__ . '/includes/init.php'; ?>
<?php $showAdd = true; include("includes/topbar.php"); ?>
<?php
include("../config.php");
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO pages (title, slug, content) VALUES (?,?,?)");
    $stmt->bind_param("sss", $title, $slug, $content);
    $stmt->execute();
}

$pages = $conn->query("SELECT * FROM pages");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Pages</title></head>
<body>
<a id="add"></a>
<h2>Manage Pages</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Page Title" required>
    <input type="text" name="slug" placeholder="Slug (about, mission)" required>
    <textarea name="content" placeholder="Page Content"></textarea>
    <button type="submit" name="add">Add Page</button>
</form>

<h3>Existing Pages</h3>
<ul>
<?php while($row=$pages->fetch_assoc()){ ?>
    <li><?=$row['title']?> (<?=$row['slug']?>)</li>
<?php } ?>
</ul>
</body>
</html>
<?php include("includes/footer.php"); ?>
