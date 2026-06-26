<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Registrasi — COE Smart City</title>
  <link rel="icon" href="{{ asset('img/favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
  <style>
    /* Override: buat card ini single-panel (tanpa gambar kanan) */
    .auth-card { max-width: 480px; min-height: auto; }
    .auth-left  { flex: 1; padding: 48px 44px; justify-content: flex-start; min-height: auto; }

    .status-icon {
      width: 72px; height: 72px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 32px;
      margin: 0 auto 20px;
    }
    .status-icon.pending  { background: #fef9c3; color: #ca8a04; }
    .status-icon.rejected { background: #fef2f2; color: #dc2626; }
    .status-icon.approved { background: #f0fdf4; color: #16a34a; }

    .status-title {
      font-size: 20px; font-weight: 800;
      color: #0f172a; text-align: center; margin-bottom: 8px;
    }
    .status-desc {
      font-size: 14px; color: #64748b;
      text-align: center; line-height: 1.6; margin-bottom: 28px;
    }

    .status-badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 14px; border-radius: 100px;
      font-size: 13px; font-weight: 700; margin-bottom: 24px;
    }
    .badge-pending  { background: #fef9c3; color: #ca8a04; }
    .badge-rejected { background: #fef2f2; color: #dc2626; }
    .badge-approved { background: #f0fdf4; color: #16a34a; }

    .info-box {
      background: #f8fafc; border: 1px solid #e2e8f0;
      border-radius: 12px; padding: 16px;
      font-size: 13px; color: #64748b;
      text-align: center; margin-bottom: 24px;
      line-height: 1.5;
    }
    .info-box i { color: #4c8dc9; display: block; font-size: 20px; margin-bottom: 6px; }

    .auth-btn-outline {
      display: block; width: 100%;
      padding: 12px;
      background: transparent;
      color: #4c8dc9;
      border: 2px solid #4c8dc9;
      border-radius: 11px;
      font-size: 14px; font-weight: 700; font-family: inherit;
      text-align: center; text-decoration: none;
      cursor: pointer; transition: background .2s, color .2s;
      margin-bottom: 10px;
    }
    .auth-btn-outline:hover { background: #4c8dc9; color: #fff; }
  </style>
</head>
<body class="auth-body">

@php
  $user   = auth()->user();
  $status = $user?->registration_status ?? 'pending';
@endphp

<div class="auth-card">
  <div class="auth-left" style="align-items:center; text-align:center;">

    <div class="auth-logo" style="justify-content:center; margin-bottom:32px;">
      <img src="{{ asset('img/logosc.png') }}" alt="COE Smart City">
    </div>

    @if($status === \App\Models\User::STATUS_PENDING)

      <div class="status-icon pending">
        <i class="bi bi-hourglass-split"></i>
      </div>
      <h2 class="status-title">Menunggu Verifikasi</h2>
      <p class="status-desc">
        Akun dosen Anda sudah terdaftar dan sedang dalam proses verifikasi oleh admin.
      </p>
      <span class="status-badge badge-pending">
        <i class="bi bi-clock-fill"></i> Pending
      </span>
      <div class="info-box">
        <i class="bi bi-bell-fill"></i>
        Proses verifikasi biasanya memakan waktu 1×24 jam.<br>
        Silakan cek kembali nanti atau hubungi admin.
      </div>

    @elseif($status === \App\Models\User::STATUS_REJECTED)

      <div class="status-icon rejected">
        <i class="bi bi-x-circle-fill"></i>
      </div>
      <h2 class="status-title">Registrasi Ditolak</h2>
      <p class="status-desc">
        Maaf, registrasi akun dosen Anda tidak disetujui.<br>
        Silakan hubungi admin untuk informasi lebih lanjut.
      </p>
      <span class="status-badge badge-rejected">
        <i class="bi bi-x-circle"></i> Ditolak
      </span>
      <div class="info-box">
        <i class="bi bi-envelope-fill"></i>
        Untuk banding atau pertanyaan, hubungi admin melalui email resmi kampus.
      </div>
      <a href="{{ route('registrasi') }}" class="auth-btn">Daftar Ulang</a>

    @else

      <div class="status-icon approved">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <h2 class="status-title">Akun Disetujui</h2>
      <p class="status-desc">Akun Anda sudah aktif. Silakan masuk ke dashboard.</p>
      <span class="status-badge badge-approved">
        <i class="bi bi-check-circle"></i> Approved
      </span>
      <a href="/beranda-dosen" class="auth-btn">Masuk Dashboard</a>

    @endif

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 16px;">
      @csrf
      <button type="submit" class="auth-btn-outline">
        <i class="bi bi-box-arrow-right me-1"></i> Logout
      </button>
    </form>

    <p class="auth-bottom" style="margin-top:16px;">
      <a href="/">← Kembali ke Beranda</a>
    </p>

  </div>
</div>

</body>
</html>
