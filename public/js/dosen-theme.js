(function () {
    'use strict';

    var root = document.documentElement;
    var key = 'dsn-theme';

    function applyTheme(dark) {
        root.setAttribute('data-theme', dark ? 'dark' : 'light');
        root.setAttribute('data-bs-theme', dark ? 'dark' : 'light');
        document.querySelectorAll('[data-dosen-theme-toggle]').forEach(function (button) {
            button.setAttribute('aria-pressed', dark ? 'true' : 'false');
            button.setAttribute('aria-label', dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
            button.title = dark ? 'Mode terang' : 'Mode gelap';
        });
    }

    var saved = 'light';
    try {
        saved = window.localStorage.getItem(key) || 'light';
    } catch (e) {
    }
    applyTheme(saved === 'dark');

    function init() {
        applyTheme(root.getAttribute('data-theme') === 'dark');
        document.querySelectorAll('[data-dosen-theme-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var dark = root.getAttribute('data-theme') !== 'dark';
                applyTheme(dark);
                try {
                    window.localStorage.setItem(key, dark ? 'dark' : 'light');
                } catch (e) {
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
