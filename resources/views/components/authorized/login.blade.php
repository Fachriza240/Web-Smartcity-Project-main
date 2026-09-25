<x-authorized.layout title="Login">
    <main class="auth-card">
        <div class="auth-left">
            <a href="{{ url('/') }}" class="auth-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda
            </a>

            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <h1 class="auth-heading">Masuk ke akun</h1>
            <p class="auth-sub">Masuk ke panel CoE Smart City Telkom University.</p>

            @if (session('success'))
                <div class="auth-alert auth-alert-success" role="status">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @error('email')
                <div class="auth-alert auth-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <form action="{{ route('login.masuk') }}" method="POST" novalidate data-validate>
                @csrf

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@contoh.com" autocomplete="email" required
                        data-label="Email" data-rules="required|email|max:100"
                        class="@error('email') is-error @enderror" aria-describedby="error-email">
                    <span class="auth-error" id="error-email" data-error-for="email"></span>
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <div class="auth-input-wrap">
                        <input type="password" id="password" name="password" placeholder="Masukkan password"
                            autocomplete="current-password" required data-label="Password" data-rules="required|max:64"
                            class="@error('password') is-error @enderror" aria-describedby="error-password">
                        <button type="button" class="auth-eye" data-toggle-password="password" aria-label="Tampilkan password">
                            <i class="bi bi-eye-slash" aria-hidden="true"></i>
                        </button>
                    </div>
                    <span class="auth-error" id="error-password" data-error-for="password">@error('password'){{ $message }}@enderror</span>
                </div>

                <div class="auth-meta">
                    <label class="auth-meta-left">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Ingat saya
                    </label>
                    <a href="{{ route('contact') }}">Lupa password?</a>
                </div>

                <button type="submit" class="auth-btn">Masuk</button>
            </form>

            <p class="auth-bottom">
                Belum punya akun? <a href="{{ route('registrasi') }}">Daftar sekarang</a>
            </p>
        </div>

        <x-authorized.visual />
    </main>
</x-authorized.layout>
