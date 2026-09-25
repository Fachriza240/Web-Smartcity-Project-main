@php
    $user = auth()->user();
    $status = $user?->registration_status ?? \App\Models\User::STATUS_PENDING;
    $home = match ($user?->role) {
        'content_creator' => url('/beranda-creator'),
        'admin' => url('/beranda-admin'),
        default => url('/beranda-dosen'),
    };
@endphp

<x-authorized.layout title="Status Registrasi">
    <main class="auth-card auth-card--narrow">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            @if ($status === \App\Models\User::STATUS_PENDING)
                <div class="auth-status__icon auth-status__icon--pending"><i class="bi bi-hourglass-split" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Menunggu Verifikasi</h1>
                <p class="auth-status__desc">
                    {{ $user?->role === 'content_creator'
                        ? 'Akun Content Creator Anda sudah terdaftar dan sedang menunggu persetujuan admin.'
                        : 'Akun dosen Anda sudah terdaftar dan sedang dalam proses verifikasi oleh admin.' }}
                </p>
                <span class="auth-status__badge auth-status__badge--pending"><i class="bi bi-clock-fill" aria-hidden="true"></i> Menunggu</span>
                <div class="auth-status__info">
                    <i class="bi bi-bell-fill" aria-hidden="true"></i>
                    Proses verifikasi biasanya memakan waktu 1 x 24 jam kerja. Silakan cek kembali nanti atau hubungi admin.
                </div>
            @elseif ($status === \App\Models\User::STATUS_REJECTED)
                <div class="auth-status__icon auth-status__icon--rejected"><i class="bi bi-x-circle-fill" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Registrasi Ditolak</h1>
                <p class="auth-status__desc">Registrasi akun Anda belum dapat disetujui. Hubungi admin untuk informasi lebih lanjut.</p>
                <span class="auth-status__badge auth-status__badge--rejected"><i class="bi bi-x-circle" aria-hidden="true"></i> Ditolak</span>
                <div class="auth-status__info">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    Kirim pertanyaan ke {{ config('smartcity.email') }} atau WhatsApp {{ config('smartcity.phone_display') }}.
                </div>
            @else
                <div class="auth-status__icon auth-status__icon--approved"><i class="bi bi-check-circle-fill" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Akun Disetujui</h1>
                <p class="auth-status__desc">Akun Anda sudah aktif. Silakan masuk ke dashboard.</p>
                <span class="auth-status__badge auth-status__badge--approved"><i class="bi bi-check-circle" aria-hidden="true"></i> Disetujui</span>
            @endif

            <div class="auth-status__actions">
                @if ($status === \App\Models\User::STATUS_APPROVED)
                    <a href="{{ $home }}" class="auth-btn">Masuk ke Dashboard</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" data-confirm="Apakah Anda yakin ingin keluar dari akun?">
                    @csrf
                    <button type="submit" class="auth-btn-outline">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                    </button>
                </form>
            </div>

            <p class="auth-bottom">
                <a href="{{ url('/') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda</a>
            </p>
        </div>
    </main>
</x-authorized.layout>
