(function () {
    'use strict';

    var html = document.documentElement;
    var THEME_KEY = 'adm-theme';
    var ACCENT_KEY = 'adm-accent';

    function storageGet(key) {
        try {
            return window.localStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    function storageSet(key, value) {
        try {
            window.localStorage.setItem(key, value);
        } catch (e) {
        }
    }

    function isDark() {
        return storageGet(THEME_KEY) === 'dark';
    }

    function applyTheme(dark) {
        html.setAttribute('data-theme', dark ? 'dark' : '');
        storageSet(THEME_KEY, dark ? 'dark' : 'light');
        var sw = document.getElementById('darkModeSwitch');
        if (sw) {
            sw.checked = dark;
        }
        var text = document.getElementById('dmStatusText');
        if (text) {
            text.textContent = dark
                ? 'Mode gelap aktif. Matikan untuk kembali ke mode terang.'
                : 'Aktifkan untuk tampilan gelap yang nyaman di malam hari.';
        }
    }

    function hexToRgb(hex) {
        return [1, 3, 5].map(function (i) {
            return parseInt(hex.slice(i, i + 2), 16);
        });
    }

    function applyAccent(color, save) {
        if (!/^#[0-9a-f]{6}$/i.test(color || '')) {
            return;
        }
        var rgb = hexToRgb(color);
        var dark = '#' + rgb.map(function (v) {
            return Math.max(0, Math.floor(v * 0.85)).toString(16).padStart(2, '0');
        }).join('');
        html.style.setProperty('--adm-primary', color);
        html.style.setProperty('--adm-primary-dark', dark);
        html.style.setProperty('--adm-primary-rgb', rgb.join(','));
        html.style.setProperty('--adm-primary-light', 'rgba(' + rgb.join(',') + ',0.1)');
        html.style.setProperty('--adm-brand-logo-bg', color);
        if (save) {
            storageSet(ACCENT_KEY, color);
        }
    }

    function initTheme() {
        applyTheme(isDark());
        var sw = document.getElementById('darkModeSwitch');
        if (sw) {
            sw.addEventListener('change', function () {
                applyTheme(sw.checked);
            });
        }
        var quick = document.getElementById('quickDarkToggle');
        if (quick) {
            quick.addEventListener('click', function () {
                applyTheme(!isDark());
            });
        }

        var saved = storageGet(ACCENT_KEY);
        if (saved) {
            applyAccent(saved, false);
        }
        var dots = document.querySelectorAll('.adm-color-dot');
        dots.forEach(function (dot) {
            dot.style.background = dot.getAttribute('data-color');
            if (saved) {
                dot.classList.toggle('selected', dot.getAttribute('data-accent') === saved);
            }
            dot.addEventListener('click', function () {
                dots.forEach(function (d) {
                    d.classList.remove('selected');
                });
                dot.classList.add('selected');
                applyAccent(dot.getAttribute('data-accent'), true);
            });
        });
    }

    function initSidebar() {
        var sidebar = document.getElementById('admSidebar');
        var toggle = document.getElementById('sidebarToggle');
        var close = document.getElementById('sidebarClose');
        var backdrop = document.getElementById('admBackdrop');
        if (!sidebar || !toggle) {
            return;
        }
        var mobile = window.matchMedia('(max-width: 991.98px)');

        function setOpen(open) {
            sidebar.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('adm-no-scroll', open);
            if (backdrop) {
                backdrop.hidden = !open;
            }
            if (open) {
                var first = sidebar.querySelector('.nav-link');
                if (first) {
                    first.focus();
                }
            }
        }

        toggle.addEventListener('click', function () {
            setOpen(!sidebar.classList.contains('open'));
        });
        if (close) {
            close.addEventListener('click', function () {
                setOpen(false);
                toggle.focus();
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', function () {
                setOpen(false);
            });
        }
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && sidebar.classList.contains('open')) {
                setOpen(false);
                toggle.focus();
            }
        });
        mobile.addEventListener('change', function () {
            if (!mobile.matches) {
                setOpen(false);
            }
        });
    }

    function initConfirmForms() {
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (form instanceof HTMLFormElement && form.hasAttribute('data-confirm') && !window.confirm(form.getAttribute('data-confirm'))) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        }, true);
    }

    function initTableLabels() {
        document.querySelectorAll('.data-table').forEach(function (table) {
            var heads = Array.prototype.map.call(table.querySelectorAll('thead th'), function (th) {
                return th.textContent.trim();
            });
            table.querySelectorAll('tbody tr').forEach(function (row) {
                var cells = row.children;
                if (cells.length === 1) {
                    row.classList.add('is-empty-row');
                    return;
                }
                Array.prototype.forEach.call(cells, function (cell, index) {
                    if (heads[index] && !cell.hasAttribute('data-label')) {
                        cell.setAttribute('data-label', heads[index]);
                    }
                    if (!cell.querySelector(':scope > .adm-cell')) {
                        var wrap = document.createElement('div');
                        wrap.className = 'adm-cell';
                        while (cell.firstChild) {
                            wrap.appendChild(cell.firstChild);
                        }
                        cell.appendChild(wrap);
                    }
                });
            });
            table.classList.add('is-stackable');
        });
    }

    function initSwitchPanels() {
        document.querySelectorAll('input[data-switch]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                var form = radio.closest('form') || document;
                form.querySelectorAll('[data-switch-panel]').forEach(function (panel) {
                    panel.hidden = panel.getAttribute('data-switch-panel') !== radio.value;
                });
            });
        });
    }

    initConfirmForms();

    document.addEventListener('DOMContentLoaded', function () {
        initTheme();
        initSidebar();
        initTableLabels();
        initSwitchPanels();
    });
})();
