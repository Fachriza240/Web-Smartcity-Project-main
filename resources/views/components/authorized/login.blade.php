<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — COE Smart City</title>
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

    <h1 class="auth-heading">Login</h1>
    <p class="auth-sub">Masuk ke panel COE Smart City Universitas Telkom.</p>

    @if(session('success'))
      <div class="auth-alert auth-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="auth-alert auth-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login.masuk') }}" method="POST" id="loginForm" novalidate>
      @csrf

      <div class="auth-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="nama@example.com"
               autocomplete="email"
               class="{{ $errors->has('email') ? 'is-error' : '' }}"
               required>
        <div class="auth-error-text" id="err-email">{{ $errors->first('email') }}</div>
      </div>

      <div class="auth-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••••••"
               autocomplete="current-password"
               minlength="6"
               required>
        <span class="auth-eye" id="togglePwd" title="Tampilkan password">
          <i class="bi bi-eye-slash" id="eyeIcon"></i>
        </span>
        <div class="auth-error-text" id="err-password">{{ $errors->first('password') }}</div>
      </div>

      <div class="auth-meta">
        <label class="auth-meta-left">
          <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="#">Forgot Password?</a>
      </div>

      <button type="submit" class="auth-btn" id="loginSubmitBtn">Login</button>
    </form>

    <p class="auth-bottom">
      Don't have an account? <a href="{{ route('registrasi') }}">Sign Up</a>
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
    var btn  = document.getElementById('togglePwd');
    var inp  = document.getElementById('password');
    var icon = document.getElementById('eyeIcon');
    if (btn) {
      btn.addEventListener('click', function () {
        var show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
      });
    }

    /* ── Validasi client-side ────────────────────────────────
       Aturan di bawah ini SENGAJA disamakan dengan
       app/Http/Requests/LoginRequest.php agar pesan &
       kondisi valid antara front-end dan back-end konsisten.
    ------------------------------------------------------- */
    var form       = document.getElementById('loginForm');
    var submitBtn  = document.getElementById('loginSubmitBtn');
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    var fields = {
      email: {
        el: document.getElementById('email'),
        errEl: document.getElementById('err-email'),
        validate: function (v) {
          if (!v.trim()) return 'Email wajib diisi.';
          if (!emailRegex.test(v)) return 'Format email tidak valid.';
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
      }
    };

    function validateField(key) {
      var f = fields[key];
      var message = f.validate(f.el.value);
      f.el.classList.toggle('is-error', !!message);
      if (f.errEl) f.errEl.textContent = message;
      return !message;
    }

    function validateAll() {
      var valid = true;
      Object.keys(fields).forEach(function (key) {
        if (!validateField(key)) valid = false;
      });
      return valid;
    }

    Object.keys(fields).forEach(function (key) {
      var f = fields[key];
      f.el.addEventListener('input', function () { validateField(key); });
      f.el.addEventListener('blur', function () { validateField(key); });
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