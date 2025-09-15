<?php
include("config.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <?php if($post): ?>
        <title><?= htmlspecialchars($post['title']) ?> | अखिल भारतीय शासक संघ</title>
        <meta name="description" content="<?= mb_substr(strip_tags($post['content']),0,160) ?>">
        <!-- Open Graph for Social Media -->
        <meta property="og:title" content="<?= htmlspecialchars($post['title']) ?>">
        <meta property="og:description" content="<?= mb_substr(strip_tags($post['content']),0,160) ?>">
        <?php if($post['image']): ?>
            <meta property="og:image" content="uploads/<?= $post['image'] ?>">
        <?php endif; ?>
        <meta property="og:type" content="article">
    <?php else: ?>
        <title>पोस्ट नहीं मिला | अखिल भारतीय शासक संघ</title>
    <?php endif; ?>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <?php if($post): ?>
            <h2><?= $post['title'] ?></h2>
            <hr class="border-info">
            
            <?php if($post['image']): ?>
                <img src="uploads/<?= $post['image'] ?>" class="img-fluid rounded mb-3">
            <?php endif; ?>
            
            <p><?= nl2br($post['content']) ?></p>
            
            <p class="text-muted small">
                <i class="fa fa-calendar"></i> <?= $post['created_at'] ?>
            </p>

            <!-- 🔗 Social Share Buttons -->
            <div class="mt-3">
                <h5><i class="fa fa-share-alt"></i> साझा करें:</h5>
                <?php 
                $url = urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                $title = urlencode($post['title']);
                ?>
                <a class="btn btn-success btn-sm me-2" href="https://wa.me/?text=<?= $title ?>%20<?= $url ?>" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a class="btn btn-primary btn-sm me-2" href="https://www.facebook.com/sharer/sharer.php?u=<?= $url ?>" target="_blank">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
                <a class="btn btn-info btn-sm me-2" href="https://twitter.com/intent/tweet?url=<?= $url ?>&text=<?= $title ?>" target="_blank">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
            </div>

            <a href="javascript:history.back()" class="btn btn-light mt-3">
                <i class="fa fa-arrow-left"></i> वापस जाएँ
            </a>
        <?php else: ?>
            <p>❌ यह पोस्ट उपलब्ध नहीं है।</p>
        <?php endif; ?>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
