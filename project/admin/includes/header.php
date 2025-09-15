<?php
// admin/includes/header.php
// NOTE: Include order on every admin page should be:
// require_once("includes/auth.php"); include("../config.php"); include("includes/header.php");  [then optional topbar.php]

$active = basename($_SERVER['PHP_SELF']); // to highlight active item
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

<style>
  :root{
    --bg:#0f172a; --card:#1f2937; --text:#e5e7eb; --muted:#94a3b8; --nav:#0b1220; --link:#60a5fa;
  }
  body.light{ --bg:#f4f6fb; --card:#ffffff; --text:#0f172a; --muted:#475569; --nav:#0d6efd; --link:#0d6efd; }
  body{ background:var(--bg); color:var(--text); }

  .admin-shell{ display:flex; min-height:100vh; }
  .sidebar{
    width:240px; background:var(--nav); color:#fff; position:sticky; top:0; height:100vh; overflow-y:auto;
    box-shadow:0 0 20px rgba(0,0,0,.25);
  }
  .sidebar.collapsed{ width:72px; }
  .sidebar .brand{
    display:flex; align-items:center; justify-content:space-between; padding:14px 12px; font-weight:700;
  }
  .sidebar .brand .logo{ display:flex; align-items:center; gap:10px; white-space:nowrap; overflow:hidden; }
  .sidebar.collapsed .brand .title-text{ display:none; }
  .sidebar .nav-section{ padding:10px 8px; }
  .sidebar a.nav-link{
    color:#e2e8f0; border-radius:10px; padding:10px 12px; display:flex; align-items:center; gap:10px; text-decoration:none;
  }
  .sidebar a.nav-link:hover{ background:rgba(255,255,255,.08); }
  .sidebar a.active{ background:rgba(255,255,255,.15); color:#fff; }
  .sidebar .nav-label{ white-space:nowrap; overflow:hidden; }
  .sidebar.collapsed .nav-label{ display:none; }

  .content{ flex:1; }
  .topbar{
    display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:rgba(255,255,255,.03);
    border-bottom:1px solid rgba(255,255,255,.08);
  }
  .btn-ghost{ background:transparent; border:1px solid rgba(255,255,255,.15); color:var(--text); }
  body.light .btn-ghost{ border-color:rgba(0,0,0,.15); }

  .sidebar .footer{
    margin-top:auto; padding:8px; border-top:1px solid rgba(255,255,255,.15);
  }
  .sidebar .footer .btn{ width:100%; margin-bottom:8px; }
  a{ color:var(--link); }
</style>

<div class="admin-shell">
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="logo">
        <i class="fa fa-gear me-2"></i> <span class="title-text">Admin Panel</span>
      </div>
      <!-- <button id="sidebarToggle" class="btn btn-ghost btn-sm" title="Toggle sidebar">
        <i class="fa fa-bars"></i>
      </button> -->
    </div>

    <div class="nav-section">
      <a class="nav-link <?= $active==='dashboard.php'?'active':'' ?>" href="dashboard.php">
        <i class="fa fa-house"></i> <span class="nav-label">Dashboard</span>
      </a>
      <a class="nav-link <?= $active==='menu.php'?'active':'' ?>" href="menu.php">
        <i class="fa fa-bars"></i> <span class="nav-label">Menus</span>
      </a>
      <a class="nav-link <?= $active==='pages.php'?'active':'' ?>" href="pages.php">
        <i class="fa fa-file"></i> <span class="nav-label">Pages</span>
      </a>
      <a class="nav-link <?= $active==='posts.php'?'active':'' ?>" href="posts.php">
        <i class="fa fa-newspaper"></i> <span class="nav-label">Posts</span>
      </a>
      <a class="nav-link <?= $active==='banners.php'?'active':'' ?>" href="banners.php">
        <i class="fa fa-image"></i> <span class="nav-label">Banners</span>
      </a>
      <a class="nav-link <?= $active==='gallery.php'?'active':'' ?>" href="gallery.php">
        <i class="fa fa-photo-film"></i> <span class="nav-label">Gallery</span>
      </a>
      <a class="nav-link <?= $active==='complaints.php'?'active':'' ?>" href="complaints.php">
        <i class="fa fa-pen-to-square"></i> <span class="nav-label">Complaints</span>
      </a>
      <a class="nav-link <?= $active==='contributions.php'?'active':'' ?>" href="contributions.php">
        <i class="fa fa-hand-holding-dollar"></i> <span class="nav-label">Contributions</span>
      </a>
      <a class="nav-link <?= in_array($active,['messages.php','view_message.php'])?'active':'' ?>" href="messages.php">
        <i class="fa fa-envelope"></i> <span class="nav-label">Messages</span>
      </a>
    </div>

    <div class="footer">
      <button id="themeToggle" class="btn btn-ghost btn-sm w-100 mb-2">
        <i class="fa fa-moon me-1"></i> <span class="nav-label">Dark</span>
      </button>
      <a href="logout.php" class="btn btn-danger btn-sm"><i class="fa fa-right-from-bracket me-1"></i> <span class="nav-label">Logout</span></a>
    </div>
  </aside>

  <!-- MAIN AREA -->
  <main class="content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-2">
        <button id="sidebarToggleTop" class="btn btn-ghost btn-sm"><i class="fa fa-bars"></i></button>
        <span class="fw-semibold">Admin</span>
      </div>
      <div class="small text-muted">Manage content</div>
    </div>
    <div class="container-fluid py-3">
