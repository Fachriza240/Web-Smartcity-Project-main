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

{{-- Tagify CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    /* Gaya dasar input Tagify */
    .tagify {
        --tags-border-color: #e2e8f0;
        --tags-hover-border-color: #cbd5e1;
        --tags-focus-border-color: #4c8dc9;
        --tag-bg: #eff6ff;
        --tag-hover: #dbeafe;
        --tag-text-color: #1e3a8a;
        --tag-pad: 0.375rem 0.75rem;
        --tag-inset-shadow-size: 1.1em;
        --tag-remove-btn-color: #1e3a8a;
        --tag-remove-btn-bg--hover: #bfdbfe;
        border-radius: 8px;
        padding: 4px;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-size: 14px;
    }
    .tagify.tagify--focus {
        box-shadow: 0 0 0 0.25rem rgba(76, 141, 201, 0.25);
        border-color: #4c8dc9;
    }
    /* Custom Tag (Pill) */
    .tagify__tag {
        border-radius: 999px;
        font-weight: 500;
    }
    .tagify__tag>div::before {
        border-radius: 999px;
    }
    /* Dropdown styling */
    .dosen-dropdown {
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        padding: 0;
        overflow: hidden;
        margin-top: 5px;
    }
    .dosen-dropdown .tagify__dropdown__wrapper {
        background: #fff;
        border: none;
    }
    .dosen-dropdown .tagify__dropdown__item {
        padding: 12px 16px;
        margin: 0;
        border-radius: 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .dosen-dropdown .tagify__dropdown__item:last-child {
        border-bottom: none;
    }
    .dosen-dropdown .tagify__dropdown__item--active {
        background-color: #f8fafc;
        color: inherit;
    }
    /* Style untuk info dosen di dropdown */
    .dosen-info-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .dosen-info-meta {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .dosen-info-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.querySelector('input[name=pencipta]');
        
        @php
            $whitelistData = isset($dosens) ? $dosens->map(function($d) {
                return [
                    'value'    => $d->fullname,
                    'nip'      => $d->nip,
                    'prodi'    => $d->prodi,
                    'fakultas' => $d->fakultas
                ];
            })->toArray() : [];
        @endphp
        var whitelist = @json($whitelistData);

        var tagify = new Tagify(input, {
            whitelist: whitelist,
            enforceWhitelist: false,
            delimiters: ",",
            originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(', '),
            dropdown: {
                maxItems: 20,
                classname: "dosen-dropdown",
                enabled: 0,
                closeOnSelect: false,
                searchKeys: ['value', 'nip', 'prodi', 'fakultas']
            },
            templates: {
                dropdownItem: function(item) {
                    var icon = '<i class="bi bi-person-badge text-primary"></i>';
                    var title = `<div class="dosen-info-title">${icon} ${item.value}</div>`;
                    
                    var metaHtml = '';
                    if (item.nip || item.prodi || item.fakultas) {
                        metaHtml += '<div class="dosen-info-meta">';
                        if (item.nip) metaHtml += `<span><i class="bi bi-credit-card-2-front"></i> ${item.nip}</span>`;
                        if (item.prodi) metaHtml += `<span><i class="bi bi-mortarboard"></i> ${item.prodi}</span>`;
                        if (item.fakultas) metaHtml += `<span><i class="bi bi-building"></i> ${item.fakultas}</span>`;
                        metaHtml += '</div>';
                    } else {
                        metaHtml += '<div class="dosen-info-meta"><span><i class="bi bi-pencil-square"></i> Input Manual (Tidak terdaftar)</span></div>';
                    }

                    return `<div class='tagify__dropdown__item' ${this.getAttributes(item)}>
                                ${title}
                                ${metaHtml}
                            </div>`;
                }
            }
        });
    });
</script>
