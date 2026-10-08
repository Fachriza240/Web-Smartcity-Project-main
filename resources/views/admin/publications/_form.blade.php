@csrf

@php
    $isMember = old('submission_type', $publication->submission_type) === 'member';
@endphp

<div class="mb-3">
    <span class="form-label fw-semibold d-block">Tipe Pengusul <span class="text-danger">*</span></span>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="pubTypeMember"
                   value="member" @checked($isMember) data-switch>
            <label class="form-check-label" for="pubTypeMember">Member (Dosen terdaftar)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="pubTypeNonMember"
                   value="non_member" @checked(! $isMember) data-switch>
            <label class="form-check-label" for="pubTypeNonMember">Non-Member (isi manual)</label>
        </div>
    </div>
    @error('submission_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div id="pubMemberField" class="mb-3" data-switch-panel="member" data-field @unless ($isMember) hidden @endunless>
    <label for="user_id" class="form-label">Dosen Pengusul <span class="text-danger">*</span></label>
    <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" data-label="Dosen" data-rules="required">
        <option value="">-- Pilih Dosen --</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" @selected(old('user_id', $publication->user_id) == $dosen->id)>
                {{ $dosen->fullname }} {{ $dosen->nip ? '(' . $dosen->nip . ')' : '' }}
            </option>
        @endforeach
    </select>
    @error('user_id')<div class="invalid-feedback d-block" data-error-for="user_id">{{ $message }}</div>@enderror
</div>

<div id="pubNonMemberField" class="mb-3" data-switch-panel="non_member" data-field @if ($isMember) hidden @endif>
    <label for="recommended_by" class="form-label">Nama Pengusul (Manual)</label>
    <input type="text" id="recommended_by" name="recommended_by"
           class="form-control @error('recommended_by') is-invalid @enderror"
           value="{{ old('recommended_by', $publication->recommended_by) }}"
           placeholder="Nama lengkap pengusul non-member"
           data-label="Nama pengusul" data-rules="min:3|max:100|name">
    @error('recommended_by')<div class="invalid-feedback d-block" data-error-for="recommended_by">{{ $message }}</div>@enderror
</div>

<hr>

<div class="row">
    <div class="col-md-8 mb-3" data-field>
        <label for="judul" class="form-label">Judul Publikasi <span class="text-danger">*</span></label>
        <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $publication->judul) }}" required
               data-label="Judul" data-rules="required|min:5|max:200|safe">
        <div class="form-text">Minimal 5 karakter, maksimal 200 karakter.</div>
        @error('judul')<div class="invalid-feedback d-block" data-error-for="judul">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3" data-field>
        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
               value="{{ old('tahun', $publication->tahun) }}" min="1900" max="{{ date('Y') + 1 }}" required
               data-label="Tahun" data-rules="required|range:1900,{{ date('Y') + 1 }}">
        @error('tahun')<div class="invalid-feedback d-block" data-error-for="tahun">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
    <input type="text" id="penulis" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
           value="{{ old('penulis', $publication->penulis) }}" required placeholder="Nama penulis, pisahkan dengan koma"
           data-label="Penulis" data-rules="required|min:3|max:255|name">
    @error('penulis')<div class="invalid-feedback d-block" data-error-for="penulis">{{ $message }}</div>@enderror
</div>

<div class="mb-3" data-field>
    <label for="abstrak" class="form-label">Abstrak <span class="text-danger">*</span></label>
    <textarea id="abstrak" name="abstrak" class="form-control @error('abstrak') is-invalid @enderror"
              rows="5" required data-label="Abstrak" data-rules="required|min:20|max:5000|safe">{{ old('abstrak', $publication->abstrak) }}</textarea>
    <div class="form-text">Minimal 20 karakter, maksimal 5000 karakter.</div>
    @error('abstrak')<div class="invalid-feedback d-block" data-error-for="abstrak">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror" required
                data-label="Kategori" data-rules="required">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('kategori', $publication->kategori) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('kategori')<div class="invalid-feedback d-block" data-error-for="kategori">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <div data-switch-panel="member" @unless ($isMember) hidden @endunless>
            <span class="form-label d-block">Status</span>
            <span class="badge bg-success">Publish</span>
            <div class="form-text">Publikasi milik dosen langsung dipublikasikan tanpa status Draft.</div>
        </div>
        <div data-switch-panel="non_member" data-field @if ($isMember) hidden @endif>
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" data-label="Status" data-rules="required">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $publication->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" id="penerbit" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
               value="{{ old('penerbit', $publication->penerbit) }}" placeholder="Nama jurnal atau penerbit"
               data-label="Penerbit" data-rules="min:2|max:200|safe">
        @error('penerbit')<div class="invalid-feedback d-block" data-error-for="penerbit">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="doi" class="form-label">DOI</label>
        <input type="text" id="doi" name="doi" class="form-control @error('doi') is-invalid @enderror"
               value="{{ old('doi', $publication->doi) }}" placeholder="10.xxxx/xxxxx"
               data-label="DOI" data-rules="max:255|pattern" data-pattern="^[A-Za-z0-9./:_()\-]+$"
               data-pattern-message="DOI hanya boleh berisi huruf, angka, titik, garis miring, titik dua, garis bawah, kurung, dan tanda hubung.">
        @error('doi')<div class="invalid-feedback d-block" data-error-for="doi">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="pdf" class="form-label">File PDF @unless ($publication->exists)<span class="text-danger">*</span>@endunless</label>
        <input type="file" id="pdf" name="pdf" class="form-control @error('pdf') is-invalid @enderror"
               accept=".pdf,application/pdf" @if (! $publication->exists) required @endif
               data-label="File PDF" data-rules="{{ $publication->exists ? '' : 'required|' }}file:pdf|filesize:20480">
        <div class="form-text">Format PDF, maksimal 20 MB.</div>
        @if ($publication->pdf_path)
            <div class="small mt-2">
                File saat ini:
                <a href="{{ asset('storage/' . $publication->pdf_path) }}" target="_blank" rel="noopener">Unduh PDF</a>
            </div>
        @endif
        @error('pdf')<div class="invalid-feedback d-block" data-error-for="pdf">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="thumbnail" class="form-label">Thumbnail</label>
        <input type="file" id="thumbnail" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*"
               data-label="Thumbnail" data-rules="file:jpg,jpeg,png,webp,gif,bmp|filesize:4096">
        <div class="form-text">Format JPG, PNG, WEBP, GIF, atau BMP, maksimal 4 MB.</div>
        @if ($publication->thumbnail_path)
            <img src="{{ asset('storage/' . $publication->thumbnail_path) }}" alt="Thumbnail" class="mt-2 adm-preview adm-preview--sm">
        @endif
        @error('thumbnail')<div class="invalid-feedback d-block" data-error-for="thumbnail">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success"
            data-confirm-submit="Apakah data yang diinputkan sudah benar?"
            data-confirm-second="Apakah Anda yakin ingin menyimpan perubahan?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.publications.index') }}" class="btn btn-light">Batal</a>
</div>