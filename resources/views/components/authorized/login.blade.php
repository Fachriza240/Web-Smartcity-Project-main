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

    <form action="{{ route('login.masuk') }}" method="POST" novalidate>
      @csrf

      <div class="auth-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="nama@example.com"
               autocomplete="email"
               class="{{ $errors->has('email') ? 'is-error' : '' }}"
               required>
      </div>

      <div class="auth-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••••••"
               autocomplete="current-password"
               required>
        <span class="auth-eye" id="togglePwd" title="Tampilkan password">
          <i class="bi bi-eye-slash" id="eyeIcon"></i>
        </span>
      </div>

      <div class="auth-meta">
        <label class="auth-meta-left">
          <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="#">Forgot Password?</a>
      </div>

      <button type="submit" class="auth-btn">Login</button>
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
  })();
</script>

</body>
</html>
