@csrf

<div class="mb-3">
    <label class="form-label fw-semibold">Tipe Pengusul <span class="text-danger">*</span></label>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="pubTypeMember"
                   value="member"
                   @checked(old('submission_type', $publication->submission_type) === 'member') data-switch>
            <label class="form-check-label" for="pubTypeMember">Member (Dosen terdaftar)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="pubTypeNonMember"
                   value="non_member"
                   @checked(old('submission_type', $publication->submission_type) !== 'member') data-switch>
            <label class="form-check-label" for="pubTypeNonMember">Non-Member (isi manual)</label>
        </div>
    </div>
</div>

<div id="pubMemberField" class="mb-3" data-switch-panel="member" @if (old('submission_type', $publication->submission_type) !== 'member') hidden @endif>
    <label class="form-label">Dosen Pengusul</label>
    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
        <option value="">-- Pilih Dosen --</option>
        @foreach($dosens as $dosen)
            <option value="{{ $dosen->id }}"
                    @selected(old('user_id', $publication->user_id) == $dosen->id)>
                {{ $dosen->fullname }} {{ $dosen->nip ? '(' . $dosen->nip . ')' : '' }}
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div id="pubNonMemberField" class="mb-3" data-switch-panel="non_member" @if (old('submission_type', $publication->submission_type) === 'member') hidden @endif>
    <label class="form-label">Nama Pengusul (Manual)</label>
    <input type="text" name="recommended_by"
           class="form-control @error('recommended_by') is-invalid @enderror"
           value="{{ old('recommended_by', $publication->recommended_by) }}"
           placeholder="Nama lengkap pengusul non-member">
    @error('recommended_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr>

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $publication->judul) }}" required>
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
               value="{{ old('tahun', $publication->tahun) }}" min="1900" max="{{ date('Y') + 1 }}" required>
        @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Penulis <span class="text-danger">*</span></label>
    <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
           value="{{ old('penulis', $publication->penulis) }}" required>
    @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Abstrak <span class="text-danger">*</span></label>
    <textarea name="abstrak" class="form-control @error('abstrak') is-invalid @enderror"
              rows="5" required>{{ old('abstrak', $publication->abstrak) }}</textarea>
    @error('abstrak') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category }}"
                        @selected(old('kategori', $publication->kategori) === $category)>
                    {{ $category }}
                </option>
            @endforeach
        </select>
        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}"
                        @selected(old('status', $publication->status) === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Penerbit</label>
        <input type="text" name="penerbit" class="form-control"
               value="{{ old('penerbit', $publication->penerbit) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">DOI</label>
        <input type="text" name="doi" class="form-control"
               value="{{ old('doi', $publication->doi) }}" placeholder="10.xxxx/xxxxx">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">
            PDF @unless ($publication->exists)<span class="text-danger">*</span>@endunless
        </label>
        <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror"
               accept="application/pdf"
               @if(!$publication->exists) required @endif>
        @if($publication->pdf_path)
            <div class="small mt-2">
                File saat ini:
                <a href="{{ asset('storage/' . $publication->pdf_path) }}" target="_blank">Download PDF</a>
            </div>
        @endif
        @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/*">
        @if($publication->thumbnail_path)
            <img src="{{ asset('storage/' . $publication->thumbnail_path) }}"
                 alt="Thumbnail" class="mt-2 adm-preview adm-preview--sm">
        @endif
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="bi bi-save me-1"></i> Simpan
    </button>
    <a href="{{ route('admin.publications.index') }}" class="btn btn-light">Batal</a>
</div>
