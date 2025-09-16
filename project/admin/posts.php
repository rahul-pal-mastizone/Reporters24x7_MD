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
  $name = 'post_'.date('Ymd_His').'_'.bin2hex(random_bytes(3)).'.'.strtolower($ext);
  $dest = $dir.$name;
  if(move_uploaded_file($_FILES[$field]['tmp_name'],$dest)) return [true,$name];
  return [false,null];
}

$edit=null; $msg=null;

// CREATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='create'){
  $type = $_POST['type'] ?? 'news';
  $title= trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  if($title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if(!$okUp){ $msg=['t'=>'e','m'=>'Image upload failed.']; }
    else{
      $st=$conn->prepare("INSERT INTO posts(type,title,content,image) VALUES(?,?,?,?)");
      $st->bind_param('ssss',$type,$title,$content,$img);
      if($st->execute()) $msg=['t'=>'o','m'=>'Post added.']; else $msg=['t'=>'e','m'=>'Could not add post.'];
      $st->close();
    }
  }
}

// LOAD EDIT
if(isset($_GET['edit'])){
  $id=(int)$_GET['edit'];
  $st=$conn->prepare("SELECT * FROM posts WHERE id=?");
  $st->bind_param('i',$id); $st->execute();
  $edit=$st->get_result()->fetch_assoc(); $st->close();
}

// UPDATE
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update'){
  $id=(int)($_POST['id']??0);
  $type=$_POST['type'] ?? 'news';
  $title=trim($_POST['title']??'');
  $content=trim($_POST['content']??'');
  if($id<=0||$title===''){ $msg=['t'=>'e','m'=>'Title required.']; }
  else{
    [$okUp,$img]=upload_image('image');
    if($img){
      // get old image
      $o=$conn->query("SELECT image FROM posts WHERE id={$id}")->fetch_assoc();
      $st=$conn->prepare("UPDATE posts SET type=?, title=?, content=?, image=? WHERE id=?");
      $st->bind_param('ssssi',$type,$title,$content,$img,$id);
      if($st->execute()) {
        if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
        $msg=['t'=>'o','m'=>'Post updated.'];
        $edit=null;
      } else $msg=['t'=>'e','m'=>'Could not update post.'];
      $st->close();
    } else {
      $st=$conn->prepare("UPDATE posts SET type=?, title=?, content=? WHERE id=?");
      $st->bind_param('sssi',$type,$title,$content,$id);
      if($st->execute()) { $msg=['t'=>'o','m'=>'Post updated.']; $edit=null; }
      else $msg=['t'=>'e','m'=>'Could not update post.'];
      $st->close();
    }
  }
}

// DELETE
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $o=$conn->query("SELECT image FROM posts WHERE id={$id}")->fetch_assoc();
  $st=$conn->prepare("DELETE FROM posts WHERE id=?");
  $st->bind_param('i',$id);
  if($st->execute()) {
    if($o && $o['image']) @unlink(dirname(__DIR__).'/uploads/'.$o['image']);
    $msg=['t'=>'o','m'=>'Post deleted.'];
  }
  $st->close();
}

$rows=$conn->query("SELECT * FROM posts ORDER BY created_at DESC, id DESC");
?>
<div class="container-fluid px-3 py-3">
  <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Back to Dashboard</a>

  <h3 class="mb-3">Add / Manage Posts</h3>
  <?php if($msg){ $msg['t']==='o'?ok($msg['m']):err($msg['m']); } ?>

  <form method="post" enctype="multipart/form-data" class="row gy-2 gx-2 mb-4">
    <?php if($edit): ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= e($edit['id']) ?>">
    <?php else: ?>
      <input type="hidden" name="action" value="create">
    <?php endif; ?>

    <div class="col-md-2">
      <select name="type" class="form-select">
        <?php $types=['news'=>'News','editorial'=>'Editorial','press'=>'Press']; 
        foreach($types as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $edit && $edit['type']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3"><input class="form-control" name="title" placeholder="Title" value="<?= $edit?e($edit['title']):'' ?>" required></div>
    <div class="col-md-5"><textarea class="form-control" name="content" rows="2" placeholder="Content"><?= $edit?e($edit['content']):'' ?></textarea></div>
    <div class="col-md-2 d-flex gap-2">
      <input class="form-control" type="file" name="image" accept="image/*">
      <button class="btn btn-primary"><?= $edit?'Save':'Add Post' ?></button>
      <?php if($edit): ?><a href="posts.php" class="btn btn-outline-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead><tr><th>#</th><th>Type</th><th>Title</th><th>Date</th><th>Image</th><th>Action</th></tr></thead>
      <tbody>
        <?php while($r=$rows->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><span class="badge bg-secondary"><?= e($r['type']) ?></span></td>
          <td><?= e($r['title']) ?></td>
          <td><?= e($r['created_at']) ?></td>
          <td><?php if($r['image']): ?><img src="../uploads/<?= e($r['image']) ?>" style="height:40px"><?php endif; ?></td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-info" href="posts.php?edit=<?= $r['id'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger" href="posts.php?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
