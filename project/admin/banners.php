<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function ok($m){ echo '<div class="alert alert-success mb-3">'.e($m).'</div>'; }
function err($m){ echo '<div class="alert alert-danger mb-3">'.e($m).'</div>'; }
function upload_image($field){
  if(empty($_FILES[$field]['name'])) return [true,null];
  $dir = dirname(__DIR__) . '/uploads/';
  if(!is_dir($dir)) @mkdir($dir,0775,true);
  $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
  $name = 'banner_'.date('Ymd_His').'_'.bin2hex(random_bytes(3)).'.'.strtolower($ext);
  if(move_uploaded_file($_FILES[$field]['tmp_name'], $dir.$name)) return [true,$name];
  return [false,null];
}

$edit=null; $msg=null;

// CREATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='create'){
  $title = trim($_POST['title'] ?? '');
  $pos   = trim($_POST['position'] ?? 'top');
  if($title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if(!$okUp){ $msg=['t'=>'e','m'=>'Image upload failed.']; }
    else{
      $st=$conn->prepare("INSERT INTO banners(title,image,position,status) VALUES(?,?,?,1)");
      $st->bind_param('sss',$title,$img,$pos);
      if($st->execute()) $msg=['t'=>'o','m'=>'Banner added.']; else $msg=['t'=>'e','m'=>'Could not add banner.'];
      $st->close();
    }
  }
}

// LOAD EDIT
if(isset($_GET['edit'])){
  $id=(int)$_GET['edit'];
  $st=$conn->prepare("SELECT * FROM banners WHERE id=?");
  $st->bind_param('i',$id); $st->execute();
  $edit=$st->get_result()->fetch_assoc(); $st->close();
}

// UPDATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update'){
  $id=(int)($_POST['id']??0);
  $title=trim($_POST['title']??'');
  $pos=trim($_POST['position']??'top');
  if($id<=0||$title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if($img){
      $o=$conn->query("SELECT image FROM banners WHERE id={$id}")->fetch_assoc();
      $st=$conn->prepare("UPDATE banners SET title=?, position=?, image=? WHERE id=?");
      $st->bind_param('sssi',$title,$pos,$img,$id);
      if($st->execute()){
        if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
        $msg=['t'=>'o','m'=>'Banner updated.']; $edit=null;
      } else $msg=['t'=>'e','m'=>'Could not update banner.'];
      $st->close();
    } else {
      $st=$conn->prepare("UPDATE banners SET title=?, position=? WHERE id=?");
      $st->bind_param('ssi',$title,$pos,$id);
      if($st->execute()){ $msg=['t'=>'o','m'=>'Banner updated.']; $edit=null; }
      else $msg=['t'=>'e','m'=>'Could not update banner.'];
      $st->close();
    }
  }
}

// DELETE
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $o=$conn->query("SELECT image FROM banners WHERE id={$id}")->fetch_assoc();
  $st=$conn->prepare("DELETE FROM banners WHERE id=?");
  $st->bind_param('i',$id);
  if($st->execute()){
    if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
    $msg=['t'=>'o','m'=>'Banner deleted.'];
  }
  $st->close();
}

$rows=$conn->query("SELECT * FROM banners ORDER BY id DESC");
?>
<div class="container-fluid px-3 py-3">
  <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>

  <h3 class="mb-3">Manage Banners</h3>
  <?php if($msg){ $msg['t']==='o'?ok($msg['m']):err($msg['m']); } ?>

  <form method="post" enctype="multipart/form-data" class="row gy-2 gx-2 mb-4">
    <?php if($edit): ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= e($edit['id']) ?>">
    <?php else: ?>
      <input type="hidden" name="action" value="create">
    <?php endif; ?>
    <div class="col-md-4"><input class="form-control" name="title" placeholder="Banner Title" value="<?= $edit?e($edit['title']):'' ?>" required></div>
    <div class="col-md-3"><input class="form-control" type="file" name="image" accept="image/*"></div>
    <div class="col-md-2">
      <select class="form-select" name="position">
        <?php $positions=['top'=>'Top','bottom'=>'Bottom','sidebar'=>'Sidebar'];
        foreach($positions as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $edit && $edit['position']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button class="btn btn-primary"><?= $edit?'Save':'Add Banner' ?></button>
      <?php if($edit): ?><a href="banners.php" class="btn btn-outline-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>#</th><th>Title</th><th>Position</th><th>Image</th><th>Action</th></tr></thead>
      <tbody>
      <?php while($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= e($r['title']) ?></td>
          <td><span class="badge bg-secondary"><?= e($r['position']) ?></span></td>
          <td><?php if($r['image']): ?><img src="../uploads/<?= e($r['image']) ?>" style="height:40px"><?php endif; ?></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-info" href="banners.php?edit=<?= $r['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="banners.php?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this banner?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
