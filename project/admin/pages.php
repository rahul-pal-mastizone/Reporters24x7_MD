<?php
require_once __DIR__ . '/includes/auth.php';

// --- uploads helper (not used here, but harmless if kept consistent) ---
$UPLOAD_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
if (!is_dir($UPLOAD_DIR)) { @mkdir($UPLOAD_DIR, 0777, true); }

$notice = '';

// CREATE / UPDATE page
if (isset($_POST['add'])) {
    $title   = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $slug === '') {
        $notice = "Title and slug are required.";
    } else {
        // normalize slug => lower-case, dash separated
        $slug = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', $slug));

        // Insert or Update by unique slug (prevents fatal duplicate error)
        $stmt = $conn->prepare("
            INSERT INTO pages (title, slug, content)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                title   = VALUES(title),
                content = VALUES(content)
        ");
        if ($stmt) {
            $stmt->bind_param("sss", $title, $slug, $content);
            $stmt->execute();
            $stmt->close();
            header("Location: pages.php?ok=1"); exit;
        } else {
            $notice = "DB error: " . $conn->error;
        }
    }
}

// Fetch existing pages
$pages = $conn->query("SELECT id, title, slug FROM pages ORDER BY id DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/topbar.php';
?>
<div class="container-fluid p-3">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>
    <?php if (!empty($_GET['ok'])): ?>
        <div class="alert alert-success">Saved successfully.</div>
    <?php elseif (!empty($notice)): ?>
        <div class="alert alert-warning mb-2"><?= e($notice) ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="m-0">Manage Pages</h4>
        <a href="#add" class="btn btn-success btn-sm">+ Add New</a>
    </div>

    <form id="add" method="POST" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3"><input name="title" class="form-control" placeholder="Page Title" required></div>
            <div class="col-md-3"><input name="slug"  class="form-control" placeholder="Slug (about, mission)" required></div>
            <div class="col-md-4"><textarea name="content" class="form-control" placeholder="Page Content"></textarea></div>
            <div class="col-md-2"><button class="btn btn-primary w-100" name="add">Add Page</button></div>
        </div>
    </form>

    <h6>Existing Pages</h6>
    <ul class="mb-0">
        <?php while($p = $pages->fetch_assoc()): ?>
            <li><?= e($p['title']) ?> <span class="text-muted">(<?= e($p['slug']) ?>)</span></li>
        <?php endwhile; ?>
    </ul>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
