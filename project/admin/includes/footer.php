<?php /* admin/includes/footer.php */ ?>
    </div> <!-- /.container-fluid -->
  </main>
</div> <!-- /.admin-shell -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* Sidebar collapse + persist */
(function () {
  const SID_KEY = 'admin-sidebar-collapsed';
  const el   = document.getElementById('sidebar');
  const btn1 = document.getElementById('sidebarToggle');
  const btn2 = document.getElementById('sidebarToggleTop');

  function setCollapsed(v){
    if (!el) return;
    if (v) { el.classList.add('collapsed'); }
    else   { el.classList.remove('collapsed'); }
  }
  setCollapsed(localStorage.getItem(SID_KEY) === '1');

  [btn1, btn2].forEach(b => {
    if (!b) return;
    b.addEventListener('click', () => {
      const collapsed = !el.classList.contains('collapsed');
      setCollapsed(collapsed);
      localStorage.setItem(SID_KEY, collapsed ? '1' : '0'); // ✅ fixed
    });
  });
})();

/* Theme toggle + persist */
(function () {
  const KEY = 'admin-theme';
  const saved = localStorage.getItem(KEY);
  if (saved === 'light') { document.body.classList.add('light'); }

  const btn = document.getElementById('themeToggle');
  function render(){
    const light = document.body.classList.contains('light');
    if (!btn) return;
    btn.innerHTML = light
      ? '<i class="fa fa-sun me-1"></i> <span class="nav-label">Light</span>'
      : '<i class="fa fa-moon me-1"></i> <span class="nav-label">Dark</span>';
  }
  render();

  if (btn) {
    btn.addEventListener('click', () => {
      document.body.classList.toggle('light');
      localStorage.setItem(KEY, document.body.classList.contains('light') ? 'light' : 'dark');
      render();
    });
  }
})();
</script>
