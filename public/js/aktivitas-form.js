(function () {
    'use strict';

    var form = document.querySelector('[data-aktivitas-form]');
    var config = window.aktivitasConfig;

    if (!form || !config) {
        return;
    }

    var jenisEl = form.querySelector('[name="jenis"]');
    var subEl = form.querySelector('[name="sub_jenis"]');
    var subLabelEl = form.querySelector('[data-sub-label]');
    var mitraEl = form.querySelector('[name="mitra"]');
    var mitraMark = form.querySelector('[data-mitra-mark]');
    var terpilih = config.terpilih || '';

    if (!jenisEl) {
        return;
    }

    function isiSubJenis(jenis) {
        if (!subEl) {
            return;
        }

        var opsi = (config.sub && config.sub[jenis]) || {};
        var nilai = subEl.value || terpilih;
        terpilih = '';

        subEl.innerHTML = '';

        var kosong = document.createElement('option');
        kosong.value = '';
        kosong.textContent = 'Pilih';
        subEl.appendChild(kosong);

        Object.keys(opsi).forEach(function (kunci) {
            var option = document.createElement('option');
            option.value = kunci;
            option.textContent = opsi[kunci];
            if (kunci === nilai) {
                option.selected = true;
            }
            subEl.appendChild(option);
        });

        if (subLabelEl && config.subLabel && config.subLabel[jenis]) {
            subLabelEl.textContent = config.subLabel[jenis];
        }
    }

    function terapkan() {
        var jenis = jenisEl.value;

        form.querySelectorAll('[data-show-for]').forEach(function (wrap) {
            var daftar = wrap.getAttribute('data-show-for').split(',');
            var tampil = daftar.indexOf(jenis) !== -1;

            wrap.hidden = !tampil;
            wrap.querySelectorAll('input, select, textarea').forEach(function (el) {
                el.disabled = !tampil;
            });
        });

        isiSubJenis(jenis);

        if (mitraEl) {
            var wajib = (config.mitraWajib || []).indexOf(jenis) !== -1;
            mitraEl.setAttribute('data-rules', (wajib ? 'required|' : '') + 'min:2|max:150|safe');
            if (mitraMark) {
                mitraMark.hidden = !wajib;
            }
        }
    }

    jenisEl.addEventListener('change', terapkan);
    terapkan();
})();