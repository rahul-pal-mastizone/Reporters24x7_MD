<header>
    <div class="logo">
        <img src="assets/logo.png" alt="Logo">
    </div>
    <div class="scrolling-text">
        <marquee>🌟 अखिल भारतीय शासक संघ में आपका स्वागत है 🌟</marquee>
    </div>
</header>

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

<!-- 🔍 Search Bar -->
<div class="bg-dark p-2 text-center">
    <form action="search.php" method="GET" class="d-inline-flex" style="max-width:400px;">
        <input type="text" name="q" class="form-control me-2" placeholder="🔎 खोजें...">
        <button class="btn btn-info"><i class="fa fa-search"></i></button>
    </form>
</div>
