(function () {
    'use strict';

    var EMOJI = /\p{Extended_Pictographic}|[\u{1F000}-\u{1FAFF}\u{2600}-\u{27BF}\u{FE0F}\u{200D}]/u;
    var NAME = /^\p{L}[\p{L}\p{M}\s.,'\-]*$/u;
    var HTML = /<\s*\/?\s*[a-z!?][^>]*>|javascript\s*:|\bon[a-z]+\s*=|&#x?[0-9a-f]+;?/i;
    var SQL_STRICT = [
        /['"`]\s*(or|and)\b[\s\S]*?=/i,
        /['"`]\s*(--|#|;)/,
        /--\s*$/
    ];
    var SQL = [
        /\bunion\b[\s\S]*\bselect\b/i,
        /\bselect\s+(\*|count\s*\(|@@)/i,
        /\binsert\s+into\b/i,
        /\bdelete\s+from\b/i,
        /\bupdate\s+\w+\s+set\b/i,
        /\b(drop|truncate|alter)\s+(table|database|schema)\b/i,
        /\b(or|and)\s+\d+\s*=\s*\d+/i,
        /;\s*--/,
        /\/\*[\s\S]*?\*\//
    ];
    var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    function unsafe(value, label, patterns) {
        if (!value) {
            return null;
        }
        if (EMOJI.test(value)) {
            return label + ' tidak boleh berisi emoji atau simbol khusus.';
        }
        if (HTML.test(value)) {
            return label + ' tidak boleh berisi tag HTML atau skrip.';
        }
        for (var i = 0; i < patterns.length; i++) {
            if (patterns[i].test(value)) {
                return label + ' berisi pola karakter yang tidak diizinkan.';
            }
        }
        return null;
    }

    var checks = {
        required: function (value, arg, label) {
            return value.trim() === '' ? label + ' wajib diisi.' : null;
        },
        min: function (value, arg, label) {
            return value.trim().length > 0 && value.trim().length < Number(arg) ? label + ' minimal ' + arg + ' karakter.' : null;
        },
        max: function (value, arg, label) {
            return value.length > Number(arg) ? label + ' maksimal ' + arg + ' karakter.' : null;
        },
        email: function (value, arg, label) {
            return value && !EMAIL.test(value.trim()) ? label + ' harus berupa alamat email yang valid.' : null;
        },
        digits: function (value, arg, label) {
            var parts = String(arg).split(',');
            var v = value.trim();
            if (!v) {
                return null;
            }
            if (!/^[0-9]+$/.test(v)) {
                return label + ' hanya boleh berisi angka.';
            }
            return v.length < Number(parts[0]) || v.length > Number(parts[1])
                ? label + ' harus terdiri dari ' + parts[0] + ' sampai ' + parts[1] + ' digit.'
                : null;
        },
        name: function (value, arg, label) {
            var v = value.trim();
            if (!v) {
                return null;
            }
            if (EMOJI.test(v)) {
                return label + ' tidak boleh berisi emoji atau simbol khusus.';
            }
            if (!NAME.test(v)) {
                return label + ' hanya boleh berisi huruf, spasi, titik, koma, apostrof, dan tanda hubung.';
            }
            if (/--|''|'\s|\s'|'$/.test(v)) {
                return label + ' berisi pola karakter yang tidak diizinkan.';
            }
            if (/\s{2,}/.test(v)) {
                return label + ' tidak boleh berisi spasi berurutan.';
            }
            return null;
        },
        safe: function (value, arg, label) {
            return unsafe(value, label, SQL.concat(SQL_STRICT));
        },
        text: function (value, arg, label) {
            return unsafe(value, label, SQL);
        },
        pattern: function (value, arg, label, form, input) {
            var re = new RegExp(input.getAttribute('data-pattern'));
            return value.trim() && !re.test(value.trim()) ? (input.getAttribute('data-pattern-message') || 'Format ' + label.toLowerCase() + ' tidak valid.') : null;
        },
        range: function (value, arg, label) {
            var parts = String(arg).split(',');
            var num = Number(value);
            if (value === '') {
                return null;
            }
            return !/^-?\d+$/.test(value) || num < Number(parts[0]) || num > Number(parts[1])
                ? label + ' harus di antara ' + parts[0] + ' sampai ' + parts[1] + '.'
                : null;
        },
        same: function (value, arg, label, form) {
            var other = form.querySelector('[name="' + arg + '"]');
            return other && value !== other.value ? label + ' tidak cocok dengan password.' : null;
        }
    };

    function errorBox(form, input) {
        return form.querySelector('[data-error-for="' + input.name + '"]');
    }

    function setError(form, input, message) {
        var box = errorBox(form, input);
        var wrap = input.closest('[data-field]');
        if (wrap) {
            wrap.classList.toggle('has-error', !!message);
        }
        input.classList.toggle('is-error', !!message);
        input.classList.toggle('is-invalid', !!message);
        input.setAttribute('aria-invalid', message ? 'true' : 'false');
        if (box) {
            box.textContent = message || '';
        }
    }

    function isActive(input) {
        return !input.disabled && !input.closest('.hidden');
    }

    function validateInput(form, input) {
        if (!isActive(input)) {
            setError(form, input, null);
            return true;
        }
        var label = input.getAttribute('data-label') || input.name;
        var rules = (input.getAttribute('data-rules') || '').split('|').filter(Boolean);
        for (var i = 0; i < rules.length; i++) {
            var parts = rules[i].split(':');
            var fn = checks[parts[0]];
            var message = fn ? fn(input.value, parts.slice(1).join(':'), label, form, input) : null;
            if (message) {
                setError(form, input, message);
                return false;
            }
        }
        setError(form, input, null);
        return true;
    }

    function initValidation() {
        document.querySelectorAll('form[data-validate]').forEach(function (form) {
            var inputs = form.querySelectorAll('[data-rules]');
            inputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    if (input.classList.contains('is-error')) {
                        validateInput(form, input);
                    }
                });
                input.addEventListener('blur', function () {
                    if (input.value !== '') {
                        validateInput(form, input);
                    }
                });
            });
            form.addEventListener('submit', function (event) {
                var firstInvalid = null;
                inputs.forEach(function (input) {
                    if (!validateInput(form, input) && !firstInvalid) {
                        firstInvalid = input;
                    }
                });
                if (firstInvalid) {
                    event.preventDefault();
                    firstInvalid.focus();
                    return;
                }
                var button = form.querySelector('[type="submit"]');
                if (button) {
                    button.disabled = true;
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initValidation);
    } else {
        initValidation();
    }
})();
