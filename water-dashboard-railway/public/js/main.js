// ============================================================
// AquaFarm - Main JavaScript
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

  // ---- Sidebar Toggle ----
  const sidebar = document.getElementById('sidebar');
  const toggle  = document.getElementById('sidebarToggle');
  const main    = document.getElementById('mainContent');

  if (toggle && sidebar) {
    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      if (window.innerWidth > 992) {
        if (sidebar.classList.contains('collapsed')) {
          sidebar.classList.remove('collapsed');
          main.style.marginLeft = '260px';
        } else {
          sidebar.classList.add('collapsed');
          main.style.marginLeft = '0';
        }
      }
    });
  }

  // Close sidebar on outside click (mobile)
  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 992 && sidebar && !sidebar.contains(e.target) && toggle && !toggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  });

  // ---- Dark Mode Toggle ----
  const darkBtn   = document.getElementById('darkModeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const htmlEl    = document.documentElement;

  const savedTheme = localStorage.getItem('aquafarm-theme') || 'dark';
  htmlEl.setAttribute('data-theme', savedTheme);
  if (themeIcon) {
    themeIcon.className = savedTheme === 'light' ? 'fas fa-sun' : 'fas fa-moon';
  }

  if (darkBtn) {
    darkBtn.addEventListener('click', function () {
      const current = htmlEl.getAttribute('data-theme');
      const next    = current === 'dark' ? 'light' : 'dark';
      htmlEl.setAttribute('data-theme', next);
      localStorage.setItem('aquafarm-theme', next);
      if (themeIcon) {
        themeIcon.className = next === 'light' ? 'fas fa-sun' : 'fas fa-moon';
      }
    });
  }

  // ---- Auto-dismiss flash messages ----
  const flashes = document.querySelectorAll('.flash-alert');
  flashes.forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity 0.5s';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 500);
    }, 4000);
  });

  // ---- Confirm delete links ----
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(el.dataset.confirm || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // ---- Animate numbers on load ----
  function animateNumber(el, target, duration) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(function () {
      start += step;
      if (start >= target) {
        start = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(start).toLocaleString();
    }, 16);
  }

  // ---- Chart.js Global Defaults ----
  if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = "'Space Grotesk', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.tooltip.backgroundColor = '#1a2235';
    Chart.defaults.plugins.tooltip.borderColor = 'rgba(74,222,128,0.3)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.titleColor = '#f1f5f9';
    Chart.defaults.plugins.tooltip.bodyColor = '#94a3b8';
  }

  // ---- Search filter (client-side table search) ----
  const searchInput = document.querySelector('.topbar-search input');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const query = this.value.toLowerCase();
      document.querySelectorAll('.table-custom tbody tr').forEach(function (row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }

  // ---- Modal close on overlay click ----
  document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) {
        overlay.style.display = 'none';
      }
    });
  });

  // ---- Responsive: fix margin on resize ----
  window.addEventListener('resize', function () {
    if (window.innerWidth > 992 && main) {
      main.style.marginLeft = '260px';
      if (sidebar) sidebar.classList.remove('open');
    } else if (main) {
      main.style.marginLeft = '0';
    }
  });

  // ---- Tooltip init (Bootstrap) ----
  const tooltipEls = document.querySelectorAll('[title]');
  tooltipEls.forEach(function (el) {
    el.setAttribute('data-bs-toggle', 'tooltip');
  });
  if (typeof bootstrap !== 'undefined') {
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (el) { return new bootstrap.Tooltip(el); });
  }

});
