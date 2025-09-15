<?php
include("config.php");

$limit = 4; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page-1) * $limit;

$totalRes = $conn->query("SELECT COUNT(*) as cnt FROM posts WHERE type='editorial'");
$total = $totalRes->fetch_assoc()['cnt'];
$totalPages = ceil($total/$limit);

$posts = $conn->query("SELECT * FROM posts WHERE type='editorial' ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>संपादकीय</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-pen-nib text-warning"></i> संपादकीय</h2>
        <hr class="border-info">

        <div class="row">
            <?php while($row = $posts->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 bg-dark text-light">
                        <div class="card-body">
                            <h5>
                                <a href="post.php?id=<?= $row['id'] ?>" class="text-light text-decoration-none">
                                    <?= $row['title'] ?>
                                </a>
                            </h5>
                            <p><?= mb_substr($row['content'],0,200) ?>...</p>
                        </div>
                        <div class="card-footer text-muted">
                            <small><i class="fa fa-calendar"></i> <?= $row['created_at'] ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php for($i=1; $i<=$totalPages; $i++): ?>
                    <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
