@csrf

<div class="row">
    <div class="col-md-8 mb-3" data-field>
        <label for="judul" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
        <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $project->judul) }}" required
               data-label="Judul" data-rules="required|min:3|max:100|safe">
        <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
        @error('judul')<div class="invalid-feedback d-block" data-error-for="judul">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="tahun" class="form-label">Tahun</label>
        <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
               value="{{ old('tahun', $project->tahun) }}" min="1900" max="{{ date('Y') + 1 }}"
               placeholder="{{ date('Y') }}" data-label="Tahun" data-rules="range:1900,{{ date('Y') + 1 }}">
        @error('tahun')<div class="invalid-feedback d-block" data-error-for="tahun">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
               value="{{ old('kategori', $project->kategori) }}" placeholder="Contoh: IoT, AI, Infrastruktur"
               data-label="Kategori" data-rules="min:2|max:100|safe">
        @error('kategori')<div class="invalid-feedback d-block" data-error-for="kategori">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="partner" class="form-label">Partner</label>
        <input type="text" id="partner" name="partner" class="form-control @error('partner') is-invalid @enderror"
               value="{{ old('partner', $project->partner) }}" placeholder="Nama instansi atau mitra"
               data-label="Mitra" data-rules="min:2|max:200|safe">
        @error('partner')<div class="invalid-feedback d-block" data-error-for="partner">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
    <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
              rows="5" required data-label="Deskripsi" data-rules="required|min:20|max:5000|text">{{ old('deskripsi', $project->deskripsi) }}</textarea>
    <div class="form-text">Minimal 20 karakter, maksimal 5000 karakter.</div>
    @error('deskripsi')<div class="invalid-feedback d-block" data-error-for="deskripsi">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="thumbnail" class="form-label">Thumbnail @unless ($project->exists)<span class="text-danger">*</span>@endunless</label>
        <input type="file" id="thumbnail" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*" @if (! $project->exists) required @endif
               data-label="Thumbnail" data-rules="{{ $project->exists ? '' : 'required|' }}file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
        <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
        @if ($project->thumbnail_path)
            <div class="mt-2">
                <img class="adm-preview adm-preview--sm" src="{{ asset('storage/' . $project->thumbnail_path) }}" alt="Thumbnail">
                <div class="small text-muted mt-1">Thumbnail saat ini. Unggah file baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail')<div class="invalid-feedback d-block" data-error-for="thumbnail">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="gallery" class="form-label">Galeri (bisa pilih banyak foto)</label>
    <input type="file" id="gallery" name="gallery[]"
           class="form-control @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror"
           accept="image/*" multiple
           data-label="Foto galeri" data-rules="file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
    <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB per foto.</div>
    @if ($project->exists && ! empty($project->gallery_paths))
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach ($project->gallery_paths as $img)
                <img class="adm-preview-gallery" src="{{ asset('storage/' . $img) }}" alt="Galeri">
            @endforeach
        </div>
        <div class="small text-muted mt-1">Galeri saat ini ({{ count($project->gallery_paths) }} foto). Unggah file baru untuk mengganti semua.</div>
    @endif
    @if ($errors->has('gallery') || $errors->has('gallery.*'))
        <div class="invalid-feedback d-block" data-error-for="gallery[]">{{ $errors->first('gallery') ?: $errors->first('gallery.*') }}</div>
    @endif
</div>

<div class="mb-3" data-field>
    <label for="dokumen" class="form-label">Dokumen</label>
    <input type="file" id="dokumen" name="dokumen"
           class="form-control @error('dokumen') is-invalid @enderror"
           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
           data-label="Dokumen" data-rules="file:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|filesize:20480">
    <div class="form-text">Format PDF, Word, PowerPoint, Excel, ZIP, atau RAR, maksimal 20 MB.</div>
    @if ($project->dokumen_path)
        <div class="small mt-1">
            Dokumen saat ini:
            <a href="{{ asset('storage/' . $project->dokumen_path) }}" target="_blank" rel="noopener">Unduh</a>
            <span class="text-muted">Unggah file baru untuk mengganti.</span>
        </div>
    @endif
    @error('dokumen')<div class="invalid-feedback d-block" data-error-for="dokumen">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan data proyek ini?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-light">Batal</a>
</div>
