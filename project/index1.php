<?php include("config.php"); ?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>अखिल भारतीय शासक संघ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="assets/logo.png" alt="Logo">
        </div>
        <div class="scrolling-text">
            <marquee>🌟 अखिल भारतीय शासक संघ में आपका स्वागत है 🌟</marquee>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <?php
            $menu = $conn->query("SELECT * FROM menu_items WHERE parent_id IS NULL AND status=1 ORDER BY sort_order");
            while($row = $menu->fetch_assoc()){
                echo "<li><a href='{$row['slug']}.php'>{$row['title']}</a></li>";
            }
            ?>
        </ul>
    </nav>

    <!-- Banner -->
    <section class="banner">
        <?php
        $banner = $conn->query("SELECT * FROM banners WHERE status=1 LIMIT 1");
        if($row = $banner->fetch_assoc()){
            echo "<img src='uploads/{$row['image']}' alt='{$row['title']}'>";
        } else {
            echo "<img src='assets/default-banner.jpg' alt='Banner'>";
        }
        ?>
    </section>

    <!-- Objectives -->
    <section>
        <h2>🎯 हमारा उद्देश्य</h2>
        <?php
        $page = $conn->query("SELECT * FROM pages WHERE slug='objective'");
        if($row = $page->fetch_assoc()){
            echo "<p>{$row['content']}</p>";
        } else {
            echo "<p>यहाँ उद्देश्य दिखेगा...</p>";
        }
        ?>
    </section>

    <!-- Mission -->
    <section>
        <h2>🚀 हमारा मिशन</h2>
        <?php
        $page = $conn->query("SELECT * FROM pages WHERE slug='mission'");
        if($row = $page->fetch_assoc()){
            echo "<p>{$row['content']}</p>";
        } else {
            echo "<p>यहाँ मिशन दिखेगा...</p>";
        }
        ?>
    </section>

    <!-- Latest News -->
    <section>
        <h2>📰 नवीनतम समाचार</h2>
        <ul>
        <?php
        $posts = $conn->query("SELECT * FROM posts WHERE type='news' ORDER BY created_at DESC LIMIT 5");
        while($row = $posts->fetch_assoc()){
            echo "<li><strong>{$row['title']}</strong> - ".mb_substr($row['content'],0,80)."...</li>";
        }
        ?>
        </ul>
    </section>

    <!-- Complaint & Support -->
    <section class="interactive text-center">
        <a href="complaint.php">📝 शिकायत करें</a>
        <a href="sahyog.php">💰 सहयोग करें</a>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2025 अखिल भारतीय शासक संघ | Designed with ❤️ in PHP + MySQL</p>
    </footer>

</body>
</html>
