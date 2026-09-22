<x-layout.link>
    <x-layout.navbar-dosen></x-layout.navbar-dosen>
    <x-halaman-dosen.profil-dosen :user="$user"></x-halaman-dosen.profil-dosen>
    <x-layout.footer></x-layout.footer>
</x-layout.link>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Ganti Password</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('profil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="current_password" class="form-label">Password Lama</label>
                <input type="password" id="current_password" name="current_password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <input type="password" id="new_password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       minlength="6" maxlength="64" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="new_password_confirmation" name="password_confirmation"
                       class="form-control"
                       minlength="6" maxlength="64" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
        </form>
    </div>
</div>