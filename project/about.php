<?php include("config.php"); ?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>हमारे बारे में</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-circle-info text-warning"></i> हमारे बारे में</h2>
        <hr class="border-info">
        <?php
        $page = $conn->query("SELECT * FROM pages WHERE slug='about'");
        if($row = $page->fetch_assoc()){
            echo "<p>{$row['content']}</p>";
        } else {
            echo "<p>यहाँ 'हमारे बारे में' की सामग्री दिखाई जाएगी...</p>";
        }
        ?>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
