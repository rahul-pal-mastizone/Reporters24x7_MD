<?php include("config.php"); ?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>अखिल भारतीय शासक संघ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">

            <!-- उद्देश्य -->
            <div class="card bg-secondary mb-3 p-3">
                <h4><i class="fa fa-bullseye text-warning"></i> हमारा उद्देश्य</h4>
                <hr class="border-info">
                <?php
                $page = $conn->query("SELECT * FROM pages WHERE slug='objective'");
                if($row = $page->fetch_assoc()){
                    echo "<p>{$row['content']}</p>";
                } else {
                    echo "<p>यहाँ उद्देश्य की सामग्री दिखाई जाएगी...</p>";
                }
                ?>
            </div>

            <!-- मिशन -->
            <div class="card bg-secondary mb-3 p-3">
                <h4><i class="fa fa-rocket text-danger"></i> हमारा मिशन</h4>
                <hr class="border-info">
                <?php
                $page = $conn->query("SELECT * FROM pages WHERE slug='mission'");
                if($row = $page->fetch_assoc()){
                    echo "<p>{$row['content']}</p>";
                } else {
                    echo "<p>यहाँ मिशन की सामग्री दिखाई जाएगी...</p>";
                }
                ?>
            </div>

            <!-- समाचार -->
            <div class="card bg-secondary mb-3 p-3">
                <h4><i class="fa fa-newspaper text-info"></i> नवीनतम समाचार</h4>
                <hr class="border-info">
                <ul>
                <?php
                $posts = $conn->query("SELECT * FROM posts WHERE type='news' ORDER BY created_at DESC LIMIT 5");
                while($row = $posts->fetch_assoc()){
                    echo "<li><strong>{$row['title']}</strong> - ".mb_substr($row['content'],0,80)."...</li>";
                }
                ?>
                </ul>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-md-4">

            <!-- Banner -->
            <div class="card bg-secondary mb-3 p-2 text-center">
                <h5 class="text-light">मुख्य बैनर</h5>
                <?php
                $banner = $conn->query("SELECT * FROM banners WHERE status=1 LIMIT 1");
                if($row = $banner->fetch_assoc()){
                    echo "<img src='uploads/{$row['image']}' class='img-fluid rounded'>";
                } else {
                    echo "<img src='assets/default-banner.jpg' class='img-fluid rounded'>";
                }
                ?>
            </div>

            <!-- Quick Links -->
            <div class="card bg-secondary p-3 text-center">
                <a href="complaint.php" class="btn btn-danger w-100 mb-2">
                    <i class="fa fa-pen-to-square"></i> शिकायत करें
                </a>
                <a href="sahyog.php" class="btn btn-success w-100">
                    <i class="fa fa-hand-holding-dollar"></i> सहयोग करें
                </a>
            </div>

        </div>
    </div>

</div>

<?php include("partials/footer.php"); ?>

</body>
</html>
