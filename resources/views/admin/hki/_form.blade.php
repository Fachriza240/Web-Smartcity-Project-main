@csrf

@php
    $isMember = old('submission_type', $hki->submission_type) === 'member';
    $whitelist = isset($dosens)
        ? $dosens->map(fn ($d) => ['value' => $d->fullname, 'nip' => $d->nip, 'prodi' => $d->prodi, 'fakultas' => $d->fakultas])->values()
        : collect();
@endphp

<div class="mb-3">
    <span class="form-label fw-semibold d-block">Tipe Pengusul <span class="text-danger">*</span></span>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="typeMember"
                   value="member" @checked($isMember) data-switch>
            <label class="form-check-label" for="typeMember">Member (Dosen terdaftar)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="typeNonMember"
                   value="non_member" @checked(! $isMember) data-switch>
            <label class="form-check-label" for="typeNonMember">Non-Member (isi manual)</label>
        </div>
    </div>
    @error('submission_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div id="memberField" class="mb-3" data-switch-panel="member" data-field @unless ($isMember) hidden @endunless>
    <label for="user_id" class="form-label">Pilih Dosen <span class="text-danger">*</span></label>
    <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" data-label="Dosen" data-rules="required">
        <option value="">-- Pilih Dosen --</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" @selected(old('user_id', $hki->user_id) == $dosen->id)>
                {{ $dosen->fullname }} {{ $dosen->nip ? '(' . $dosen->nip . ')' : '' }}
            </option>
        @endforeach
    </select>
    @error('user_id')<div class="invalid-feedback d-block" data-error-for="user_id">{{ $message }}</div>@enderror
</div>

<div id="nonMemberField" class="mb-3" data-switch-panel="non_member" data-field @if ($isMember) hidden @endif>
    <label for="recommended_by" class="form-label">Nama Pengusul</label>
    <input type="text" id="recommended_by" name="recommended_by" class="form-control @error('recommended_by') is-invalid @enderror"
           value="{{ old('recommended_by', $hki->recommended_by) }}" placeholder="Nama lengkap pengusul"
           data-label="Nama pengusul" data-rules="min:3|max:100|name">
    @error('recommended_by')<div class="invalid-feedback d-block" data-error-for="recommended_by">{{ $message }}</div>@enderror
</div>

<hr>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="nomor_sertifikat" class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
        <input type="text" id="nomor_sertifikat" name="nomor_sertifikat"
               class="form-control @error('nomor_sertifikat') is-invalid @enderror"
               value="{{ old('nomor_sertifikat', $hki->nomor_sertifikat) }}"
               placeholder="Contoh: EC00202412345" required
               data-label="Nomor sertifikat" data-rules="required|min:3|max:100|pattern" data-pattern="^[A-Za-z0-9./\-\s]+$"
               data-pattern-message="Nomor sertifikat hanya boleh berisi huruf, angka, titik, garis miring, dan tanda hubung.">
        @error('nomor_sertifikat')<div class="invalid-feedback d-block" data-error-for="nomor_sertifikat">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="tgl_terbit" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
        <input type="date" id="tgl_terbit" name="tgl_terbit"
               class="form-control @error('tgl_terbit') is-invalid @enderror"
               value="{{ old('tgl_terbit', $hki->tgl_terbit?->format('Y-m-d')) }}" required
               data-label="Tanggal terbit" data-rules="required">
        @error('tgl_terbit')<div class="invalid-feedback d-block" data-error-for="tgl_terbit">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3" data-field>
    <label for="judul_sertifikat" class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
    <input type="text" id="judul_sertifikat" name="judul_sertifikat"
           class="form-control @error('judul_sertifikat') is-invalid @enderror"
           value="{{ old('judul_sertifikat', $hki->judul_sertifikat) }}" required
           data-label="Judul sertifikat" data-rules="required|min:5|max:200|safe">
    <div class="form-text">Minimal 5 karakter, maksimal 200 karakter.</div>
    @error('judul_sertifikat')<div class="invalid-feedback d-block" data-error-for="judul_sertifikat">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="jenis_sertifikat" class="form-label">Jenis Sertifikat <span class="text-danger">*</span></label>
        <select id="jenis_sertifikat" name="jenis_sertifikat" class="form-select @error('jenis_sertifikat') is-invalid @enderror" required
                data-label="Jenis sertifikat" data-rules="required">
            <option value="">-- Pilih Jenis --</option>
            @foreach ($jenis as $j)
                <option value="{{ $j }}" @selected(old('jenis_sertifikat', $hki->jenis_sertifikat) === $j)>{{ $j }}</option>
            @endforeach
        </select>
        @error('jenis_sertifikat')<div class="invalid-feedback d-block" data-error-for="jenis_sertifikat">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3" data-field>
        <label for="pencipta" class="form-label">Pencipta atau Pemegang Hak <span class="text-danger">*</span></label>
        <input type="text" id="pencipta" name="pencipta" data-dosen-picker
               class="form-control @error('pencipta') is-invalid @enderror"
               value="{{ old('pencipta', $hki->pencipta) }}"
               placeholder="Nama pencipta atau pemegang hak" required
               data-label="Pencipta" data-rules="required|min:3|max:255|name">
        @error('pencipta')<div class="invalid-feedback d-block" data-error-for="pencipta">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="file_sertifikat" class="form-label">File Sertifikat</label>
        <input type="file" id="file_sertifikat" name="file_sertifikat"
               class="form-control @error('file_sertifikat') is-invalid @enderror"
               accept=".pdf,.doc,.docx"
               data-label="File sertifikat" data-rules="file:pdf,doc,docx|filesize:10240">
        <div class="form-text">Format PDF, DOC, atau DOCX, maksimal 10 MB.</div>
        @if ($hki->exists && $hki->file_sertifikat)
            <div class="mt-2 small">
                File saat ini:
                <a href="{{ asset('storage/' . $hki->file_sertifikat) }}" target="_blank" rel="noopener">Lihat</a>
                Unggah file baru untuk mengganti.
            </div>
        @endif
        @error('file_sertifikat')<div class="invalid-feedback d-block" data-error-for="file_sertifikat">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <div data-switch-panel="member" @unless ($isMember) hidden @endunless>
            <span class="form-label d-block">Status</span>
            <span class="badge bg-success">Publish</span>
            <div class="form-text">HKI milik dosen langsung dipublikasikan tanpa status Draft.</div>
        </div>
        <div data-switch-panel="non_member" data-field @if ($isMember) hidden @endif>
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" data-label="Status" data-rules="required">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $hki->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"
            data-confirm-submit="Apakah data yang diinputkan sudah benar?"
            data-confirm-second="Apakah Anda yakin ingin menyimpan perubahan?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.hki.index') }}" class="btn btn-light">Batal</a>
</div>

<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    window.dosenWhitelist = @json($whitelist);
</script>
<script src="{{ asset('js/dosen-picker.js') }}"></script>