<?php
require_once("includes/auth.php");   // FIRST! No output before this.
require_once("../config.php");
include("includes/header.php");
include("includes/topbar.php");      // ok to output now
?>
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$msg = $conn->query("SELECT * FROM contact_messages WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>View Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../assets/style.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->
</head>
<body class="bg-dark text-light">

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <?php if($msg): ?>
            <h3><i class="fa fa-envelope-open"></i> <?= $msg['subject'] ?></h3>
            <hr class="border-info">
            <p><b>नाम:</b> <?= $msg['name'] ?></p>
            <p><b>ईमेल:</b> <?= $msg['email'] ?></p>
            <p><b>संदेश:</b><br><?= nl2br($msg['message']) ?></p>
            <p class="text-muted small"><i class="fa fa-calendar"></i> <?= $msg['created_at'] ?></p>
            <a href="messages.php" class="btn btn-light"><i class="fa fa-arrow-left"></i> वापस जाएँ</a>
        <?php else: ?>
            <p>❌ संदेश नहीं मिला।</p>
        <?php endif; ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
</body>
</html>
