@csrf

<div class="row">
    <div class="col-md-9 mb-3" data-field>
        <label for="judul" class="form-label">Judul Program <span class="text-danger">*</span></label>
        <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $program->judul) }}" required
               data-label="Judul" data-rules="required|min:3|max:100|safe">
        <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
        @error('judul')<div class="invalid-feedback d-block" data-error-for="judul">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3" data-field>
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
               value="{{ old('urutan', $program->urutan ?? 0) }}" min="0" max="9999"
               data-label="Urutan" data-rules="range:0,9999">
        <div class="form-text">Angka kecil tampil lebih dulu.</div>
        @error('urutan')<div class="invalid-feedback d-block" data-error-for="urutan">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
    <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
              rows="6" required data-label="Deskripsi" data-rules="required|min:20|max:5000|text">{{ old('deskripsi', $program->deskripsi) }}</textarea>
    <div class="form-text">Minimal 20 karakter, maksimal 5000 karakter.</div>
    @error('deskripsi')<div class="invalid-feedback d-block" data-error-for="deskripsi">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="thumbnail" class="form-label">Thumbnail @unless ($program->exists)<span class="text-danger">*</span>@endunless</label>
        <input type="file" id="thumbnail" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*" @if (! $program->exists) required @endif
               data-label="Thumbnail" data-rules="{{ $program->exists ? '' : 'required|' }}file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
        <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
        @if ($program->thumbnail_path)
            <div class="mt-2">
                <img class="adm-preview" src="{{ asset('storage/' . $program->thumbnail_path) }}" alt="{{ $program->judul }}">
                <div class="small text-muted mt-1">Thumbnail saat ini. Unggah file baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail')<div class="invalid-feedback d-block" data-error-for="thumbnail">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $program->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan data program ini?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.programs.index') }}" class="btn btn-light">Batal</a>
</div>
