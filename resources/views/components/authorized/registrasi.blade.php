<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun — COE Smart City</title>
  <link rel="icon" href="{{ asset('img/favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-body">

<div class="auth-card">

  {{-- ── Kiri: Form ──────────────────────────────────────── --}}
  <div class="auth-left">

    <a href="/" class="auth-back">
      <i class="bi bi-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="auth-logo">
      <img src="{{ asset('img/logosc.png') }}" alt="COE Smart City">
    </div>

    <h1 class="auth-heading">Buat Akun</h1>
    <p class="auth-sub">Daftar untuk bergabung dengan COE Smart City Universitas Telkom.</p>

    @if($errors->any())
      <div class="auth-alert auth-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
      </div>
    @endif

    {{-- Role Selector --}}
    <div class="auth-roles">
      <label class="auth-role {{ old('role','dosen') === 'dosen' ? 'active' : '' }}" id="lblDosen">
        <input type="radio" name="role_ui" value="dosen">
        <div class="auth-role-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <div>
          <div class="auth-role-name">Dosen</div>
          <div class="auth-role-sub">Tenaga pengajar</div>
        </div>
      </label>
      <label class="auth-role {{ old('role') === 'content_creator' ? 'active' : '' }}" id="lblCreator">
        <input type="radio" name="role_ui" value="content_creator">
        <div class="auth-role-icon"><i class="bi bi-pen-fill"></i></div>
        <div>
          <div class="auth-role-name">Content Creator</div>
          <div class="auth-role-sub">Kelola konten web</div>
        </div>
      </label>
    </div>

    <form action="{{ route('registrasi') }}" method="POST" enctype="multipart/form-data" id="registerForm" novalidate>
      @csrf
      <input type="hidden" name="role" id="roleHidden" value="{{ old('role','dosen') }}">

      {{-- Nama --}}
      <div class="auth-field">
        <label for="fullname">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname"
               value="{{ old('fullname') }}"
               placeholder="Nama lengkap Anda"
               autocomplete="name"
               maxlength="255"
               class="{{ $errors->has('fullname') ? 'is-error' : '' }}"
               required>
        <div class="auth-error-text" id="err-fullname">{{ $errors->first('fullname') }}</div>
      </div>

      {{-- Email --}}
      <div class="auth-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="nama@example.com"
               autocomplete="email"
               maxlength="255"
               class="{{ $errors->has('email') ? 'is-error' : '' }}"
               required>
        <div class="auth-error-text" id="err-email">{{ $errors->first('email') }}</div>
      </div>

      {{-- NIP — hanya dosen --}}
      <div class="auth-nip-wrap {{ old('role','dosen') === 'content_creator' ? 'hidden' : '' }}" id="nipWrap">
        <div class="auth-field" style="margin-bottom:0;">
          <label for="nip">NIP</label>
          <input type="text" id="nip" name="nip"
                 value="{{ old('nip') }}"
                 placeholder="Nomor Induk Pegawai (5-20 digit angka)"
                 inputmode="numeric"
                 pattern="[0-9]*"
                 maxlength="20"
                 class="{{ $errors->has('nip') ? 'is-error' : '' }}">
          <div class="auth-error-text" id="err-nip">{{ $errors->first('nip') }}</div>
        </div>
      </div>

      {{-- Prodi & Fakultas — hanya dosen --}}
      <div class="auth-nip-wrap {{ old('role','dosen') === 'content_creator' ? 'hidden' : '' }}" id="prodiFakultasWrap">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:0;">
          <div class="auth-field" style="margin-bottom:0;">
            <label for="prodi">Program Studi</label>
            <input type="text" id="prodi" name="prodi"
                   value="{{ old('prodi') }}"
                   placeholder="Contoh: Teknik Informatika"
                   maxlength="255"
                   class="{{ $errors->has('prodi') ? 'is-error' : '' }}">
            <div class="auth-error-text" id="err-prodi">{{ $errors->first('prodi') }}</div>
          </div>
          <div class="auth-field" style="margin-bottom:0;">
            <label for="fakultas">Fakultas</label>
            <input type="text" id="fakultas" name="fakultas"
                   value="{{ old('fakultas') }}"
                   placeholder="Contoh: Fakultas Informatika"
                   maxlength="255"
                   class="{{ $errors->has('fakultas') ? 'is-error' : '' }}">
            <div class="auth-error-text" id="err-fakultas">{{ $errors->first('fakultas') }}</div>
          </div>
        </div>
      </div>

      {{-- Catatan pending — tampil untuk semua role --}}
      <div class="auth-note-wrap" id="dosenNoteWrap">
        <div class="auth-note">
          <i class="bi bi-info-circle-fill"></i>
          <span id="dosenNoteText">Akun Anda akan diverifikasi oleh admin sebelum bisa digunakan.</span>
        </div>
      </div>

      {{-- Password 2 kolom --}}
      <div class="auth-row">
        <div class="auth-field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password"
                 placeholder="Min. 6 karakter"
                 autocomplete="new-password"
                 minlength="6"
                 class="{{ $errors->has('password') ? 'is-error' : '' }}"
                 required>
          <span class="auth-eye" id="togglePwd1"><i class="bi bi-eye-slash" id="eyeIcon1"></i></span>
          <div class="auth-error-text" id="err-password">{{ $errors->first('password') }}</div>
        </div>
        <div class="auth-field">
          <label for="password_confirmation">Konfirmasi</label>
          <input type="password" id="password_confirmation" name="password_confirmation"
                 placeholder="Ulangi password"
                 autocomplete="new-password"
                 minlength="6"
                 required>
          <span class="auth-eye" id="togglePwd2"><i class="bi bi-eye-slash" id="eyeIcon2"></i></span>
          <div class="auth-error-text" id="err-password_confirmation"></div>
        </div>
      </div>

      <button type="submit" class="auth-btn" id="registerSubmitBtn">Daftar Sekarang</button>
    </form>

    <p class="auth-bottom">
      Sudah punya akun? <a href="{{ route('login') }}">Login</a>
    </p>

  </div>

  {{-- ── Kanan: Gambar ───────────────────────────────────── --}}
  <div class="auth-right">
    <img src="{{ asset('img/bglogin.jpg') }}" alt="Smart City" loading="eager">
    <div class="auth-caption">
      Inovasi teknologi untuk<br>kota-kota cerdas Indonesia.
    </div>
    <div class="auth-badges">
      <span class="auth-badge"><i class="bi bi-mortarboard-fill"></i> Universitas Telkom</span>
      <span class="auth-badge"><i class="bi bi-building"></i> COE Smart City</span>
    </div>
  </div>

</div>

<script>
(function () {

  /* ── Role toggle ────────────────────────────────────────── */
  var lblDosen    = document.getElementById('lblDosen');
  var lblCreator  = document.getElementById('lblCreator');
  var roleHidden  = document.getElementById('roleHidden');
  var nipWrap     = document.getElementById('nipWrap');
  var nipInput    = document.getElementById('nip');
  var dosenNote   = document.getElementById('dosenNoteWrap');

  function setRole(role) {
    roleHidden.value = role;
    var isDosen = role === 'dosen';

    lblDosen.classList.toggle('active', isDosen);
    lblCreator.classList.toggle('active', !isDosen);

    nipWrap.classList.toggle('hidden', !isDosen);

    // Toggle prodi & fakultas
    var prodiFakultasWrap = document.getElementById('prodiFakultasWrap');
    if (prodiFakultasWrap) {
      prodiFakultasWrap.classList.toggle('hidden', !isDosen);
    }

    // Note selalu tampil untuk semua role — teks berbeda
    var noteText = document.getElementById('dosenNoteText');
    if (noteText) {
      noteText.textContent = isDosen
        ? 'Akun dosen akan diverifikasi oleh admin sebelum bisa digunakan.'
        : 'Akun Content Creator akan diverifikasi oleh admin sebelum bisa digunakan.';
    }

    nipInput.required = isDosen;
    if (!isDosen) nipInput.value = '';
  }

  lblDosen.addEventListener('click',   function () { setRole('dosen'); });
  lblCreator.addEventListener('click', function () { setRole('content_creator'); });

  // Restore from old() on validation fail
  setRole(roleHidden.value || 'dosen');

  /* ── Password toggle ────────────────────────────────────── */
  function eyeToggle(btnId, inpId, iconId) {
    var btn  = document.getElementById(btnId);
    var inp  = document.getElementById(inpId);
    var icon = document.getElementById(iconId);
    if (!btn) return;
    btn.addEventListener('click', function () {
      var show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      icon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
  }

  eyeToggle('togglePwd1', 'password',              'eyeIcon1');
  eyeToggle('togglePwd2', 'password_confirmation', 'eyeIcon2');

  var form        = document.getElementById('registerForm');
  var submitBtn   = document.getElementById('registerSubmitBtn');
  var emailRegex  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  var fields = {
    fullname: {
      el: document.getElementById('fullname'),
      errEl: document.getElementById('err-fullname'),
      validate: function (v) {
        if (!v.trim()) return 'Nama lengkap wajib diisi.';
        if (v.length > 255) return 'Nama lengkap maksimal 255 karakter.';
        return '';
      }
    },
    email: {
      el: document.getElementById('email'),
      errEl: document.getElementById('err-email'),
      validate: function (v) {
        if (!v.trim()) return 'Email wajib diisi.';
        if (!emailRegex.test(v)) return 'Format email tidak valid.';
        if (v.length > 255) return 'Email maksimal 255 karakter.';
        return '';
      }
    },
    nip: {
      el: document.getElementById('nip'),
      errEl: document.getElementById('err-nip'),
      validate: function (v) {
        var isDosen = roleHidden.value === 'dosen';
        if (!v.trim()) {
          return isDosen ? 'NIP wajib diisi untuk akun dosen.' : '';
        }
        if (!/^[0-9]+$/.test(v)) return 'NIP hanya boleh berisi angka.';
        if (v.length < 5 || v.length > 20) return 'NIP harus terdiri dari 5–20 digit.';
        return '';
      }
    },
    prodi: {
      el: document.getElementById('prodi'),
      errEl: document.getElementById('err-prodi'),
      validate: function (v) {
        if (v.length > 255) return 'Program studi maksimal 255 karakter.';
        return '';
      }
    },
    fakultas: {
      el: document.getElementById('fakultas'),
      errEl: document.getElementById('err-fakultas'),
      validate: function (v) {
        if (v.length > 255) return 'Fakultas maksimal 255 karakter.';
        return '';
      }
    },
    password: {
      el: document.getElementById('password'),
      errEl: document.getElementById('err-password'),
      validate: function (v) {
        if (!v) return 'Password wajib diisi.';
        if (v.length < 6) return 'Password minimal 6 karakter.';
        return '';
      }
    },
    password_confirmation: {
      el: document.getElementById('password_confirmation'),
      errEl: document.getElementById('err-password_confirmation'),
      validate: function (v) {
        var pwd = document.getElementById('password').value;
        if (!v) return 'Konfirmasi password wajib diisi.';
        if (v !== pwd) return 'Konfirmasi password tidak cocok.';
        return '';
      }
    }
  };

  function validateField(key) {
    var f = fields[key];
    if (!f || !f.el) return true;
    var message = f.validate(f.el.value);
    f.el.classList.toggle('is-error', !!message);
    if (f.errEl) f.errEl.textContent = message;
    return !message;
  }

  function validateAll() {
    var valid = true;
    Object.keys(fields).forEach(function (key) {
      if ((key === 'prodi' || key === 'fakultas') && roleHidden.value !== 'dosen') {
        fields[key].el.classList.remove('is-error');
        if (fields[key].errEl) fields[key].errEl.textContent = '';
        return;
      }
      if (!validateField(key)) valid = false;
    });
    return valid;
  }

  Object.keys(fields).forEach(function (key) {
    var f = fields[key];
    if (!f.el) return;
    f.el.addEventListener('input', function () { validateField(key); });
    f.el.addEventListener('blur', function () { validateField(key); });
  });

  document.getElementById('password').addEventListener('input', function () {
    validateField('password_confirmation');
  });

  form.addEventListener('submit', function (e) {
    if (!validateAll()) {
      e.preventDefault();
      var firstInvalid = form.querySelector('.is-error');
      if (firstInvalid) firstInvalid.focus();
    } else {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Memproses...';
    }
  });

})();
</script>

</body>
</html>