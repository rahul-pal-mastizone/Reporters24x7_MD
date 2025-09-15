<?php
// admin/includes/topbar.php
// Usage:
//   $showAdd = true;  // if the page has an <a id="add"></a> above the form
//   include("includes/topbar.php");
$showAdd = $showAdd ?? false;
?>
<div class="container pt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="dashboard.php" class="btn btn-ghost btn-sm">
      <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
    </a>
    <?php if ($showAdd): ?>
      <a href="#add" class="btn btn-success btn-sm">
        <i class="fa fa-plus me-1"></i> Add New
      </a>
    <?php endif; ?>
  </div>
</div>
