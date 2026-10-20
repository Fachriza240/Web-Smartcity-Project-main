<x-layout.admin title="Akun Saya" subtitle="Ubah email dan password akun Anda.">

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-person-circle"></i> Informasi Akun
        </div>
        <p class="adm-settings-section__desc">Data akun yang sedang digunakan.</p>

        <ul class="adm-info-list">
            <li>
                <span>Nama</span>
                <span>{{ $user->fullname ?? '-' }}</span>
            </li>
            <li>
                <span>Email</span>
                <span>{{ $user->email }}</span>
            </li>
            <li>
                <span>Peran</span>
                <span>{{ $user->role === 'content_creator' ? 'Content Creator' : 'Admin' }}</span>
            </li>
        </ul>
    </div>

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-envelope"></i> Ubah Email
        </div>
        <p class="adm-settings-section__desc">Masukkan email baru dan password saat ini untuk konfirmasi.</p>

        <form action="{{ route('admin.akun.email') }}" method="POST" novalidate data-validate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3" data-field>
                    <label for="email" class="form-label">Email Baru <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" autocomplete="email"
                           class="form-control @error('email', 'akunEmail') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required
                           data-label="Email" data-rules="required|email|min:6|max:254">
                    @error('email', 'akunEmail')<div class="invalid-feedback d-block" data-error-for="email">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3" data-field>
                    <label for="email_current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                    <input type="password" id="email_current_password" name="current_password" autocomplete="current-password"
                           class="form-control @error('current_password', 'akunEmail') is-invalid @enderror" required
                           data-label="Password saat ini" data-rules="required|max:64">
                    @error('current_password', 'akunEmail')<div class="invalid-feedback d-block" data-error-for="current_password">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary" data-confirm-submit="Apakah Anda yakin ingin mengubah email akun?">
                <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Email
            </button>
        </form>
    </div>

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-key"></i> Ubah Password
        </div>
        <p class="adm-settings-section__desc">Gunakan password baru yang terdiri dari 8 sampai 64 karakter.</p>

        <form action="{{ route('admin.akun.password') }}" method="POST" novalidate data-validate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3" data-field>
                    <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                           class="form-control @error('current_password', 'akunPassword') is-invalid @enderror" required
                           data-label="Password saat ini" data-rules="required|max:64">
                    @error('current_password', 'akunPassword')<div class="invalid-feedback d-block" data-error-for="current_password">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3" data-field>
                    <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" autocomplete="new-password"
                           class="form-control @error('password', 'akunPassword') is-invalid @enderror" required
                           data-label="Password baru" data-rules="required|min:8|max:64">
                    @error('password', 'akunPassword')<div class="invalid-feedback d-block" data-error-for="password">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3" data-field>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                           class="form-control" required
                           data-label="Konfirmasi password" data-rules="required|same:password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" data-confirm-submit="Apakah Anda yakin ingin mengubah password akun?">
                <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Password
            </button>
        </form>
    </div>

</x-layout.admin>