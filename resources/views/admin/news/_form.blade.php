@csrf

<div class="row">
    <div class="col-md-8 mb-3" data-field>
        <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
        <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $news->judul) }}" required
               data-label="Judul" data-rules="required|min:3|max:100|safe">
        <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
        @error('judul')<div class="invalid-feedback d-block" data-error-for="judul">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="kategori" class="form-label">Kategori</label>
        <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(old('kategori', $news->kategori) === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('kategori')<div class="invalid-feedback d-block" data-error-for="kategori">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="konten" class="form-label">Isi Berita <span class="text-danger">*</span></label>
    <textarea id="konten" name="konten" class="form-control @error('konten') is-invalid @enderror"
              rows="12" required data-label="Isi berita" data-rules="required|min:20|max:5000|text">{{ old('konten', $news->konten) }}</textarea>
    <div class="form-text">Minimal 20 karakter, maksimal 5000 karakter.</div>
    @error('konten')<div class="invalid-feedback d-block" data-error-for="konten">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="thumbnail" class="form-label">Thumbnail @unless ($news->exists)<span class="text-danger">*</span>@endunless</label>
        <input type="file" id="thumbnail" name="thumbnail"
               class="form-control @error('thumbnail') is-invalid @enderror"
               accept="image/*" @if (! $news->exists) required @endif
               data-label="Thumbnail" data-rules="{{ $news->exists ? '' : 'required|' }}file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
        <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
        @if ($news->thumbnail_path)
            <div class="mt-2">
                <img class="adm-preview" src="{{ asset('storage/' . $news->thumbnail_path) }}" alt="{{ $news->judul }}">
                <div class="small text-muted mt-1">Thumbnail saat ini. Unggah file baru untuk mengganti.</div>
            </div>
        @endif
        @error('thumbnail')<div class="invalid-feedback d-block" data-error-for="thumbnail">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $news->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <div class="form-text">Ubah ke Publish untuk menampilkan berita di halaman publik.</div>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan data berita ini?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.news.index') }}" class="btn btn-light">Batal</a>
</div>
