@php
    $user = auth()->user();
    $publicationCount = \App\Models\Publication::forDosen($user)->count();
    $hkiCount = \App\Models\Hki::forDosen($user)->count();
@endphp

<section class="dsn-quick" aria-labelledby="dsn-quick-title">
    <div class="container">
        <div class="dsn-quick__head">
            <h2 id="dsn-quick-title">Selamat datang, {{ $user->fullname }}</h2>
            <p>Kelola profil, publikasi, dan HKI Anda dari satu tempat.</p>
        </div>

        <div class="dsn-quick__grid">
            <a href="{{ route('profil.dosen') }}" class="dsn-quick__card">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
                <strong>Profil Saya</strong>
                <span>Perbarui data diri</span>
            </a>
            <a href="{{ route('dosen.publikasi.index') }}" class="dsn-quick__card">
                <i class="bi bi-journal-text" aria-hidden="true"></i>
                <strong>Publikasi Saya</strong>
                <span>{{ $publicationCount }} entri</span>
            </a>
            <a href="{{ route('dosen.hki.index') }}" class="dsn-quick__card">
                <i class="bi bi-award" aria-hidden="true"></i>
                <strong>HKI Saya</strong>
                <span>{{ $hkiCount }} entri</span>
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
