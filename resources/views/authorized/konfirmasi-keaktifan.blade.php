<x-authorized.layout title="Konfirmasi Keaktifan Akun">
    <main class="auth-card auth-card--narrow">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <div class="auth-status__icon auth-status__icon--pending"><i class="bi bi-person-fill-exclamation" aria-hidden="true"></i></div>
            <h1 class="auth-status__title">Konfirmasi Keaktifan Akun</h1>
            <p class="auth-status__desc">
                Akun <strong>{{ $user->email }}</strong> terdeteksi tidak aktif karena tidak digunakan untuk login lebih dari {{ $hari }} hari.
                Apakah Anda ingin mengaktifkan kembali akun ini dan melanjutkan proses login?
            </p>
            <span class="auth-status__badge auth-status__badge--pending"><i class="bi bi-hourglass-split" aria-hidden="true"></i> Akun Lama Tidak Aktif</span>
            <div class="auth-status__info">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                Aktivitas login terakhir: {{ $terakhir?->translatedFormat('d F Y, H:i') ?? '-' }} WIB.
                Setelah Anda memilih Ya, proses login dilanjutkan dengan konfirmasi melalui email.
            </div>

            <div class="auth-status__actions">
                <form action="{{ route('login.keaktifan.konfirmasi') }}" method="POST">
                    @csrf
                    <button type="submit" class="auth-btn">
                        <i class="bi bi-check-circle" aria-hidden="true"></i> Ya, Lanjutkan Login
                    </button>
                </form>
                <form action="{{ route('login.keaktifan.batal') }}" method="POST">
                    @csrf
                    <button type="submit" class="auth-btn-outline">
                        <i class="bi bi-x-lg" aria-hidden="true"></i> Tidak, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-authorized.layout>
