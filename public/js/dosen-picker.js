(function () {
    'use strict';

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (ch) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
        });
    }

    function init() {
        if (typeof Tagify === 'undefined') {
            return;
        }
        document.querySelectorAll('[data-dosen-picker]').forEach(function (input) {
            var tagify = new Tagify(input, {
                whitelist: window.dosenWhitelist || [],
                enforceWhitelist: false,
                delimiters: ',',
                originalInputValueFormat: function (values) {
                    return values.map(function (item) {
                        return item.value;
                    }).join(', ');
                },
                dropdown: {
                    maxItems: 20,
                    classname: 'dosen-dropdown',
                    enabled: 0,
                    closeOnSelect: false,
                    searchKeys: ['value', 'nip', 'prodi', 'fakultas']
                },
                templates: {
                    dropdownItem: function (item) {
                        var meta = [];
                        if (item.nip) {
                            meta.push('<span><i class="bi bi-credit-card-2-front"></i> ' + escapeHtml(item.nip) + '</span>');
                        }
                        if (item.prodi) {
                            meta.push('<span><i class="bi bi-mortarboard"></i> ' + escapeHtml(item.prodi) + '</span>');
                        }
                        if (item.fakultas) {
                            meta.push('<span><i class="bi bi-building"></i> ' + escapeHtml(item.fakultas) + '</span>');
                        }
                        if (!meta.length) {
                            meta.push('<span><i class="bi bi-pencil-square"></i> Input manual</span>');
                        }
                        return '<div class="tagify__dropdown__item" ' + this.getAttributes(item) + '>' +
                            '<div class="dosen-info-title"><i class="bi bi-person-badge"></i> ' + escapeHtml(item.value) + '</div>' +
                            '<div class="dosen-info-meta">' + meta.join('') + '</div>' +
                            '</div>';
                    }
                }
            });
            tagify.on('change', function () {
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
