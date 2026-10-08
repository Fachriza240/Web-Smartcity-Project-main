@php
    $daftar = [
        'disetujui' => [
            'ikon' => 'approved', 'simbol' => 'bi-check-circle-fill',
            'judul' => 'Login Berhasil Dikonfirmasi',
            'pesan' => 'Terima kasih, login Anda sudah dikonfirmasi. Silakan kembali ke perangkat atau browser tempat Anda login. Halaman tersebut akan otomatis masuk ke dashboard.',
            'gantiPassword' => false,
        ],
        'ditolak' => [
            'ikon' => 'rejected', 'simbol' => 'bi-shield-x',
            'judul' => 'Login Berhasil Dibatalkan',
            'pesan' => 'Proses login tersebut telah dibatalkan dan akun Anda dikunci sementara selama '.max(1, (int) config('login.lock_minutes', 30)).' menit. Laporan aktivitas mencurigakan berisi waktu, email, dan perangkat telah dikirim ke Admin. Segera ganti password akun Anda.',
            'gantiPassword' => true,
        ],
        'sudah-disetujui' => [
            'ikon' => 'approved', 'simbol' => 'bi-check-circle-fill',
            'judul' => 'Login Sudah Dikonfirmasi',
            'pesan' => 'Permintaan login ini sudah dikonfirmasi sebelumnya. Jika Anda tidak merasa melakukannya, segera ganti password akun Anda.',
            'gantiPassword' => true,
        ],
        'sudah-ditolak' => [
            'ikon' => 'rejected', 'simbol' => 'bi-shield-x',
            'judul' => 'Login Sudah Ditolak',
            'pesan' => 'Permintaan login ini sudah ditolak sebelumnya sehingga tidak dapat digunakan untuk masuk ke akun Anda.',
            'gantiPassword' => true,
        ],
        'kedaluwarsa' => [
            'ikon' => 'pending', 'simbol' => 'bi-hourglass-bottom',
            'judul' => 'Tautan Kedaluwarsa',
            'pesan' => 'Tautan konfirmasi login sudah tidak berlaku. Silakan login kembali untuk menerima tautan konfirmasi yang baru.',
            'gantiPassword' => false,
        ],
        'dibatalkan' => [
            'ikon' => 'pending', 'simbol' => 'bi-slash-circle',
            'judul' => 'Permintaan Login Tidak Berlaku',
            'pesan' => 'Permintaan login ini sudah dibatalkan atau digantikan oleh permintaan login yang lebih baru.',
            'gantiPassword' => false,
        ],
        'nonaktif' => [
            'ikon' => 'rejected', 'simbol' => 'bi-person-lock',
            'judul' => 'Akun Dinonaktifkan',
            'pesan' => \App\Models\User::INACTIVE_MESSAGE,
            'gantiPassword' => false,
        ],
        'terkunci' => [
            'ikon' => 'rejected', 'simbol' => 'bi-lock-fill',
            'judul' => 'Akun Dikunci Sementara',
            'pesan' => $pesan ?? 'Akun Anda dikunci sementara karena terdeteksi aktivitas login mencurigakan.',
            'gantiPassword' => true,
        ],
        'tidak-valid' => [
            'ikon' => 'rejected', 'simbol' => 'bi-link-45deg',
            'judul' => 'Tautan Tidak Valid',
            'pesan' => 'Tautan konfirmasi login tidak valid. Silakan login kembali untuk menerima tautan konfirmasi yang baru.',
            'gantiPassword' => false,
        ],
    ];
    $hasil = $daftar[$jenis] ?? $daftar['tidak-valid'];
@endphp

<x-authorized.layout :title="$hasil['judul']">
    <main class="auth-card auth-card--narrow">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <div class="auth-status__icon auth-status__icon--{{ $hasil['ikon'] }}"><i class="bi {{ $hasil['simbol'] }}" aria-hidden="true"></i></div>
            <h1 class="auth-status__title">{{ $hasil['judul'] }}</h1>
            <p class="auth-status__desc">{{ $hasil['pesan'] }}</p>

            <div class="auth-status__actions">
                @if ($hasil['gantiPassword'])
                    <a href="{{ route('password.request') }}" class="auth-btn">
                        <i class="bi bi-key-fill" aria-hidden="true"></i> Ganti Password
                    </a>
                    <a href="{{ route('login') }}" class="auth-btn-outline">
                        <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Ke Halaman Login
                    </a>
                @else
                    <a href="{{ route('login') }}" class="auth-btn">
                        <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Ke Halaman Login
                    </a>
                @endif
            </div>

            <p class="auth-bottom">
                <a href="{{ url('/') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda</a>
            </p>
        </div>
    </main>
</x-authorized.layout>
