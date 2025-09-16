<?php
// admin/menu.php — robust version that keeps your table UI and auto-detects sort column

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config.php';

// ---- table/columns (adjust only if your table name is different) ----
$TABLE = 'menu_items';
$PARENT_COL = 'parent_id';

// Detect a sort column if present
function detect_sort_col(mysqli $conn, string $table): ?string {
    $candidates = ['sort', 'sort_order', 'menu_order', 'order_no', 'order', 'position', 'rank'];
    foreach ($candidates as $c) {
        $q = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$c'");
        if ($q && $q->num_rows) return $c;
    }
    return null;
}
$SORT_COL = detect_sort_col($conn, $TABLE);
$HAS_SORT = $SORT_COL !== null;

$errors = [];
$ok = isset($_GET['ok']);

function int_or_null($v) {
    if ($v === '' || $v === null) return null;
    $i = (int)$v;
    return $i > 0 ? $i : null;
}

/* --------- DELETE --------- */
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $conn->prepare("DELETE FROM `$TABLE` WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: menu.php?ok=1"); exit;
}

/* --------- ADD --------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title  = trim($_POST['title'] ?? '');
    $slug   = trim($_POST['slug'] ?? '');
    $parent = int_or_null($_POST['parent'] ?? null);
    $sort   = (int)($_POST['sort'] ?? 0);

    if ($title === '') $errors[] = 'Title is required.';
    if ($slug  === '') $errors[] = 'Slug is required.';

    // validate parent exists
    if ($parent !== null) {
        $chk = $conn->prepare("SELECT id FROM `$TABLE` WHERE id=?");
        $chk->bind_param("i", $parent);
        $chk->execute(); $chk->store_result();
        if ($chk->num_rows === 0) $parent = null;
        $chk->close();
    }

    if (!$errors) {
        if ($HAS_SORT) {
            $sql = "INSERT INTO `$TABLE` (title, slug, `$PARENT_COL`, `$SORT_COL`) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $title, $slug, $parent, $sort);
        } else {
            $sql = "INSERT INTO `$TABLE` (title, slug, `$PARENT_COL`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $title, $slug, $parent);
        }
        if ($stmt->execute()) { header("Location: menu.php?ok=1"); exit; }
        if ($conn->errno == 1062) $errors[] = 'Slug already exists.'; else $errors[] = $conn->error;
    }
}

/* --------- UPDATE --------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id     = (int)$_POST['id'];
    $title  = trim($_POST['title'] ?? '');
    $slug   = trim($_POST['slug'] ?? '');
    $parent = int_or_null($_POST['parent'] ?? null);
    $sort   = (int)($_POST['sort'] ?? 0);

    if ($parent === $id) $parent = null;

    if ($parent !== null) {
        $chk = $conn->prepare("SELECT id FROM `$TABLE` WHERE id=? AND id<>?");
        $chk->bind_param("ii", $parent, $id);
        $chk->execute(); $chk->store_result();
        if ($chk->num_rows === 0) $parent = null;
        $chk->close();
    }

    if ($HAS_SORT) {
        $sql = "UPDATE `$TABLE` SET title=?, slug=?, `$PARENT_COL`=?, `$SORT_COL`=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $title, $slug, $parent, $sort, $id);
    } else {
        $sql = "UPDATE `$TABLE` SET title=?, slug=?, `$PARENT_COL`=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $title, $slug, $parent, $id);
    }
    if ($stmt->execute()) { header("Location: menu.php?ok=1"); exit; }
    if ($conn->errno == 1062) $errors[] = 'Slug already exists.'; else $errors[] = $conn->error;
}

/* --------- DATA --------- */
$selectCols = "id, title, slug, `$PARENT_COL` AS parent_id";
$selectCols .= $HAS_SORT ? ", `$SORT_COL` AS sort" : ", 0 AS sort";
$orderBy = $HAS_SORT ? "ORDER BY `$SORT_COL`, id" : "ORDER BY id";

$items = [];
$q = $conn->query("SELECT $selectCols FROM `$TABLE` $orderBy");
while ($row = $q->fetch_assoc()) $items[] = $row;

$edit = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT $selectCols FROM `$TABLE` WHERE id=?");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/topbar.php';
?>

<div class="container mt-3">
  <a href="dashboard.php" class="btn btn-sm btn-secondary mb-3">&larr; Back to Dashboard</a>

  <?php if ($errors): ?>
    <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
  <?php elseif (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Saved successfully.</div>
  <?php endif; ?>

  <h3 class="mb-3">Manage Navigation Menu</h3>

  <!-- Add / Edit (single-row form) -->
  <form method="post" class="row g-2 align-items-end mb-3">
    <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>"><?php endif; ?>

    <div class="col-md-4">
      <label class="form-label">Menu Title</label>
      <input type="text" class="form-control" name="title" required value="<?php echo htmlspecialchars($edit['title'] ?? ''); ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Slug</label>
      <input type="text" class="form-control" name="slug" placeholder="about, mission" required value="<?php echo htmlspecialchars($edit['slug'] ?? ''); ?>">
    </div>

    <div class="col-md-2">
      <label class="form-label">Parent</label>
      <select class="form-select" name="parent">
        <option value="0">No Parent</option>
        <?php foreach ($items as $it): if ($edit && $it['id'] == $edit['id']) continue; ?>
          <option value="<?php echo $it['id']; ?>" <?php echo ($edit && (int)$edit['parent_id'] === (int)$it['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($it['title']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <?php
      $sortValue = $edit ? (int)$edit['sort'] : (count($items) + 1);
      if (!$HAS_SORT) echo '<input type="hidden" name="sort" value="'. $sortValue .'">';
    ?>
    <div class="col-md-1" <?php if(!$HAS_SORT) echo 'style="display:none"'; ?>>
      <label class="form-label">Sort</label>
      <input type="number" class="form-control" name="sort" value="<?php echo $sortValue; ?>">
    </div>

    <div class="col-md-1">
      <?php if ($edit): ?>
        <button class="btn btn-primary" name="update">Update</button>
        <a class="btn btn-outline-secondary" href="menu.php">Cancel</a>
      <?php else: ?>
        <button class="btn btn-primary" name="add">Add Menu</button>
      <?php endif; ?>
    </div>
  </form>

  <table class="table table-dark table-hover align-middle">
    <thead>
      <tr>
        <th style="width:60px">#</th>
        <th>Title</th>
        <th>Slug</th>
        <th>Parent</th>
        <th><?php echo $HAS_SORT ? 'Sort' : 'Sort'; ?></th>
        <th style="width:140px">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; foreach ($items as $row): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><span class="text-info"><?php echo htmlspecialchars($row['slug']); ?></span></td>
          <td><?php echo $row['parent_id'] ? (int)$row['parent_id'] : 0; ?></td>
          <td><?php echo (int)$row['sort']; ?></td>
          <td>
            <a class="btn btn-sm btn-info" href="menu.php?edit=<?php echo $row['id']; ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="menu.php?del=<?php echo $row['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
