@csrf

<div class="row">
    <div class="col-md-8 mb-3" data-field>
        <label for="nama" class="form-label">Nama Mitra <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $partner->nama) }}" required
               data-label="Nama mitra" data-rules="required|min:3|max:100|safe">
        <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
        @error('nama')<div class="invalid-feedback d-block" data-error-for="nama">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
               value="{{ old('urutan', $partner->urutan ?? 0) }}" min="0" max="9999"
               data-label="Urutan" data-rules="range:0,9999">
        <div class="form-text">Angka kecil tampil lebih dulu.</div>
        @error('urutan')<div class="invalid-feedback d-block" data-error-for="urutan">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="deskripsi" class="form-label">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4"
              data-label="Deskripsi" data-rules="min:20|max:5000|text">{{ old('deskripsi', $partner->deskripsi) }}</textarea>
    <div class="form-text">Opsional. Jika diisi, minimal 20 karakter dan maksimal 5000 karakter.</div>
    @error('deskripsi')<div class="invalid-feedback d-block" data-error-for="deskripsi">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="website" class="form-label">Website</label>
        <input type="url" id="website" name="website" class="form-control @error('website') is-invalid @enderror"
               value="{{ old('website', $partner->website) }}" placeholder="https://example.com"
               data-label="Website" data-rules="url|max:500">
        @error('website')<div class="invalid-feedback d-block" data-error-for="website">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $partner->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="logo" class="form-label">Logo</label>
    <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*"
           data-label="Logo" data-rules="file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
    <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
    @if ($partner->exists && $partner->logo_path)
        <div class="mt-2">
            <img class="adm-logo-lg" src="{{ asset('storage/'.$partner->logo_path) }}" alt="{{ $partner->nama }}">
            <div class="small text-muted mt-1">Logo saat ini. Unggah file baru untuk mengganti.</div>
        </div>
    @endif
    @error('logo')<div class="invalid-feedback d-block" data-error-for="logo">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan data mitra ini?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.partners.index') }}" class="btn btn-light">Batal</a>
</div>
