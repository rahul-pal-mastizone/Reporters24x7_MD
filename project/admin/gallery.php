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
  $name = 'gallery_'.date('Ymd_His').'_'.bin2hex(random_bytes(3)).'.'.strtolower($ext);
  if(move_uploaded_file($_FILES[$field]['tmp_name'], $dir.$name)) return [true,$name];
  return [false,null];
}

$edit=null; $msg=null;

// CREATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='create'){
  $title=trim($_POST['title']??'');
  $cat=trim($_POST['category']??'');
  if($title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if(!$okUp){ $msg=['t'=>'e','m'=>'Image upload failed.']; }
    else{
      $st=$conn->prepare("INSERT INTO gallery(title,image,category) VALUES(?,?,?)");
      $st->bind_param('sss',$title,$img,$cat);
      if($st->execute()) $msg=['t'=>'o','m'=>'Image added.']; else $msg=['t'=>'e','m'=>'Could not add image.'];
      $st->close();
    }
  }
}

// LOAD EDIT
if(isset($_GET['edit'])){
  $id=(int)$_GET['edit'];
  $st=$conn->prepare("SELECT * FROM gallery WHERE id=?");
  $st->bind_param('i',$id); $st->execute();
  $edit=$st->get_result()->fetch_assoc(); $st->close();
}

// UPDATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update'){
  $id=(int)($_POST['id']??0);
  $title=trim($_POST['title']??'');
  $cat=trim($_POST['category']??'');
  if($id<=0||$title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if($img){
      $o=$conn->query("SELECT image FROM gallery WHERE id={$id}")->fetch_assoc();
      $st=$conn->prepare("UPDATE gallery SET title=?, category=?, image=? WHERE id=?");
      $st->bind_param('sssi',$title,$cat,$img,$id);
      if($st->execute()){
        if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
        $msg=['t'=>'o','m'=>'Image updated.']; $edit=null;
      } else $msg=['t'=>'e','m'=>'Could not update.'];
      $st->close();
    } else {
      $st=$conn->prepare("UPDATE gallery SET title=?, category=? WHERE id=?");
      $st->bind_param('ssi',$title,$cat,$id);
      if($st->execute()){ $msg=['t'=>'o','m'=>'Image updated.']; $edit=null; }
      else $msg=['t'=>'e','m'=>'Could not update.'];
      $st->close();
    }
  }
}

// DELETE
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $o=$conn->query("SELECT image FROM gallery WHERE id={$id}")->fetch_assoc();
  $st=$conn->prepare("DELETE FROM gallery WHERE id=?");
  $st->bind_param('i',$id);
  if($st->execute()){
    if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
    $msg=['t'=>'o','m'=>'Image deleted.'];
  }
  $st->close();
}

$rows=$conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>
<div class="container-fluid px-3 py-3">
  <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>

  <h3 class="mb-3">Gallery</h3>
  <?php if($msg){ $msg['t']==='o'?ok($msg['m']):err($msg['m']); } ?>

  <form method="post" enctype="multipart/form-data" class="row gy-2 gx-2 mb-4">
    <?php if($edit): ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= e($edit['id']) ?>">
    <?php else: ?>
      <input type="hidden" name="action" value="create">
    <?php endif; ?>
    <div class="col-md-3"><input class="form-control" name="title" placeholder="Title" value="<?= $edit?e($edit['title']):'' ?>" required></div>
    <div class="col-md-3"><input class="form-control" name="category" placeholder="Category (e.g., Movements)" value="<?= $edit?e($edit['category']):'' ?>"></div>
    <div class="col-md-3"><input class="form-control" type="file" name="image" accept="image/*"></div>
    <div class="col-md-3 d-flex gap-2">
      <button class="btn btn-primary"><?= $edit?'Save':'Add Image' ?></button>
      <?php if($edit): ?><a href="gallery.php" class="btn btn-outline-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Action</th></tr></thead>
      <tbody>
      <?php while($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?php if($r['image']): ?><img src="../uploads/<?= e($r['image']) ?>" style="height:50px"><?php endif; ?></td>
          <td><?= e($r['title']) ?></td>
          <td><?= e($r['category']) ?></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-info" href="gallery.php?edit=<?= $r['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="gallery.php?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this image?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
