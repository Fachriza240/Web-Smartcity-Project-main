@php
    $role = old('role', 'dosen') === 'content_creator' ? 'content_creator' : 'dosen';
@endphp

<x-authorized.layout title="Daftar Akun">
    <main class="auth-card">
        <div class="auth-left">
            <a href="{{ url('/') }}" class="auth-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda
            </a>

            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <h1 class="auth-heading">Buat akun</h1>
            <p class="auth-sub">Daftar untuk bergabung dengan CoE Smart City Telkom University.</p>

            @if ($errors->any())
                <div class="auth-alert auth-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>Periksa kembali data yang ditandai merah di bawah.</span>
                </div>
            @endif

            <form action="{{ route('registrasi') }}" method="POST" enctype="multipart/form-data" novalidate data-validate>
                @csrf
                <input type="hidden" name="role" id="roleHidden" value="{{ $role }}">

                <fieldset class="auth-roles">
                    <legend class="visually-hidden">Daftar sebagai</legend>
                    <label class="auth-role {{ $role === 'dosen' ? 'active' : '' }}" data-role="dosen">
                        <input type="radio" name="role_ui" value="dosen" @checked($role === 'dosen')>
                        <span class="auth-role-icon"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></span>
                        <span>
                            <span class="auth-role-name d-block">Dosen</span>
                            <span class="auth-role-sub d-block">Tenaga pengajar</span>
                        </span>
                    </label>
                    <label class="auth-role {{ $role === 'content_creator' ? 'active' : '' }}" data-role="content_creator">
                        <input type="radio" name="role_ui" value="content_creator" @checked($role === 'content_creator')>
                        <span class="auth-role-icon"><i class="bi bi-pen-fill" aria-hidden="true"></i></span>
                        <span>
                            <span class="auth-role-name d-block">Content Creator</span>
                            <span class="auth-role-sub d-block">Kelola konten web</span>
                        </span>
                    </label>
                </fieldset>

                <div class="auth-field">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}"
                        placeholder="Nama lengkap sesuai identitas" autocomplete="name" required
                        data-label="Nama lengkap" data-rules="required|min:3|max:100|name"
                        class="@error('fullname') is-error @enderror" aria-describedby="error-fullname">
                    <span class="auth-error" id="error-fullname" data-error-for="fullname">@error('fullname'){{ $message }}@enderror</span>
                </div>

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@contoh.com" autocomplete="email" required
                        data-label="Email" data-rules="required|email|max:100"
                        class="@error('email') is-error @enderror" aria-describedby="error-email">
                    <span class="auth-error" id="error-email" data-error-for="email">@error('email'){{ $message }}@enderror</span>
                </div>

                <div class="auth-nip-wrap {{ $role === 'content_creator' ? 'hidden' : '' }}" data-dosen-only>
                    <div class="auth-field">
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                            inputmode="numeric" data-label="NIP" data-rules="required|digits:4,30"
                            class="@error('nip') is-error @enderror" aria-describedby="error-nip">
                        <span class="auth-error" id="error-nip" data-error-for="nip">@error('nip'){{ $message }}@enderror</span>
                    </div>
                </div>

                <div class="auth-nip-wrap {{ $role === 'content_creator' ? 'hidden' : '' }}" data-dosen-only>
                    <div class="auth-row">
                        <div class="auth-field">
                            <label for="prodi">Program Studi <span class="auth-optional">(opsional)</span></label>
                            <input type="text" id="prodi" name="prodi" value="{{ old('prodi') }}" placeholder="Contoh: Sistem Informasi"
                                data-label="Program studi" data-rules="min:2|max:100|safe"
                                class="@error('prodi') is-error @enderror" aria-describedby="error-prodi">
                            <span class="auth-error" id="error-prodi" data-error-for="prodi">@error('prodi'){{ $message }}@enderror</span>
                        </div>
                        <div class="auth-field">
                            <label for="fakultas">Fakultas <span class="auth-optional">(opsional)</span></label>
                            <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas') }}" placeholder="Contoh: Fakultas Ilmu Terapan"
                                data-label="Fakultas" data-rules="min:2|max:100|safe"
                                class="@error('fakultas') is-error @enderror" aria-describedby="error-fakultas">
                            <span class="auth-error" id="error-fakultas" data-error-for="fakultas">@error('fakultas'){{ $message }}@enderror</span>
                        </div>
                    </div>
                </div>

                <div class="auth-row">
                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-input-wrap">
                            <input type="password" id="password" name="password" placeholder="6 sampai 64 karakter"
                                autocomplete="new-password" required data-label="Password" data-rules="required|min:6|max:64"
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
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password"
                                autocomplete="new-password" required data-label="Konfirmasi password" data-rules="required|same:password"
                                aria-describedby="error-password_confirmation">
                            <button type="button" class="auth-eye" data-toggle-password="password_confirmation" aria-label="Tampilkan password">
                                <i class="bi bi-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="auth-error" id="error-password_confirmation" data-error-for="password_confirmation"></span>
                    </div>
                </div>

                <div class="auth-note">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    <span id="roleNote">Akun Anda akan diverifikasi oleh admin sebelum bisa digunakan.</span>
                </div>

                <button type="submit" class="auth-btn">Daftar Sekarang</button>
            </form>

            <p class="auth-bottom">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </p>
        </div>

        <x-authorized.visual />
    </main>
</x-authorized.layout>
