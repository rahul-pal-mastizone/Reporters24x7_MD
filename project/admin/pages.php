<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function ok($m){ echo '<div class="alert alert-success mb-3">'.e($m).'</div>'; }
function err($m){ echo '<div class="alert alert-danger mb-3">'.e($m).'</div>'; }

$edit=null; $msg=null;

// CREATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='create'){
  $title = trim($_POST['title'] ?? '');
  $slug  = trim($_POST['slug'] ?? '');
  $content = trim($_POST['content'] ?? '');
  if($title==='' || $slug===''){ $msg=['t'=>'e','m'=>'Title & slug required.']; }
  else{
    $st=$conn->prepare("INSERT INTO pages(title,slug,content) VALUES(?,?,?)");
    if($st && $st->bind_param('sss',$title,$slug,$content) && $st->execute()){
      $msg=['t'=>'o','m'=>'Page added.'];
    }else{ $msg=['t'=>'e','m'=>'Could not add (duplicate slug?).']; }
    if($st) $st->close();
  }
}

// LOAD EDIT
if(isset($_GET['edit'])){
  $id=(int)$_GET['edit'];
  $st=$conn->prepare("SELECT * FROM pages WHERE id=?");
  $st->bind_param('i',$id); $st->execute();
  $edit=$st->get_result()->fetch_assoc(); $st->close();
}

// UPDATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update'){
  $id=(int)($_POST['id']??0);
  $title=trim($_POST['title']??'');
  $slug=trim($_POST['slug']??'');
  $content=trim($_POST['content']??'');
  if($id<=0||$title===''||$slug===''){ $msg=['t'=>'e','m'=>'All fields required.']; }
  else{
    $st=$conn->prepare("UPDATE pages SET title=?,slug=?,content=? WHERE id=?");
    if($st && $st->bind_param('sssi',$title,$slug,$content,$id) && $st->execute()){
      $msg=['t'=>'o','m'=>'Page updated.']; $edit=null;
    }else{ $msg=['t'=>'e','m'=>'Could not update (duplicate slug?).']; }
    if($st) $st->close();
  }
}

// DELETE
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $st=$conn->prepare("DELETE FROM pages WHERE id=?");
  $st->bind_param('i',$id);
  if($st->execute()) $msg=['t'=>'o','m'=>'Page deleted.'];
  $st->close();
}

$rows=$conn->query("SELECT * FROM pages ORDER BY id DESC");
?>
<div class="container-fluid px-3 py-3">
  <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>

  <h3 class="mb-3">Manage Pages</h3>
  <?php if($msg){ $msg['t']==='o'?ok($msg['m']):err($msg['m']); } ?>

  <form method="post" class="row gy-2 gx-2 mb-4">
    <?php if($edit): ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= e($edit['id']) ?>">
      <div class="col-md-3"><input class="form-control" name="title" value="<?= e($edit['title']) ?>" required></div>
      <div class="col-md-3"><input class="form-control" name="slug" value="<?= e($edit['slug']) ?>" required></div>
      <div class="col-12"><textarea class="form-control" name="content" rows="6" required><?= e($edit['content']) ?></textarea></div>
      <div class="col-md-3 mt-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Save</button>
        <a href="pages.php" class="btn btn-outline-secondary w-100">Cancel</a>
      </div>
    <?php else: ?>
      <input type="hidden" name="action" value="create">
      <div class="col-md-3"><input class="form-control" name="title" placeholder="Page Title" required></div>
      <div class="col-md-3"><input class="form-control" name="slug" placeholder="about, mission" required></div>
      <div class="col-12"><textarea class="form-control" name="content" rows="6" placeholder="Page Content" required></textarea></div>
      <div class="col-md-3 mt-2"><button class="btn btn-primary w-100">Add Page</button></div>
    <?php endif; ?>
  </form>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>#</th><th>Title</th><th>Slug</th><th>Action</th></tr></thead>
      <tbody>
        <?php while($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= e($r['title']) ?></td>
          <td><code><?= e($r['slug']) ?></code></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-info" href="pages.php?edit=<?= $r['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="pages.php?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this page?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
