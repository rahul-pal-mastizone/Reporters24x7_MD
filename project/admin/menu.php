<?php include("includes/header.php"); ?>
<?php
session_start();
include("../config.php");
if(!isset($_SESSION['admin_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $parent_id = $_POST['parent_id'] ?? NULL;
    $sort_order = $_POST['sort_order'];

    $stmt = $conn->prepare("INSERT INTO menu_items (title, slug, parent_id, sort_order) VALUES (?,?,?,?)");
    $stmt->bind_param("ssii", $title, $slug, $parent_id, $sort_order);
    $stmt->execute();
}

$menus = $conn->query("SELECT * FROM menu_items ORDER BY sort_order");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Menu</title></head>
<body>
<h2>Manage Navigation Menu</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Menu Title" required>
    <input type="text" name="slug" placeholder="Slug (about, mission)" required>
    <input type="number" name="sort_order" placeholder="Order" value="0">
    <select name="parent_id">
        <option value="">No Parent</option>
        <?php while($row=$menus->fetch_assoc()){ ?>
            <option value="<?=$row['id']?>"><?=$row['title']?></option>
        <?php } ?>
    </select>
    <button type="submit" name="add">Add Menu</button>
</form>

<h3>Existing Menus</h3>
<ul>
<?php
$result = $conn->query("SELECT * FROM menu_items ORDER BY sort_order");
while($row = $result->fetch_assoc()){
    echo "<li>{$row['title']} ({$row['slug']})</li>";
}
?>
</ul>
</body>
</html>
