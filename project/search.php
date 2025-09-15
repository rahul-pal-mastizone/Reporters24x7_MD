<?php
include("config.php");

$q = isset($_GET['q']) ? trim($_GET['q']) : "";
$results = [];
if($q){
    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
    $like = "%$q%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>खोज परिणाम</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-search text-info"></i> खोज परिणाम</h2>
        <hr class="border-info">

        <form class="mb-3" method="GET">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="समाचार खोजें..." class="form-control">
        </form>

        <?php if($q && $results->num_rows > 0): ?>
            <div class="row">
                <?php while($row = $results->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 bg-dark text-light">
                            <?= $row['image'] ? "<img src='uploads/{$row['image']}' class='card-img-top'>" : "" ?>
                            <div class="card-body">
                                <h5>
                                    <a href="post.php?id=<?= $row['id'] ?>" class="text-light text-decoration-none">
                                        <?= $row['title'] ?>
                                    </a>
                                </h5>
                                <p><?= mb_substr($row['content'],0,120) ?>...</p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php elseif($q): ?>
            <p>❌ कोई परिणाम नहीं मिला।</p>
        <?php else: ?>
            <p>🔎 कृपया खोज शब्द दर्ज करें।</p>
        <?php endif; ?>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
