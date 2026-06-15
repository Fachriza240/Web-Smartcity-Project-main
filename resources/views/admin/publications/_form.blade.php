@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul', $publication->judul) }}" required>
        @error('judul') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $publication->tahun) }}" min="1900" max="{{ date('Y') + 1 }}" required>
        @error('tahun') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Penulis</label>
    <input type="text" name="penulis" class="form-control" value="{{ old('penulis', $publication->penulis) }}" required>
    @error('penulis') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Abstrak</label>
    <textarea name="abstrak" class="form-control" rows="5" required>{{ old('abstrak', $publication->abstrak) }}</textarea>
    @error('abstrak') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" @selected(old('kategori', $publication->kategori) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('kategori') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $publication->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Penerbit</label>
        <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit', $publication->penerbit) }}">
        @error('penerbit') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">DOI</label>
        <input type="text" name="doi" class="form-control" value="{{ old('doi', $publication->doi) }}" placeholder="10.xxxx/xxxxx">
        @error('doi') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">PDF</label>
        <input type="file" name="pdf" class="form-control" accept="application/pdf" @if(!$publication->exists) required @endif>
        @if($publication->pdf_path)
            <div class="small mt-2">
                File saat ini:
                <a href="{{ asset('storage/' . $publication->pdf_path) }}" target="_blank">Download PDF</a>
            </div>
        @endif
        @error('pdf') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/*">
        @if($publication->thumbnail_path)
            <img src="{{ asset('storage/' . $publication->thumbnail_path) }}" alt="Thumbnail Publication" class="mt-2" style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px;">
        @endif
        @error('thumbnail') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="bi bi-save"></i>
        Simpan
    </button>
    <a href="{{ route('admin.publications.index') }}" class="btn btn-light">Batal</a>
</div>
