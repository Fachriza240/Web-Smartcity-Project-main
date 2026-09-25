(function () {
    'use strict';

    function initPasswordToggle() {
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            var input = document.getElementById(button.getAttribute('data-toggle-password'));
            var icon = button.querySelector('i');
            if (!input) {
                return;
            }
            button.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                icon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
                button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    }

    function initRoleSwitch() {
        var hidden = document.getElementById('roleHidden');
        if (!hidden) {
            return;
        }
        var roles = document.querySelectorAll('[data-role]');
        var nip = document.getElementById('nip');
        var note = document.getElementById('roleNote');

        function apply(role) {
            var isDosen = role === 'dosen';
            hidden.value = role;
            roles.forEach(function (label) {
                var active = label.getAttribute('data-role') === role;
                label.classList.toggle('active', active);
                label.querySelector('input').checked = active;
            });
            document.querySelectorAll('[data-dosen-only]').forEach(function (el) {
                el.classList.toggle('hidden', !isDosen);
            });
            if (nip) {
                nip.setAttribute('data-rules', isDosen ? 'required|digits:4,30' : 'digits:4,30');
                if (!isDosen) {
                    nip.value = '';
                }
            }
            if (note) {
                note.textContent = isDosen
                    ? 'Akun dosen akan diverifikasi oleh admin sebelum bisa digunakan.'
                    : 'Akun Content Creator akan diverifikasi oleh admin sebelum bisa digunakan.';
            }
        }

        roles.forEach(function (label) {
            label.querySelector('input').addEventListener('change', function () {
                apply(label.getAttribute('data-role'));
            });
        });
        apply(hidden.value || 'dosen');
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

    initConfirmForms();
    document.addEventListener('DOMContentLoaded', function () {
        initRoleSwitch();
        initPasswordToggle();
    });
})();
