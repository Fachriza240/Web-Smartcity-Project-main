<x-authorized.layout title="Buat Password Baru">
    <main class="auth-card">
        <div class="auth-left">
            <a href="{{ route('login') }}" class="auth-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Login
            </a>

            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <h1 class="auth-heading">Buat password baru</h1>
            <p class="auth-sub">Masukkan password baru untuk akun Anda.</p>

            @error('email')
                <div class="auth-alert auth-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <form action="{{ route('password.update') }}" method="POST" novalidate data-validate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}"
                        placeholder="nama@contoh.com" autocomplete="email" required
                        data-label="Email" data-rules="required|email|min:6|max:254"
                        class="@error('email') is-error @enderror" aria-describedby="error-email">
                    <span class="auth-error" id="error-email" data-error-for="email"></span>
                </div>

                <div class="auth-row">
                    <div class="auth-field">
                        <label for="password">Password Baru</label>
                        <div class="auth-input-wrap">
                            <input type="password" id="password" name="password" placeholder="8 sampai 64 karakter"
                                autocomplete="new-password" required data-label="Password" data-rules="required|min:8|max:64"
                                class="@error('password') is-error @enderror" aria-describedby="error-password">
                            <button type="button" class="auth-eye" data-toggle-password="password" aria-label="Tampilkan password">
                                <i class="bi bi-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="auth-error" id="error-password" data-error-for="password">@error('password'){{ $message }}@enderror</span>
                    </div>
                    <div class="auth-field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="auth-input-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                                autocomplete="new-password" required data-label="Konfirmasi password" data-rules="required|same:password"
                                aria-describedby="error-password_confirmation">
                            <button type="button" class="auth-eye" data-toggle-password="password_confirmation" aria-label="Tampilkan password">
                                <i class="bi bi-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="auth-error" id="error-password_confirmation" data-error-for="password_confirmation"></span>
                    </div>
                </div>

                <button type="submit" class="auth-btn">Simpan Password Baru</button>
            </form>
        </div>

        <x-authorized.visual />
    </main>
</x-authorized.layout>
