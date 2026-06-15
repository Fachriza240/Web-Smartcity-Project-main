@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Mitra <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $partner->nama) }}" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Urutan</label>
        <input type="number" name="urutan" class="form-control"
               value="{{ old('urutan', $partner->urutan ?? 0) }}" min="0">
        <div class="form-text">Angka kecil tampil lebih dulu.</div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $partner->deskripsi) }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Website</label>
        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
               value="{{ old('website', $partner->website) }}" placeholder="https://example.com">
        @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $partner->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Logo</label>
    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
    <div class="form-text">Format: JPG, PNG, SVG, WebP. Maks 4MB.</div>
    @if($partner->exists && $partner->logo_path)
        <div class="mt-2">
            <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="{{ $partner->nama }}"
                 style="height:60px;max-width:160px;object-fit:contain;background:#f8f9fa;padding:8px;border-radius:8px;">
            <div class="small text-muted mt-1">Logo saat ini. Upload baru untuk mengganti.</div>
        </div>
    @endif
    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('admin.partners.index') }}" class="btn btn-light">Batal</a>
</div>
