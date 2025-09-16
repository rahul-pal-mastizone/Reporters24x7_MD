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
    $position = $_POST['position'] ?? 'top';
    $image    = save_upload('image', $UPLOAD_DIR);

    $stmt = $conn->prepare("INSERT INTO banners (title, image, position, status) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sss", $title, $image, $position);
    $stmt->execute();
    $stmt->close();

    header("Location: banners.php?ok=1"); exit;
}

$banners = $conn->query("SELECT id, title, image, position FROM banners ORDER BY id DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/topbar.php';
?>
<div class="container-fluid p-3">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>
    <?php if (!empty($_GET['ok'])): ?>
        <div class="alert alert-success">Saved successfully.</div>
    <?php endif; ?>

    <h4 class="mb-3">Manage Banners</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3"><input name="title" class="form-control" placeholder="Banner Title"></div>
            <div class="col-md-3"><input type="file" name="image" class="form-control"></div>
            <div class="col-md-2">
                <select name="position" class="form-select">
                    <option value="top">Top</option>
                    <option value="middle">Middle</option>
                    <option value="bottom">Bottom</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100" name="add">Add Banner</button></div>
        </div>
    </form>

    <h6>Existing Banners</h6>
    <ul class="mb-0">
        <?php while($b = $banners->fetch_assoc()): ?>
            <li>
                <?= e($b['title']) ?> <span class="text-muted">(<?= e($b['position']) ?>)</span>
                <?php if (!empty($b['image'])): ?>
                    — <img src="<?= e($UPLOADS_URL . $b['image']) ?>" alt="" style="height:28px; vertical-align:middle;">
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
