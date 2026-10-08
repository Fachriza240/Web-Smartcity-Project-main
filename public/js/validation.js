(function () {
    'use strict';

    var EMOJI = /[\u{1F000}-\u{1FAFF}\u{2600}-\u{27BF}\u{2B00}-\u{2BFF}\u{FE00}-\u{FE0F}\u{200D}\u{20E3}\u{E0020}-\u{E007F}\u{3030}\u{303D}\u{3297}\u{3299}]/u;
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
        /[;'"`)]\s*select\b/i,
        /\binsert\s+into\b/i,
        /\bdelete\s+from\b/i,
        /\bupdate\s+\w+\s+set\b/i,
        /\b(drop|truncate|alter)\s+(table|database|schema)\b/i,
        /\b(exec|execute)\s*(\(|\s+xp_)/i,
        /\b(sleep|benchmark|pg_sleep)\s*\(/i,
        /\b(or|and)\s+\d+\s*=\s*\d+/i,
        /;\s*--/,
        /\/\*[\s\S]*?\*\//
    ];
    var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    var URL_PATTERN = /^https?:\/\/[^\s/$.?#][^\s]*$/i;

    function length(value) {
        return Array.from(String(value).replace(/\r\n|\r|\n/g, '\r\n')).length;
    }

    function lowerFirst(label) {
        return label.charAt(0).toLowerCase() + label.slice(1);
    }

    function sizeLabel(kilobytes) {
        var value = Number(kilobytes);
        return value % 1024 === 0 ? (value / 1024) + ' MB' : value + ' KB';
    }

    function textValue(value, input) {
        return input && /password/.test(input.name || '') ? value : value.trim();
    }

    function filesOf(input) {
        return input && input.files ? Array.prototype.slice.call(input.files) : [];
    }

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
        required: function (value, arg, label, form, input) {
            if (input.type === 'file') {
                return filesOf(input).length ? null : label + ' wajib diisi.';
            }
            return value.trim() === '' ? label + ' wajib diisi.' : null;
        },
        min: function (value, arg, label, form, input) {
            var size = length(textValue(value, input));
            return size > 0 && size < Number(arg) ? label + ' minimal ' + arg + ' karakter.' : null;
        },
        max: function (value, arg, label, form, input) {
            if (length(textValue(value, input)) <= Number(arg)) {
                return null;
            }
            return /password/.test(input.name || '')
                ? label + ' melebihi batas maksimum ' + arg + ' karakter.'
                : label + ' maksimal ' + arg + ' karakter.';
        },
        email: function (value, arg, label) {
            var v = value.trim();
            return v && !EMAIL.test(v) ? label + ' harus berupa alamat email yang valid.' : null;
        },
        url: function (value, arg, label) {
            var v = value.trim();
            return v && !URL_PATTERN.test(v) ? label + ' harus berupa URL yang valid, contoh: https://domain.com.' : null;
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
            return unsafe(value.trim(), label, SQL.concat(SQL_STRICT));
        },
        text: function (value, arg, label) {
            return unsafe(value.trim(), label, SQL);
        },
        pattern: function (value, arg, label, form, input) {
            var re = new RegExp(input.getAttribute('data-pattern'));
            var v = value.trim();
            return v && !re.test(v) ? (input.getAttribute('data-pattern-message') || 'Format ' + lowerFirst(label) + ' tidak valid.') : null;
        },
        range: function (value, arg, label) {
            var parts = String(arg).split(',');
            var v = value.trim();
            if (v === '') {
                return null;
            }
            var num = Number(v);
            return !/^-?\d+$/.test(v) || num < Number(parts[0]) || num > Number(parts[1])
                ? label + ' harus di antara ' + parts[0] + ' sampai ' + parts[1] + '.'
                : null;
        },
        same: function (value, arg, label, form) {
            var other = form.querySelector('[name="' + arg + '"]');
            return other && value !== other.value ? label + ' tidak cocok dengan password.' : null;
        },
        file: function (value, arg, label, form, input) {
            var allowed = String(arg).toLowerCase().split(',');
            var files = filesOf(input);
            for (var i = 0; i < files.length; i++) {
                var name = files[i].name || '';
                var dot = name.lastIndexOf('.');
                var ext = dot > -1 ? name.slice(dot + 1).toLowerCase() : '';
                if (allowed.indexOf(ext) === -1) {
                    return 'Format ' + lowerFirst(label) + ' tidak didukung. ' + label + ' harus berupa file bertipe: ' + allowed.join(', ') + '.';
                }
            }
            return null;
        },
        filesize: function (value, arg, label, form, input) {
            var limit = Number(arg) * 1024;
            var files = filesOf(input);
            for (var i = 0; i < files.length; i++) {
                if (files[i].size > limit) {
                    return 'Ukuran ' + lowerFirst(label) + ' melebihi batas maksimum ' + sizeLabel(arg) + ' (maksimal ' + arg + ' kilobyte).';
                }
            }
            return null;
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
        if (!box && message && wrap) {
            box = document.createElement('div');
            box.className = 'invalid-feedback d-block';
            box.setAttribute('data-error-for', input.name);
            wrap.appendChild(box);
        }
        if (!box) {
            return;
        }
        if (message) {
            box.textContent = message;
        } else if (box.classList.contains('invalid-feedback')) {
            box.remove();
        } else {
            box.textContent = '';
        }
    }

    function isActive(input) {
        return !input.disabled && !input.closest('.hidden, [hidden]');
    }

    function validateInput(form, input) {
        if (!isActive(input)) {
            setError(form, input, null);
            return true;
        }
        var label = input.getAttribute('data-label') || input.name;
        var rules = (input.getAttribute('data-rules') || '').split('|').filter(Boolean);
        var value = input.type === 'file' ? '' : String(input.value || '');
        for (var i = 0; i < rules.length; i++) {
            var parts = rules[i].split(':');
            var fn = checks[parts[0]];
            var message = fn ? fn(value, parts.slice(1).join(':'), label, form, input) : null;
            if (message) {
                setError(form, input, message);
                return false;
            }
        }
        setError(form, input, null);
        return true;
    }

    function focusField(input) {
        var target = input.closest('[data-field]') || input;
        if (target.scrollIntoView) {
            target.scrollIntoView({ block: 'center' });
        }
        try {
            input.focus({ preventScroll: true });
        } catch (e) {
        }
    }

    function confirmMessages(form, submitter) {
        var source = null;
        if (submitter && submitter.hasAttribute('data-confirm-submit')) {
            source = submitter;
        } else if (form.hasAttribute('data-confirm-submit')) {
            source = form;
        } else {
            source = form.querySelector('[data-confirm-submit]');
        }
        if (!source) {
            return [];
        }
        return [source.getAttribute('data-confirm-submit'), source.getAttribute('data-confirm-second')].filter(Boolean);
    }

    function lockForm(form, locked) {
        form.dataset.submitting = locked ? 'true' : 'false';
        form.querySelectorAll('[type="submit"]').forEach(function (button) {
            button.disabled = locked;
        });
    }

    function enhance(form) {
        if (form.dataset.enhanced === 'true') {
            return;
        }
        var inputs = form.querySelectorAll('[data-rules]');
        var validated = form.hasAttribute('data-validate') || inputs.length > 0;
        var confirmable = form.hasAttribute('data-confirm-submit') || form.querySelector('[data-confirm-submit]') !== null;
        if (!validated && !confirmable) {
            return;
        }
        form.dataset.enhanced = 'true';
        if (validated) {
            form.noValidate = true;
        }

        inputs.forEach(function (input) {
            input.addEventListener('input', function () {
                if (input.classList.contains('is-error') || input.classList.contains('is-invalid')) {
                    validateInput(form, input);
                }
            });
            input.addEventListener('change', function () {
                if (input.type === 'file' || input.tagName === 'SELECT') {
                    validateInput(form, input);
                }
            });
        });

        form.addEventListener('submit', function (event) {
            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }
            var firstInvalid = null;
            form.querySelectorAll('[data-rules]').forEach(function (input) {
                if (!validateInput(form, input) && !firstInvalid) {
                    firstInvalid = input;
                }
            });
            if (firstInvalid) {
                event.preventDefault();
                focusField(firstInvalid);
                return;
            }
            var messages = confirmMessages(form, event.submitter);
            for (var i = 0; i < messages.length; i++) {
                if (!window.confirm(messages[i])) {
                    event.preventDefault();
                    return;
                }
            }
            form.dataset.submitting = 'true';
            window.setTimeout(function () {
                lockForm(form, true);
            }, 0);
        });
    }

    function initRetry() {
        document.addEventListener('click', function (event) {
            var button = event.target.closest ? event.target.closest('[data-retry]') : null;
            if (!button) {
                return;
            }
            var forms = Array.prototype.filter.call(document.querySelectorAll('form[data-enhanced="true"]'), function (form) {
                return !form.closest('.modal');
            });
            var submit = forms.length ? forms[0].querySelector('[type="submit"]') : null;
            if (submit) {
                submit.click();
            } else {
                window.location.reload();
            }
        });
    }

    function initModals() {
        if (typeof bootstrap === 'undefined') {
            return;
        }
        document.querySelectorAll('.modal[data-show-on-load]').forEach(function (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).show();
        });
    }

    function init() {
        document.querySelectorAll('form').forEach(enhance);
        initRetry();
        initModals();
    }

    window.addEventListener('pageshow', function (event) {
        if (!event.persisted) {
            return;
        }
        document.querySelectorAll('form[data-submitting="true"]').forEach(function (form) {
            lockForm(form, false);
        });
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
