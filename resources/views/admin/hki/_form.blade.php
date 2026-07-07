@csrf

{{-- Submission type --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Tipe Pengusul <span class="text-danger">*</span></label>
    <div class="d-flex gap-3">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="typeMember"
                   value="member" @checked(old('submission_type', $hki->submission_type) === 'member')
                   onchange="toggleSubmitter(this.value)">
            <label class="form-check-label" for="typeMember">Member (Dosen terdaftar)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="submission_type" id="typeNonMember"
                   value="non_member" @checked(old('submission_type', $hki->submission_type) !== 'member')
                   onchange="toggleSubmitter(this.value)">
            <label class="form-check-label" for="typeNonMember">Non-Member (isi manual)</label>
        </div>
    </div>
</div>

{{-- Member: pilih dosen --}}
<div id="memberField" class="mb-3" style="{{ old('submission_type', $hki->submission_type) !== 'member' ? 'display:none' : '' }}">
    <label class="form-label">Pilih Dosen <span class="text-danger">*</span></label>
    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
        <option value="">-- Pilih Dosen --</option>
        @foreach($dosens as $dosen)
            <option value="{{ $dosen->id }}" @selected(old('user_id', $hki->user_id) == $dosen->id)>
                {{ $dosen->fullname }} {{ $dosen->nip ? '(' . $dosen->nip . ')' : '' }}
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Non-member: isi manual --}}
<div id="nonMemberField" class="mb-3" style="{{ old('submission_type', $hki->submission_type) === 'member' ? 'display:none' : '' }}">
    <label class="form-label">Nama Pengusul</label>
    <input type="text" name="recommended_by" class="form-control @error('recommended_by') is-invalid @enderror"
           value="{{ old('recommended_by', $hki->recommended_by) }}" placeholder="Nama lengkap pengusul">
    @error('recommended_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
        <input type="text" name="nomor_sertifikat"
               class="form-control @error('nomor_sertifikat') is-invalid @enderror"
               value="{{ old('nomor_sertifikat', $hki->nomor_sertifikat) }}"
               placeholder="Contoh: EC00202412345" required>
        @error('nomor_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
        <input type="date" name="tgl_terbit"
               class="form-control @error('tgl_terbit') is-invalid @enderror"
               value="{{ old('tgl_terbit', $hki->tgl_terbit?->format('Y-m-d')) }}" required>
        @error('tgl_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
    <input type="text" name="judul_sertifikat"
           class="form-control @error('judul_sertifikat') is-invalid @enderror"
           value="{{ old('judul_sertifikat', $hki->judul_sertifikat) }}" required>
    @error('judul_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jenis Sertifikat <span class="text-danger">*</span></label>
        <select name="jenis_sertifikat" class="form-select @error('jenis_sertifikat') is-invalid @enderror" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenis as $j)
                <option value="{{ $j }}" @selected(old('jenis_sertifikat', $hki->jenis_sertifikat) === $j)>{{ $j }}</option>
            @endforeach
        </select>
        @error('jenis_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Pencipta / Pemegang <span class="text-danger">*</span></label>
        <input type="text" name="pencipta"
               class="form-control @error('pencipta') is-invalid @enderror"
               value="{{ old('pencipta', $hki->pencipta) }}"
               placeholder="Nama pencipta / pemegang hak" required>
        @error('pencipta') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">File Sertifikat</label>
        <input type="file" name="file_sertifikat"
               class="form-control @error('file_sertifikat') is-invalid @enderror"
               accept=".pdf,.jpg,.jpeg,.png">
        <div class="form-text">PDF / Gambar, maks 10MB.</div>
        @if($hki->exists && $hki->file_sertifikat)
            <div class="mt-2 small">
                File saat ini:
                <a href="{{ asset('storage/' . $hki->file_sertifikat) }}" target="_blank">Lihat</a>
                — upload baru untuk mengganti.
            </div>
        @endif
        @error('file_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $hki->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('admin.hki.index') }}" class="btn btn-light">Batal</a>
</div>

<script>
function toggleSubmitter(val) {
    document.getElementById('memberField').style.display    = val === 'member'     ? '' : 'none';
    document.getElementById('nonMemberField').style.display = val === 'non_member' ? '' : 'none';
}
</script>
