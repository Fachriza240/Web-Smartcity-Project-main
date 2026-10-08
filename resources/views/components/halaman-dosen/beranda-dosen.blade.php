@php
    $user = auth()->user();
    $publicationCount = \App\Models\Publication::forDosen($user)->count();
    $hkiCount = \App\Models\Hki::forDosen($user)->count();
    $unreadCount = $user->unreadNotifications()->count();
    $statusLabel = match ($user->registration_status) {
        \App\Models\User::STATUS_APPROVED => 'Disetujui',
        \App\Models\User::STATUS_REJECTED => 'Ditolak',
        default => 'Menunggu Validasi',
    };
@endphp

<section class="dsn-quick" aria-labelledby="dsn-quick-title">
    <div class="container">
        <div class="dsn-quick__head">
            <h2 id="dsn-quick-title">Selamat datang, {{ $user->fullname }}</h2>
            <p>Kelola profil, publikasi, dan HKI Anda dari satu tempat.</p>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
            <a href="{{ route('dosen.status') }}" class="sc-btn sc-btn--ghost sc-btn--sm">
                <i class="bi bi-patch-check" aria-hidden="true"></i> Status Registrasi: {{ $statusLabel }}
            </a>
            <a href="{{ route('dosen.notifications.index') }}" class="sc-btn sc-btn--ghost sc-btn--sm">
                <i class="bi bi-bell" aria-hidden="true"></i> {{ $unreadCount > 0 ? $unreadCount.' notifikasi belum dibaca' : 'Tidak ada notifikasi baru' }}
            </a>
        </div>

        @if ($publicationCount === 0 && $hkiCount === 0)
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="dsn-note" role="status">
                        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                        <span>Belum ada data publikasi dan HKI atas nama Anda. Tambahkan melalui menu Kelola Publikasi atau Kelola HKI.</span>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <a href="{{ route('dosen.publikasi.index') }}" class="sc-btn sc-btn--primary sc-btn--sm">
                            <i class="bi bi-journal-text" aria-hidden="true"></i> Kelola Publikasi
                        </a>
                        <a href="{{ route('dosen.hki.index') }}" class="sc-btn sc-btn--primary sc-btn--sm">
                            <i class="bi bi-award" aria-hidden="true"></i> Kelola HKI
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="dsn-quick__grid">
            <a href="{{ route('profil.dosen') }}" class="dsn-quick__card">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
                <strong>Profil Saya</strong>
                <span>Perbarui data diri</span>
            </a>
            <a href="{{ route('dosen.publikasi.index') }}" class="dsn-quick__card">
                <i class="bi bi-journal-text" aria-hidden="true"></i>
                <strong>Publikasi Saya</strong>
                <span>{{ $publicationCount > 0 ? $publicationCount.' entri' : 'Belum ada data' }}</span>
            </a>
            <a href="{{ route('dosen.hki.index') }}" class="dsn-quick__card">
                <i class="bi bi-award" aria-hidden="true"></i>
                <strong>HKI Saya</strong>
                <span>{{ $hkiCount > 0 ? $hkiCount.' entri' : 'Belum ada data' }}</span>
            </a>
            <a href="{{ route('dosen.publikasi.create') }}" class="dsn-quick__card dsn-quick__card--primary">
                <i class="bi bi-plus-circle-fill" aria-hidden="true"></i>
                <strong>Tambah Publikasi</strong>
                <span>Unggah publikasi baru</span>
            </a>
            <a href="{{ route('dosen.hki.create') }}" class="dsn-quick__card dsn-quick__card--success">
                <i class="bi bi-plus-circle-fill" aria-hidden="true"></i>
                <strong>Tambah HKI</strong>
                <span>Daftarkan HKI baru</span>
            </a>
        </div>
    </div>
</section>
