@csrf

<div class="row">
    <div class="col-md-9 mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $program->judul) }}" required>
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Urutan</label>
        <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $program->urutan ?? 0) }}" min="0">
        <div class="form-text">Angka kecil tampil lebih dulu.</div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
              rows="6" required>{{ old('deskripsi', $program->deskripsi) }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Thumbnail {{ $program->exists ? '' : '<span class="text-danger">*</span>' }}</label>
        <input type="file" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*"
               @if(!$program->exists) required @endif>
        @if($program->thumbnail_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $program->thumbnail_path) }}" alt="{{ $program->judul }}"
                     style="width:160px;height:100px;object-fit:cover;border-radius:8px;">
                <div class="small text-muted mt-1">Thumbnail saat ini. Upload baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $program->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('admin.programs.index') }}" class="btn btn-light">Batal</a>
</div>
