/* ================================================================
   ADMIN PANEL — JS
   Dark Mode · Accent Color · Sidebar Mobile Toggle
   ================================================================ */

(function () {
  'use strict';

  /* ── Helpers ─────────────────────────────────────────────── */
  const html   = document.documentElement;
  const LS_THEME  = 'adm-theme';
  const LS_ACCENT = 'adm-accent';

  /* ── Dark Mode ───────────────────────────────────────────── */
  function getTheme()      { return localStorage.getItem(LS_THEME) || 'light'; }
  function isDark()        { return getTheme() === 'dark'; }

  function applyTheme(dark) {
    html.setAttribute('data-theme', dark ? 'dark' : '');
    localStorage.setItem(LS_THEME, dark ? 'dark' : 'light');
    syncToggles(dark);
  }

  function syncToggles(dark) {
    // Settings page checkbox
    const sw = document.getElementById('darkModeSwitch');
    if (sw) sw.checked = dark;

    // Status text on settings page
    const txt = document.getElementById('dmStatusText');
    if (txt) txt.textContent = dark
      ? 'Mode gelap aktif. Klik untuk kembali ke mode terang.'
      : 'Aktifkan untuk tampilan gelap yang nyaman di malam hari.';
  }

  // Init on load
  applyTheme(isDark());

  // Settings page switch
  document.addEventListener('DOMContentLoaded', function () {
    syncToggles(isDark());

    const sw = document.getElementById('darkModeSwitch');
    if (sw) {
      sw.addEventListener('change', function () {
        applyTheme(this.checked);
      });
    }

    // Topbar quick toggle
    const quickBtn = document.getElementById('quickDarkToggle');
    if (quickBtn) {
      quickBtn.addEventListener('click', function () {
        applyTheme(!isDark());
      });
    }

    /* ── Accent Color ─────────────────────────────────────── */
    const savedAccent = localStorage.getItem(LS_ACCENT);
    if (savedAccent) applyAccent(savedAccent, false);

    const dots = document.querySelectorAll('.adm-color-dot');
    dots.forEach(function (dot) {
      if (savedAccent && dot.dataset.accent === savedAccent) {
        dots.forEach(d => d.classList.remove('selected'));
        dot.classList.add('selected');
      }
      dot.addEventListener('click', function () {
        dots.forEach(d => d.classList.remove('selected'));
        dot.classList.add('selected');
        applyAccent(dot.dataset.accent, true);
      });
    });

    /* ── Sidebar Mobile Toggle ─────────────────────────────── */
    const sidebar = document.getElementById('admSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('open');
      });

      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function (e) {
        if (window.innerWidth < 992) {
          if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('open');
          }
        }
      });
    }

    /* ── Auto-close sidebar on nav link click (mobile) ──────── */
    if (sidebar) {
      sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
          if (window.innerWidth < 992) {
            sidebar.classList.remove('open');
          }
        });
      });
    }

    /* ── Active nav highlight (fallback) ────────────────────── */
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar .nav-link').forEach(function (link) {
      const href = link.getAttribute('href');
      if (href && href !== '#' && currentPath.startsWith(href) && href !== '/') {
        // Already handled by Blade, but ensure active state
      }
    });

  });

  /* ── Accent Color ─────────────────────────────────────────── */
  function applyAccent(color, save) {
    if (!color) return;

    // Compute dark variant (simple darken: reduce lightness)
    const dark = darkenHex(color, 15);
    const rgb  = hexToRgb(color);

    html.style.setProperty('--adm-primary',       color);
    html.style.setProperty('--adm-primary-dark',  dark);
    html.style.setProperty('--adm-primary-rgb',   rgb);
    html.style.setProperty('--adm-primary-light', hexToAlpha(color, 0.1));
    html.style.setProperty('--adm-brand-logo-bg', color);

    if (save) localStorage.setItem(LS_ACCENT, color);
  }

  function hexToRgb(hex) {
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    return r + ',' + g + ',' + b;
  }

  function hexToAlpha(hex, alpha) {
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
  }

  function darkenHex(hex, percent) {
    let r = parseInt(hex.slice(1,3), 16);
    let g = parseInt(hex.slice(3,5), 16);
    let b = parseInt(hex.slice(5,7), 16);
    const factor = (100 - percent) / 100;
    r = Math.max(0, Math.floor(r * factor));
    g = Math.max(0, Math.floor(g * factor));
    b = Math.max(0, Math.floor(b * factor));
    return '#' + [r,g,b].map(x => x.toString(16).padStart(2,'0')).join('');
  }

})();
