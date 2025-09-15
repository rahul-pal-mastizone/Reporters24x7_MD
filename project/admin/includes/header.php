<?php
// admin/includes/header.php
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

<style>
  /* Tiny theme layer that works everywhere */
  :root{
    --bg: #0f172a; --card:#1f2937; --text:#e5e7eb; --muted:#94a3b8; --nav:#0b1220;
  }
  body.light{
    --bg:#f4f6fb; --card:#ffffff; --text:#0f172a; --muted:#475569; --nav:#0d6efd;
  }
  body{background:var(--bg); color:var(--text);}
  .card{background:var(--card); color:var(--text); border:0; border-radius:16px;}
  .navbar{background-color:var(--nav)!important;}
  .table-dark{ --bs-table-bg: color-mix(in srgb, var(--bg) 92%, black); --bs-table-striped-bg: color-mix(in srgb, var(--bg) 88%, black); --bs-table-striped-color: var(--text);}
  .btn-ghost{background:transparent;border:1px solid rgba(255,255,255,.15);color:var(--text);}
  body.light .btn-ghost{border-color:rgba(0,0,0,.15);}
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold" href="dashboard.php">
      <i class="fa fa-cogs me-2"></i>Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fa fa-home me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fa fa-bars me-1"></i>Menus</a></li>
        <li class="nav-item"><a class="nav-link" href="pages.php"><i class="fa fa-file me-1"></i>Pages</a></li>
        <li class="nav-item"><a class="nav-link" href="posts.php"><i class="fa fa-newspaper me-1"></i>Posts</a></li>
        <li class="nav-item"><a class="nav-link" href="banners.php"><i class="fa fa-image me-1"></i>Banners</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php"><i class="fa fa-photo-film me-1"></i>Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="complaints.php"><i class="fa fa-pen-to-square me-1"></i>Complaints</a></li>
        <li class="nav-item"><a class="nav-link" href="contributions.php"><i class="fa fa-hand-holding-dollar me-1"></i>Contributions</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fa fa-envelope me-1"></i>Messages</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <!-- Theme toggle -->
        <button id="themeToggle" class="btn btn-ghost btn-sm">
          <i class="fa fa-moon me-1"></i><span class="d-none d-sm-inline">Dark</span>
        </button>
        <a href="logout.php" class="btn btn-danger btn-sm"><i class="fa fa-right-from-bracket me-1"></i>Logout</a>
      </div>
    </div>
  </div>
</nav>

<script>
  // Theme init + toggle (persists in localStorage)
  (function(){
    const KEY='admin-theme';
    const saved = localStorage.getItem(KEY);
    if(saved === 'light'){ document.body.classList.add('light'); }
    const btn = document.getElementById('themeToggle');
    function render(){
      const light = document.body.classList.contains('light');
      btn.innerHTML = light
        ? '<i class="fa fa-sun me-1"></i><span class="d-none d-sm-inline">Light</span>'
        : '<i class="fa fa-moon me-1"></i><span class="d-none d-sm-inline">Dark</span>';
    }
    render();
    btn?.addEventListener('click', function(){
      document.body.classList.toggle('light');
      localStorage.setItem(KEY, document.body.classList.contains('light') ? 'light' : 'dark');
      render();
    });
  })();
</script>
