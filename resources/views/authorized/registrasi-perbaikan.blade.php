@php
    $isDosen = $user->role === 'dosen';
@endphp

<x-authorized.layout title="Perbaiki Data Registrasi">
    <main class="auth-card">
        <div class="auth-left">
            <a href="{{ route('dosen.status') }}" class="auth-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Status Registrasi
            </a>

            <div class="auth-logo">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University">
            </div>

            <h1 class="auth-heading">Perbaiki data registrasi</h1>
            <p class="auth-sub">Perbaiki data sesuai alasan penolakan, lalu ajukan ulang untuk divalidasi Admin.</p>

            @if ($user->rejection_reason)
                <div class="auth-status__info">
                    <i class="bi bi-chat-left-text-fill" aria-hidden="true"></i>
                    <strong>Alasan penolakan dari Admin:</strong>
                    <span class="d-block">{{ $user->rejection_reason }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="auth-alert auth-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>Periksa kembali data yang ditandai merah di bawah.</span>
                </div>
            @endif

            <form action="{{ route('registrasi.perbaikan.update') }}" method="POST" novalidate data-validate>
                @csrf
                @method('PUT')

                <div class="auth-field">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                        placeholder="Nama lengkap sesuai identitas" autocomplete="name" required
                        data-label="Nama lengkap" data-rules="required|min:3|max:100|name"
                        class="@error('fullname') is-error @enderror" aria-describedby="error-fullname">
                    <span class="auth-error" id="error-fullname" data-error-for="fullname">@error('fullname'){{ $message }}@enderror</span>
                </div>

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="nama@contoh.com" autocomplete="email" required
                        data-label="Email" data-rules="required|email|min:6|max:254"
                        class="@error('email') is-error @enderror" aria-describedby="error-email">
                    <span class="auth-error" id="error-email" data-error-for="email">@error('email'){{ $message }}@enderror</span>
                </div>

                @if ($isDosen)
                    <div class="auth-field">
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai"
                            inputmode="numeric" data-label="NIP" data-rules="required|digits:4,30"
                            class="@error('nip') is-error @enderror" aria-describedby="error-nip">
                        <span class="auth-error" id="error-nip" data-error-for="nip">@error('nip'){{ $message }}@enderror</span>
                    </div>

                    <div class="auth-row">
                        <div class="auth-field">
                            <label for="prodi">Program Studi <span class="auth-optional">(opsional)</span></label>
                            <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $user->prodi) }}" placeholder="Contoh: Sistem Informasi"
                                data-label="Program studi" data-rules="min:2|max:100|safe"
                                class="@error('prodi') is-error @enderror" aria-describedby="error-prodi">
                            <span class="auth-error" id="error-prodi" data-error-for="prodi">@error('prodi'){{ $message }}@enderror</span>
                        </div>
                        <div class="auth-field">
                            <label for="fakultas">Fakultas <span class="auth-optional">(opsional)</span></label>
                            <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas', $user->fakultas) }}" placeholder="Contoh: Fakultas Ilmu Terapan"
                                data-label="Fakultas" data-rules="min:2|max:100|safe"
                                class="@error('fakultas') is-error @enderror" aria-describedby="error-fakultas">
                            <span class="auth-error" id="error-fakultas" data-error-for="fakultas">@error('fakultas'){{ $message }}@enderror</span>
                        </div>
                    </div>
                @endif

                <div class="auth-note">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    <span>Setelah diajukan ulang, status akun kembali menjadi Menunggu Validasi Admin.</span>
                </div>

                <button type="submit" class="auth-btn">Ajukan Ulang Registrasi</button>
            </form>
        </div>

        <x-authorized.visual />
    </main>
</x-authorized.layout>
