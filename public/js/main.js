(function () {
    'use strict';

    function onReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }

    function initConfirmForms() {
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) {
                return;
            }
            if (form.dataset.confirmed === 'true') {
                return;
            }
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                event.preventDefault();
                event.stopImmediatePropagation();
                return;
            }
            form.dataset.confirmed = 'true';
        }, true);
    }

    function initNavbar() {
        var navbar = document.getElementById('scNavbar');
        if (!navbar) {
            return;
        }

        var toggleShadow = function () {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        };
        toggleShadow();
        window.addEventListener('scroll', toggleShadow, { passive: true });

        var collapseEl = document.getElementById('navbarNav');
        var toggler = navbar.querySelector('.navbar-toggler');
        if (!collapseEl || !toggler || typeof bootstrap === 'undefined') {
            return;
        }

        var collapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
        var isMobile = function () {
            return window.getComputedStyle(toggler).display !== 'none';
        };

        collapseEl.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile() && collapseEl.classList.contains('show')) {
                    collapse.hide();
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (isMobile() && collapseEl.classList.contains('show') && !navbar.contains(event.target)) {
                collapse.hide();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && collapseEl.classList.contains('show')) {
                collapse.hide();
                toggler.focus();
            }
        });

        window.addEventListener('resize', function () {
            if (!isMobile() && collapseEl.classList.contains('show')) {
                collapse.hide();
            }
        });
    }

    function initScrollTop() {
        var button = document.getElementById('scroll-top');
        if (!button) {
            return;
        }
        var toggle = function () {
            button.classList.toggle('active', window.scrollY > 200);
        };
        toggle();
        window.addEventListener('scroll', toggle, { passive: true });
        button.addEventListener('click', function (event) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    function initGallery() {
        var gallery = document.getElementById('galleryCarousel');
        if (gallery && typeof bootstrap !== 'undefined') {
            bootstrap.Carousel.getOrCreateInstance(gallery, { interval: 4000, wrap: true, pause: 'hover' });
        }
    }

    function initTabs() {
        document.querySelectorAll('[data-tab-group]').forEach(function (group) {
            var name = group.getAttribute('data-tab-group');
            var buttons = group.querySelectorAll('[data-tab-target]');
            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    buttons.forEach(function (item) {
                        var active = item === button;
                        item.classList.toggle('active', active);
                        item.setAttribute('aria-selected', active ? 'true' : 'false');
                        item.tabIndex = active ? 0 : -1;
                    });
                    document.querySelectorAll('[data-tab-panel="' + name + '"]').forEach(function (panel) {
                        panel.hidden = panel.id !== button.getAttribute('data-tab-target');
                    });
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                });
                button.addEventListener('keydown', function (event) {
                    if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') {
                        return;
                    }
                    var list = Array.prototype.slice.call(buttons);
                    var index = list.indexOf(button) + (event.key === 'ArrowRight' ? 1 : -1);
                    var next = list[(index + list.length) % list.length];
                    next.focus();
                    next.click();
                });
            });
        });
    }

    function initDisclosure() {
        document.querySelectorAll('[data-open]').forEach(function (button) {
            var target = document.querySelector(button.getAttribute('data-open'));
            if (!target) {
                return;
            }
            button.addEventListener('click', function () {
                target.hidden = false;
                button.hidden = true;
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                var field = target.querySelector('input:not([type="hidden"]):not([type="file"]), textarea, select');
                if (field) {
                    window.setTimeout(function () {
                        field.focus({ preventScroll: true });
                    }, 350);
                }
            });
        });
    }

    function initImagePreview() {
        document.querySelectorAll('[data-preview-input]').forEach(function (input) {
            var box = input.closest('form').querySelector('[data-preview-box]');
            if (!box) {
                return;
            }
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file || !file.type.match(/^image\//)) {
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (event) {
                    var img = box.querySelector('img');
                    if (!img) {
                        box.innerHTML = '';
                        img = document.createElement('img');
                        img.alt = 'Pratinjau foto profil';
                        box.appendChild(img);
                    }
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    }

    function initFlash() {
        document.querySelectorAll('.flash-stack--floating .alert').forEach(function (alert) {
            window.setTimeout(function () {
                if (typeof bootstrap !== 'undefined' && document.body.contains(alert)) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 6000);
        });
    }

    function initAos() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                once: true,
                duration: 700,
                offset: 40,
                disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
            });
        }
    }

    initConfirmForms();

    onReady(function () {
        initNavbar();
        initScrollTop();
        initGallery();
        initTabs();
        initDisclosure();
        initImagePreview();
        initFlash();
        initAos();
    });
})();
