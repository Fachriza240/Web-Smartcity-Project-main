@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $project->judul) }}" required>
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
               value="{{ old('tahun', $project->tahun) }}" min="1900" max="{{ date('Y') + 1 }}"
               placeholder="{{ date('Y') }}">
        @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
               value="{{ old('kategori', $project->kategori) }}" placeholder="Contoh: IoT, AI, Infrastruktur">
        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Partner</label>
        <input type="text" name="partner" class="form-control @error('partner') is-invalid @enderror"
               value="{{ old('partner', $project->partner) }}" placeholder="Nama instansi / mitra">
        @error('partner') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
              rows="5" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Thumbnail {{ $project->exists ? '' : '<span class="text-danger">*</span>' }}</label>
        <input type="file" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*"
               @if(!$project->exists) required @endif>
        @if($project->thumbnail_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $project->thumbnail_path) }}"
                     alt="Thumbnail" style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px;">
                <div class="small text-muted mt-1">Thumbnail saat ini. Upload baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Gallery (bisa pilih banyak foto)</label>
    <input type="file" name="gallery[]"
           class="form-control @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror"
           accept="image/*" multiple>
    @error('gallery') <div class="text-danger small">{{ $message }}</div> @enderror
    @error('gallery.*') <div class="text-danger small">{{ $message }}</div> @enderror
    @if($project->exists && !empty($project->gallery_paths))
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach($project->gallery_paths as $img)
                <img src="{{ asset('storage/' . $img) }}"
                     alt="Gallery" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px;">
            @endforeach
        </div>
        <div class="small text-muted mt-1">Gallery saat ini ({{ count($project->gallery_paths) }} foto). Upload baru untuk mengganti semua.</div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Dokumen</label>
    <input type="file" name="dokumen"
           class="form-control @error('dokumen') is-invalid @enderror"
           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
    <div class="form-text">Format: PDF, Word, PowerPoint, Excel, ZIP, RAR. Maks 20MB.</div>
    @if($project->dokumen_path)
        <div class="small mt-1">
            Dokumen saat ini:
            <a href="{{ asset('storage/' . $project->dokumen_path) }}" target="_blank">Download</a>
            <span class="text-muted">— Upload baru untuk mengganti.</span>
        </div>
    @endif
    @error('dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success">
        <i class="bi bi-save me-1"></i> Simpan
    </button>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-light">Batal</a>
</div>
