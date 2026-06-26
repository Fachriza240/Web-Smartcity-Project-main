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

    <form action="{{ route('registrasi') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      <input type="hidden" name="role" id="roleHidden" value="{{ old('role','dosen') }}">

      {{-- Nama --}}
      <div class="auth-field">
        <label for="fullname">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname"
               value="{{ old('fullname') }}"
               placeholder="Nama lengkap Anda"
               autocomplete="name"
               class="{{ $errors->has('fullname') ? 'is-error' : '' }}"
               required>
      </div>

      {{-- Email --}}
      <div class="auth-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="nama@example.com"
               autocomplete="email"
               class="{{ $errors->has('email') ? 'is-error' : '' }}"
               required>
      </div>

      {{-- NIP — hanya dosen --}}
      <div class="auth-nip-wrap {{ old('role','dosen') === 'content_creator' ? 'hidden' : '' }}" id="nipWrap">
        <div class="auth-field" style="margin-bottom:0;">
          <label for="nip">NIP</label>
          <input type="text" id="nip" name="nip"
                 value="{{ old('nip') }}"
                 placeholder="Nomor Induk Pegawai"
                 inputmode="numeric"
                 class="{{ $errors->has('nip') ? 'is-error' : '' }}">
        </div>
      </div>

      {{-- Catatan dosen --}}
      <div class="auth-note-wrap {{ old('role','dosen') === 'content_creator' ? 'hidden' : '' }}" id="dosenNoteWrap">
        <div class="auth-note">
          <i class="bi bi-info-circle-fill"></i>
          Akun dosen akan diverifikasi oleh admin sebelum bisa digunakan.
        </div>
      </div>

      {{-- Password 2 kolom --}}
      <div class="auth-row">
        <div class="auth-field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password"
                 placeholder="Min. 6 karakter"
                 autocomplete="new-password"
                 class="{{ $errors->has('password') ? 'is-error' : '' }}"
                 required>
          <span class="auth-eye" id="togglePwd1"><i class="bi bi-eye-slash" id="eyeIcon1"></i></span>
        </div>
        <div class="auth-field">
          <label for="password_confirmation">Konfirmasi</label>
          <input type="password" id="password_confirmation" name="password_confirmation"
                 placeholder="Ulangi password"
                 autocomplete="new-password"
                 required>
          <span class="auth-eye" id="togglePwd2"><i class="bi bi-eye-slash" id="eyeIcon2"></i></span>
        </div>
      </div>

      <button type="submit" class="auth-btn">Daftar Sekarang</button>
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
    dosenNote.classList.toggle('hidden', !isDosen);

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

})();
</script>

</body>
</html>
