@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $news->judul) }}" required>
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(old('kategori', $news->kategori) === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Konten <span class="text-danger">*</span></label>
    <textarea name="konten" class="form-control @error('konten') is-invalid @enderror"
              rows="12" required>{{ old('konten', $news->konten) }}</textarea>
    @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Thumbnail @unless ($news->exists)<span class="text-danger">*</span>@endunless</label>
        <input type="file" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*"
               @if(!$news->exists) required @endif>
        @if($news->thumbnail_path)
            <div class="mt-2">
                <img class="adm-preview" src="{{ asset('storage/' . $news->thumbnail_path) }}"
                     alt="{{ $news->judul }}">
                <div class="small text-muted mt-1">Thumbnail saat ini. Unggah file baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $news->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <div class="form-text">Ubah ke Publish untuk menampilkan berita di frontend.</div>
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('admin.news.index') }}" class="btn btn-light">Batal</a>
</div>
