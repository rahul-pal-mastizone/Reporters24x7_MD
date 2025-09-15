<?php
include("includes/auth.php");     // make sure this checks session login
include("../config.php");

// ---- Quick Stats ----
$counts = [];

// Posts
$counts['posts_all']       = $conn->query("SELECT COUNT(*) c FROM posts")->fetch_assoc()['c'] ?? 0;
$counts['posts_news']      = $conn->query("SELECT COUNT(*) c FROM posts WHERE type='news'")->fetch_assoc()['c'] ?? 0;
$counts['posts_editorial'] = $conn->query("SELECT COUNT(*) c FROM posts WHERE type='editorial'")->fetch_assoc()['c'] ?? 0;
$counts['posts_press']     = $conn->query("SELECT COUNT(*) c FROM posts WHERE type='press'")->fetch_assoc()['c'] ?? 0;

// Pages / Menus / Banners / Gallery
$counts['pages']    = $conn->query("SELECT COUNT(*) c FROM pages")->fetch_assoc()['c'] ?? 0;
$counts['menus']    = $conn->query("SELECT COUNT(*) c FROM menu_items")->fetch_assoc()['c'] ?? 0;
$counts['banners']  = $conn->query("SELECT COUNT(*) c FROM banners")->fetch_assoc()['c'] ?? 0;
$counts['gallery']  = $conn->query("SELECT COUNT(*) c FROM gallery")->fetch_assoc()['c'] ?? 0;

// Complaints
$counts['complaints_pending'] = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='pending'")->fetch_assoc()['c'] ?? 0;
$counts['complaints_resolved']= $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='resolved'")->fetch_assoc()['c'] ?? 0;

// Contributions
$counts['contrib_count']  = $conn->query("SELECT COUNT(*) c FROM contributions")->fetch_assoc()['c'] ?? 0;
$sum_completed            = $conn->query("SELECT COALESCE(SUM(amount),0) s FROM contributions WHERE payment_status='completed'")->fetch_assoc()['s'] ?? 0;
$counts['contrib_amount'] = number_format((float)$sum_completed, 2);

// Messages
$counts['messages'] = $conn->query("SELECT COUNT(*) c FROM contact_messages")->fetch_assoc()['c'] ?? 0;

// ---- Recent Activity ----
$recent_posts         = $conn->query("SELECT id, type, title, created_at FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_complaints    = $conn->query("SELECT id, name, message, status, created_at FROM complaints ORDER BY created_at DESC LIMIT 5");
$recent_contributions = $conn->query("SELECT id, name, amount, payment_status, created_at FROM contributions ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8" />
  <title>डैशबोर्ड | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body{ background:#0f172a; color:#e2e8f0;}
    .card{ border:0; border-radius:16px; background:#1f2937; color:#e5e7eb; box-shadow:0 6px 20px rgba(0,0,0,.2);}
    .card .icon{ font-size:28px; width:52px; height:52px; display:inline-flex; align-items:center; justify-content:center; border-radius:12px; margin-right:10px;}
    .soft-green{ background:#064e3b; color:#34d399;}
    .soft-blue{ background:#0c4a6e; color:#60a5fa;}
    .soft-amber{ background:#78350f; color:#fbbf24;}
    .soft-pink{ background:#701a75; color:#f472b6;}
    .soft-red{ background:#7f1d1d; color:#f87171;}
    .soft-sky{ background:#0b3b52; color:#38bdf8;}
    .stat-value{ font-size:22px; font-weight:700;}
    .stat-label{ opacity:.8; font-size:13px;}
    .table-dark{ --bs-table-bg:#0b1220; --bs-table-striped-bg:#0e182b; --bs-table-striped-color:#cbd5e1;}
    a.text-reset:hover{ opacity:.85;}
  </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<div class="container py-4">

  <!-- Greeting -->
  <div class="mb-4">
    <h2 class="mb-1">👋 स्वागत है, Admin!</h2>
    <p class="text-secondary">यह आपका कंट्रोल रूम है — एक नज़र में पोस्ट, शिकायतें, सहयोग और संदेश।</p>
  </div>

    <!-- Quick Create / Shortcuts -->
  <div class="row g-3 mb-2">
    <div class="col-6 col-md-3">
      <a href="posts.php#add" class="card h-100 text-decoration-none">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 rounded-3 p-3" style="background:#0c4a6e;color:#60a5fa;"><i class="fa fa-plus fa-lg"></i></div>
          <div>
            <div class="fw-bold">New Post</div>
            <div class="small text-muted">📰 / ✍️ / 📢</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="banners.php#add" class="card h-100 text-decoration-none">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 rounded-3 p-3" style="background:#78350f;color:#fbbf24;"><i class="fa fa-image fa-lg"></i></div>
          <div>
            <div class="fw-bold">Add Banner</div>
            <div class="small text-muted">📢 Home banner</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="pages.php#add" class="card h-100 text-decoration-none">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 rounded-3 p-3" style="background:#064e3b;color:#34d399;"><i class="fa fa-file-circle-plus fa-lg"></i></div>
          <div>
            <div class="fw-bold">New Page</div>
            <div class="small text-muted">➕ Static content</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="gallery.php#add" class="card h-100 text-decoration-none">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 rounded-3 p-3" style="background:#701a75;color:#f472b6;"><i class="fa fa-photo-film fa-lg"></i></div>
          <div>
            <div class="fw-bold">Add Gallery</div>
            <div class="small text-muted">🖼️ Image</div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Top Stats -->
  <div class="row g-3">
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-blue"><i class="fa fa-newspaper"></i></div>
          <div>
            <div class="stat-value"><?= $counts['posts_all'] ?></div>
            <div class="stat-label">कुल पोस्ट (📰/✍️/📢)</div>
          </div>
        </div>
        <div class="mt-3 small text-secondary">
          📰 News: <b><?= $counts['posts_news'] ?></b> • ✍️ Editorial: <b><?= $counts['posts_editorial'] ?></b> • 📢 Press: <b><?= $counts['posts_press'] ?></b>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-amber"><i class="fa fa-pen-to-square"></i></div>
          <div>
            <div class="stat-value"><?= $counts['complaints_pending'] ?></div>
            <div class="stat-label">लंबित शिकायतें</div>
          </div>
        </div>
        <div class="mt-3 small text-secondary">✅ सुलझी: <b><?= $counts['complaints_resolved'] ?></b></div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-green"><i class="fa fa-hand-holding-dollar"></i></div>
          <div>
            <div class="stat-value">₹ <?= $counts['contrib_amount'] ?></div>
            <div class="stat-label">Completed सहयोग</div>
          </div>
        </div>
        <div class="mt-3 small text-secondary">कुल एंट्री: <b><?= $counts['contrib_count'] ?></b></div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-sky"><i class="fa fa-envelope"></i></div>
          <div>
            <div class="stat-value"><?= $counts['messages'] ?></div>
            <div class="stat-label">Contact Messages</div>
          </div>
        </div>
        <div class="mt-3 small text-secondary">📨 नए संदेश देखें: <a class="text-reset" href="messages.php"><u>खोलें</u></a></div>
      </div>
    </div>
  </div>

  <!-- Secondary Stats -->
  <div class="row g-3 mt-1">
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-pink"><i class="fa fa-file"></i></div>
          <div>
            <div class="stat-value"><?= $counts['pages'] ?></div>
            <div class="stat-label">Pages</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-blue"><i class="fa fa-bars"></i></div>
          <div>
            <div class="stat-value"><?= $counts['menus'] ?></div>
            <div class="stat-label">Menu Items</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-amber"><i class="fa fa-image"></i></div>
          <div>
            <div class="stat-value"><?= $counts['banners'] ?></div>
            <div class="stat-label">Banners</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card p-3 h-100">
        <div class="d-flex align-items-center">
          <div class="icon soft-red"><i class="fa fa-photo-film"></i></div>
          <div>
            <div class="stat-value"><?= $counts['gallery'] ?></div>
            <div class="stat-label">Gallery Images</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="row g-3 mt-4">
    <!-- Recent Posts -->
    <div class="col-lg-6">
      <div class="card p-3 h-100">
        <h5 class="mb-3">📰 हाल के पोस्ट</h5>
        <table class="table table-dark table-striped align-middle">
          <thead>
            <tr><th>#</th><th>शीर्षक</th><th>टाइप</th><th>तारीख</th><th></th></tr>
          </thead>
          <tbody>
          <?php if($recent_posts && $recent_posts->num_rows): $i=1; while($p=$recent_posts->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td class="text-truncate" style="max-width:260px;"><?= htmlspecialchars($p['title']) ?></td>
              <td>
                <?php
                  echo $p['type']=='news' ? '📰 News' : ($p['type']=='editorial' ? '✍️ Editorial' : '📢 Press');
                ?>
              </td>
              <td><small><?= $p['created_at'] ?></small></td>
              <td><a class="btn btn-sm btn-outline-info" href="../post.php?id=<?= $p['id'] ?>" target="_blank"><i class="fa fa-external-link"></i></a></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5">कोई रिकॉर्ड नहीं।</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
        <a href="posts.php" class="btn btn-sm btn-info">सभी पोस्ट मैनेज करें</a>
      </div>
    </div>

    <!-- Recent Complaints & Contributions -->
    <div class="col-lg-6">
      <div class="card p-3 mb-3">
        <h5 class="mb-3">📝 हाल की शिकायतें</h5>
        <table class="table table-dark table-striped align-middle">
          <thead><tr><th>#</th><th>नाम</th><th>स्थिति</th><th>तारीख</th></tr></thead>
          <tbody>
          <?php if($recent_complaints && $recent_complaints->num_rows): $i=1; while($c=$recent_complaints->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($c['name']) ?></td>
              <td>
                <?php if($c['status']=='pending'): ?>
                  <span class="badge bg-warning text-dark">Pending</span>
                <?php else: ?>
                  <span class="badge bg-success">Resolved</span>
                <?php endif; ?>
              </td>
              <td><small><?= $c['created_at'] ?></small></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="4">कोई रिकॉर्ड नहीं।</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
        <a href="complaints.php" class="btn btn-sm btn-warning">शिकायतें देखें</a>
      </div>

      <div class="card p-3">
        <h5 class="mb-3">💰 हाल के सहयोग</h5>
        <table class="table table-dark table-striped align-middle">
          <thead><tr><th>#</th><th>नाम</th><th>राशि</th><th>स्थिति</th><th>तारीख</th></tr></thead>
        <tbody>
        <?php if($recent_contributions && $recent_contributions->num_rows): $i=1; while($g=$recent_contributions->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($g['name']) ?></td>
            <td>₹ <?= number_format($g['amount'],2) ?></td>
            <td>
              <?php
                $badge = $g['payment_status']=='completed' ? 'success' : ($g['payment_status']=='pending' ? 'secondary' : 'danger');
                echo "<span class='badge bg-{$badge}'>".ucfirst($g['payment_status'])."</span>";
              ?>
            </td>
            <td><small><?= $g['created_at'] ?></small></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="5">कोई रिकॉर्ड नहीं।</td></tr>
        <?php endif; ?>
        </tbody>
        </table>
        <a href="contributions.php" class="btn btn-sm btn-success">सहयोग सूची खोलें</a>
      </div>
    </div>
  </div>

</div>

<?php include("includes/footer.php"); ?>
</body>
</html>
