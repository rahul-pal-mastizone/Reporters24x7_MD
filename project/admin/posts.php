<?php
require_once __DIR__ . '/includes/auth.php';

// --- uploads helper ---
$UPLOAD_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
if (!is_dir($UPLOAD_DIR)) { @mkdir($UPLOAD_DIR, 0777, true); }
function save_upload($field, $dir) {
    if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }
    $ext  = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $base = pathinfo($_FILES[$field]['name'], PATHINFO_FILENAME);
    $safe = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $base);
    $name = time() . '_' . $safe . ($ext ? '.' . $ext : '');
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $name)) { return null; }
    return $name;
}

if (isset($_POST['add'])) {
    $type    = $_POST['type'] ?? 'news';
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image   = save_upload('image', $UPLOAD_DIR); // filename or null

    $stmt = $conn->prepare("INSERT INTO posts (type, title, content, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $type, $title, $content, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: posts.php?ok=1"); exit;
}

$posts = $conn->query("SELECT id, type, title, created_at FROM posts ORDER BY created_at DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/topbar.php';
?>
<div class="container-fluid p-3">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>
    <?php if (!empty($_GET['ok'])): ?>
        <div class="alert alert-success">Saved successfully.</div>
    <?php endif; ?>

    <h4 class="mb-3">Add Post</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row g-2">
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="news">News</option>
                    <option value="editorial">Editorial</option>
                    <option value="press">Press Release</option>
                </select>
            </div>
            <div class="col-md-3"><input name="title" class="form-control" placeholder="Title" required></div>
            <div class="col-md-3"><textarea name="content" class="form-control" placeholder="Content"></textarea></div>
            <div class="col-md-2"><input type="file" name="image" class="form-control"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100" name="add">Add Post</button></div>
        </div>
    </form>

    <h6>Existing Posts</h6>
    <ul class="mb-0">
        <?php while($r = $posts->fetch_assoc()): ?>
            <li>
                [<?= e($r['type']) ?>] <?= e($r['title']) ?>
                <small class="text-muted"> (<?= e($r['created_at']) ?>)</small>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
