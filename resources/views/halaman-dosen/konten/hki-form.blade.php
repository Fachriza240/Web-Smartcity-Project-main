<x-halaman-dosen.konten-layout active="hki"
    title="{{ $mode === 'create' ? 'Tambah HKI' : 'Edit HKI' }}">

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;
                box-shadow:0 2px 8px rgba(0,0,0,.05);padding:28px 30px;">

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;
                    padding-bottom:16px;border-bottom:2px solid #f1f5f9;">
            <a href="{{ route('dosen.hki.index') }}"
               style="color:#94a3b8;font-size:18px;text-decoration:none;"
               onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#94a3b8'">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0;">
                {{ $mode === 'create' ? 'Tambah HKI Baru' : 'Edit HKI' }}
            </h3>
        </div>

        {{-- Info note --}}
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;
                    padding:12px 16px;font-size:13px;color:#1d4ed8;
                    display:flex;align-items:flex-start;gap:8px;margin-bottom:24px;">
            <i class="bi bi-info-circle-fill" style="margin-top:1px;flex-shrink:0;"></i>
            <span>
                HKI yang Anda tambahkan akan masuk status <strong>Draft</strong> dan perlu
                direview oleh admin sebelum tampil di halaman publik.
            </span>
        </div>

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;
                        padding:12px 16px;font-size:13px;color:#dc2626;
                        display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ $mode === 'create' ? route('dosen.hki.store') : route('dosen.hki.update', $hki) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            {{-- Nomor Sertifikat + Tanggal Terbit --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_sertifikat"
                           class="form-control @error('nomor_sertifikat') is-invalid @enderror"
                           value="{{ old('nomor_sertifikat', $hki->nomor_sertifikat) }}"
                           placeholder="Contoh: EC00202412345" required>
                    @error('nomor_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_terbit"
                           class="form-control @error('tgl_terbit') is-invalid @enderror"
                           value="{{ old('tgl_terbit', $hki->tgl_terbit?->format('Y-m-d')) }}" required>
                    @error('tgl_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Judul Sertifikat --}}
            <div class="mb-3">
                <label class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
                <input type="text" name="judul_sertifikat"
                       class="form-control @error('judul_sertifikat') is-invalid @enderror"
                       value="{{ old('judul_sertifikat', $hki->judul_sertifikat) }}" required
                       placeholder="Judul lengkap HKI">
                @error('judul_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Jenis Sertifikat + Pencipta --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Jenis Sertifikat <span class="text-danger">*</span></label>
                    <select name="jenis_sertifikat"
                            class="form-select @error('jenis_sertifikat') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j }}"
                                    @selected(old('jenis_sertifikat', $hki->jenis_sertifikat) === $j)>
                                {{ $j }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Pencipta / Pemegang Hak <span class="text-danger">*</span></label>
                    <input type="text" name="pencipta"
                           class="form-control @error('pencipta') is-invalid @enderror"
                           value="{{ old('pencipta', $hki->pencipta) }}" required
                           placeholder="Nama pencipta / pemegang hak">
                    @error('pencipta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- File Sertifikat + Status --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div>
                    <label class="form-label">File Sertifikat</label>
                    <input type="file" name="file_sertifikat"
                        class="form-control @error('file_sertifikat') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">PDF / Gambar, maks 10MB</div>
                    @if($hki->exists && $hki->file_sertifikat)
                        <div class="small mt-1">
                            File saat ini:
                            <a href="{{ asset('storage/'.$hki->file_sertifikat) }}" target="_blank">
                                Lihat file
                            </a>
                            — upload baru untuk mengganti.
                        </div>
                    @endif
                    @error('file_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <div>
                        @php $currentStatus = $hki->status ?? \App\Models\Hki::STATUS_DRAFT; @endphp
                        <span style="display:inline-block;font-size:13px;font-weight:700;padding:7px 14px;
                                    border-radius:20px;
                                    {{ $currentStatus === 'Publish'
                                        ? 'background:#dcfce7;color:#16a34a;'
                                        : 'background:#f1f5f9;color:#64748b;' }}">
                            {{ $currentStatus }}
                        </span>
                    </div>
                    <div class="form-text">
                        Status tidak bisa diubah manual. HKI baru/hasil edit otomatis
                        berstatus <strong>Draft</strong> dan menunggu review admin.
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>
                    {{ $mode === 'create' ? 'Simpan HKI' : 'Perbarui HKI' }}
                </button>
                <a href="{{ route('dosen.hki.index') }}" class="btn btn-light">Batal</a>
            </div>

        </form>
    </div>

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
                    searchKeys: ['value', 'nip', 'prodi', 'fakultas'] // Support pencarian prodi & fakultas
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
</x-halaman-dosen.konten-layout>
