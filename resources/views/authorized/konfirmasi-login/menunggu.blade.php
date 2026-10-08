<x-authorized.layout title="Konfirmasi Login">
    <main class="auth-card auth-card--narrow">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            @if (session('success'))
                <div class="auth-alert auth-alert-success" role="status">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @error('konfirmasi')
                <div class="auth-alert auth-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            @if ($approved)
                <div class="auth-status__icon auth-status__icon--approved"><i class="bi bi-check-circle-fill" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Login Dikonfirmasi</h1>
                <p class="auth-status__desc">Login Anda sudah dikonfirmasi melalui email. Anda akan diarahkan ke dashboard.</p>
            @else
                <div class="auth-status__icon auth-status__icon--pending"><i class="bi bi-envelope-paper-fill" aria-hidden="true"></i></div>
                <h1 class="auth-status__title">Konfirmasi Login Lewat Email</h1>
                <p class="auth-status__desc">
                    Kami telah mengirim email konfirmasi login ke <strong>{{ $email }}</strong>.
                    Buka email tersebut lalu pilih <strong>Ya, Ini Saya</strong> untuk melanjutkan login.
                </p>
                <span class="auth-status__badge auth-status__badge--pending"><i class="bi bi-clock-fill" aria-hidden="true"></i> Menunggu Konfirmasi</span>
                <div class="auth-status__info">
                    <i class="bi bi-shield-lock-fill" aria-hidden="true"></i>
                    Tautan konfirmasi berlaku sampai pukul {{ $confirmation->expires_at->format('H:i') }} WIB.
                    Halaman ini akan otomatis masuk ke dashboard setelah login dikonfirmasi.
                    Jika email tidak ditemukan, periksa folder Spam atau kirim ulang email konfirmasi.
                </div>
            @endif

            <div class="auth-status__actions">
                <form action="{{ route('login.konfirmasi.lanjut') }}" method="POST" id="lanjutLogin">
                    @csrf
                    <button type="submit" class="auth-btn">
                        <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> {{ $approved ? 'Lanjutkan ke Dashboard' : 'Saya Sudah Konfirmasi' }}
                    </button>
                </form>
                @unless ($approved)
                    <form action="{{ route('login.konfirmasi.kirim-ulang') }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-btn-outline">
                            <i class="bi bi-arrow-repeat" aria-hidden="true"></i> Kirim Ulang Email
                        </button>
                    </form>
                    <form action="{{ route('login.konfirmasi.batal') }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-btn-outline">
                            <i class="bi bi-x-lg" aria-hidden="true"></i> Batalkan Login
                        </button>
                    </form>
                @endunless
            </div>
        </div>
    </main>

    <script>
        (function () {
            var form = document.getElementById('lanjutLogin');
            var statusUrl = @json(route('login.konfirmasi.status'));
            var selesai = false;

            function lanjut() {
                selesai = true;
                form.submit();
            }

            function periksa() {
                if (selesai) {
                    return;
                }
                fetch(statusUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                    cache: 'no-store'
                }).then(function (response) {
                    return response.ok ? response.json() : null;
                }).then(function (data) {
                    if (!data || selesai) {
                        return;
                    }
                    if (data.status === 'approved') {
                        lanjut();
                    } else if (data.status !== 'pending') {
                        selesai = true;
                        window.location.reload();
                    }
                }).catch(function () {
                });
            }

            @if ($approved)
                lanjut();
            @else
                window.setInterval(periksa, 3000);
            @endif
        })();
    </script>
</x-authorized.layout>
