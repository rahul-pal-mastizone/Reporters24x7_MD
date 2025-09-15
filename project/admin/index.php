<?php
// admin/index.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config.php");
mysqli_set_charset($conn, 'utf8mb4');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=? LIMIT 1");
    if (!$stmt) {
        $error = "Database error: ".$conn->error;
    } else {
        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            $error = "Query failed.";
        } else {
            $stmt->store_result();
            $stmt->bind_result($db_id, $db_hash);

            if ($stmt->num_rows === 1 && $stmt->fetch()) {
                $valid = false;

                // bcrypt (normal path)
                if (password_verify($password, $db_hash)) {
                    $valid = true;
                    // optional rehash
                    if (password_needs_rehash($db_hash, PASSWORD_BCRYPT)) {
                        $newhash = password_hash($password, PASSWORD_BCRYPT);
                        $upd = $conn->prepare("UPDATE admins SET password=? WHERE id=?");
                        if ($upd) { $upd->bind_param("si", $newhash, $db_id); $upd->execute(); }
                    }
                }
                // legacy plaintext fallback (auto-upgrade)
                elseif ($password === $db_hash) {
                    $valid = true;
                    $newhash = password_hash($password, PASSWORD_BCRYPT);
                    $upd = $conn->prepare("UPDATE admins SET password=? WHERE id=?");
                    if ($upd) { $upd->bind_param("si", $newhash, $db_id); $upd->execute(); }
                }

                if ($valid) {
                    $_SESSION['admin_id'] = $db_id;
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#0f172a; }
    .card { background:#1f2937; color:#e5e7eb; border-radius:16px; border:0; }
  </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="mx-auto card p-4" style="max-width:420px;">
      <h3 class="mb-3">🔐 Admin Login</h3>
      <?php if(!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
      <a href="../index.php" class="btn btn-link mt-2">← Back to site</a>
    </div>
  </div>
</body>
</html>
