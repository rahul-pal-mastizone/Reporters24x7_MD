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
    $title    = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $image    = save_upload('image', $UPLOAD_DIR);

    $stmt = $conn->prepare("INSERT INTO gallery (title, image, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $image, $category);
    $stmt->execute();
    $stmt->close();

    header("Location: gallery.php?ok=1"); exit;
}

$rows = $conn->query("SELECT id, title, image, category FROM gallery ORDER BY id DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/topbar.php';
?>
<div class="container-fluid p-3">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>
    <?php if (!empty($_GET['ok'])): ?>
        <div class="alert alert-success">Saved successfully.</div>
    <?php endif; ?>

    <h4 class="mb-3">Gallery</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3"><input name="title" class="form-control" placeholder="Title"></div>
            <div class="col-md-3"><input name="category" class="form-control" placeholder="Category (e.g. Movements)"></div>
            <div class="col-md-3"><input type="file" name="image" class="form-control"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100" name="add">Add Image</button></div>
        </div>
    </form>

    <h6>Existing Gallery Images</h6>
    <div class="row g-3">
        <?php while($g = $rows->fetch_assoc()): ?>
            <div class="col-6 col-md-3">
                <div class="card bg-dark border-0">
                    <?php if (!empty($g['image'])): ?>
                        <img class="card-img-top" src="<?= e($UPLOADS_URL . $g['image']) ?>" alt="">
                    <?php endif; ?>
                    <div class="card-body py-2">
                        <div class="fw-semibold small"><?= e($g['title']) ?></div>
                        <div class="text-muted small"><?= e($g['category']) ?></div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
