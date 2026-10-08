@php
    $bukanSaya = $aksi === 'bukan-saya';
@endphp

<x-authorized.layout :title="$bukanSaya ? 'Tolak Login' : 'Konfirmasi Login'">
    <main class="auth-card auth-card--narrow">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            @if ($bukanSaya)
                <div class="auth-status__icon auth-status__icon--rejected"><i class="bi bi-shield-exclamation" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Bukan Anda yang Login?</h1>
                <p class="auth-status__desc">Tolak permintaan login berikut agar tidak dapat digunakan untuk masuk ke akun Anda.</p>
            @else
                <div class="auth-status__icon auth-status__icon--approved"><i class="bi bi-person-check-fill" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Konfirmasi Login</h1>
                <p class="auth-status__desc">Pastikan permintaan login berikut memang dilakukan oleh Anda.</p>
            @endif

            <div class="auth-status__info">
                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                <strong>Akun:</strong> {{ $confirmation->user->email }}<br>
                <strong>Waktu:</strong> {{ $confirmation->created_at->translatedFormat('d F Y, H:i') }} WIB<br>
                <strong>Perangkat:</strong> {{ $confirmation->deviceLabel() }}<br>
                <strong>Alamat IP:</strong> {{ $confirmation->ip_address ?: '-' }}
            </div>

            <div class="auth-status__actions">
                @if ($bukanSaya)
                    <form action="{{ route('login.konfirmasi.tolak', $token) }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-btn">
                            <i class="bi bi-x-circle" aria-hidden="true"></i> Bukan Saya, Tolak Login
                        </button>
                    </form>
                    <a href="{{ route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'ini-saya']) }}" class="auth-btn-outline">
                        <i class="bi bi-person-check" aria-hidden="true"></i> Ternyata Ini Saya
                    </a>
                @else
                    <form action="{{ route('login.konfirmasi.setujui', $token) }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-btn">
                            <i class="bi bi-check-circle" aria-hidden="true"></i> Ya, Ini Saya
                        </button>
                    </form>
                    <a href="{{ route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'bukan-saya']) }}" class="auth-btn-outline">
                        <i class="bi bi-person-x" aria-hidden="true"></i> Bukan Saya
                    </a>
                @endif
            </div>
        </div>
    </main>
</x-authorized.layout>
