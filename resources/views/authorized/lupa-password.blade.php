<x-authorized.layout title="Lupa Password">
    <main class="auth-card">
        <div class="auth-left">
            <a href="{{ route('login') }}" class="auth-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Login
            </a>

            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <h1 class="auth-heading">Lupa password</h1>
            <p class="auth-sub">Masukkan email akun Anda. Kami akan mengirimkan tautan untuk membuat password baru.</p>

            @if (session('success'))
                <div class="auth-alert auth-alert-success" role="status">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" novalidate data-validate>
                @csrf

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@contoh.com" autocomplete="email" required
                        data-label="Email" data-rules="required|email|min:6|max:254"
                        class="@error('email') is-error @enderror" aria-describedby="error-email">
                    <span class="auth-error" id="error-email" data-error-for="email">@error('email'){{ $message }}@enderror</span>
                </div>

                <button type="submit" class="auth-btn">Kirim Tautan Reset Password</button>
            </form>

            <p class="auth-bottom">
                Sudah ingat password? <a href="{{ route('login') }}">Masuk</a>
            </p>
        </div>

        <x-authorized.visual />
    </main>
</x-authorized.layout>
