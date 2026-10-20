@csrf

@use('App\Models\AktivitasDosen')

@php
    $berkelanjutan = session()->hasOldInput() ? (bool) old('berkelanjutan') : (bool) $aktivitas->berkelanjutan;
    $konfigJs = [
        'sub' => [
            AktivitasDosen::JENIS_PRODUK => AktivitasDosen::subJenisList(AktivitasDosen::JENIS_PRODUK),
            AktivitasDosen::JENIS_KOLABORASI => AktivitasDosen::subJenisList(AktivitasDosen::JENIS_KOLABORASI),
            AktivitasDosen::JENIS_KEPEMIMPINAN => AktivitasDosen::subJenisList(AktivitasDosen::JENIS_KEPEMIMPINAN),
        ],
        'subLabel' => [
            AktivitasDosen::JENIS_PRODUK => 'Jenis Produk',
            AktivitasDosen::JENIS_KOLABORASI => 'Jenis Mitra',
            AktivitasDosen::JENIS_KEPEMIMPINAN => 'Peran Kepemimpinan',
        ],
        'mitraWajib' => [AktivitasDosen::JENIS_KOLABORASI],
        'terpilih' => old('sub_jenis', $aktivitas->sub_jenis),
    ];
@endphp

<div class="row">
    <div class="col-md-6 mb-3" data-field>
        <label for="user_id" class="form-label">Dosen <span class="text-danger">*</span></label>
        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror"
                data-label="Dosen" data-rules="required">
            <option value="">-- Pilih Dosen --</option>
            @foreach ($dosens as $dosen)
                <option value="{{ $dosen->id }}" @selected((int) old('user_id', $aktivitas->user_id) === $dosen->id)>
                    {{ $dosen->fullname }}{{ $dosen->prodi ? ' - ' . $dosen->prodi : '' }}
                </option>
            @endforeach
        </select>
        @error('user_id')<div class="invalid-feedback d-block" data-error-for="user_id">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field>
        <label for="jenis" class="form-label">Jenis Aktivitas <span class="text-danger">*</span></label>
        <select id="jenis" name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                data-label="Jenis aktivitas" data-rules="required">
            <option value="">Pilih jenis</option>
            @foreach (AktivitasDosen::jenisList() as $key => $label)
                <option value="{{ $key }}" @selected(old('jenis', $aktivitas->jenis) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('jenis')<div class="invalid-feedback d-block" data-error-for="jenis">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-9 mb-3" data-field>
        <label for="judul" class="form-label">Judul atau Nama Kegiatan <span class="text-danger">*</span></label>
        <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $aktivitas->judul) }}" placeholder="Contoh: Sistem Pemantauan Kualitas Udara Berbasis IoT"
               data-label="Judul" data-rules="required|min:5|max:255|safe">
        @error('judul')<div class="invalid-feedback d-block" data-error-for="judul">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 mb-3" data-field>
        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
               value="{{ old('tahun', $aktivitas->tahun) }}" min="2000" max="{{ date('Y') }}"
               data-label="Tahun" data-rules="required|range:2000,{{ date('Y') }}">
        @error('tahun')<div class="invalid-feedback d-block" data-error-for="tahun">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field data-show-for="penelitian,abdimas" hidden>
        <label for="skala" class="form-label">Skala <span class="text-danger">*</span></label>
        <select id="skala" name="skala" class="form-select @error('skala') is-invalid @enderror"
                data-label="Skala" data-rules="required">
            <option value="">Pilih skala</option>
            @foreach (AktivitasDosen::skalaList() as $key => $label)
                <option value="{{ $key }}" @selected(old('skala', $aktivitas->skala) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('skala')<div class="invalid-feedback d-block" data-error-for="skala">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field data-show-for="penelitian,abdimas,produk" hidden>
        <label for="peran" class="form-label">Peran Dosen <span class="text-danger">*</span></label>
        <select id="peran" name="peran" class="form-select @error('peran') is-invalid @enderror"
                data-label="Peran" data-rules="required">
            <option value="">Pilih peran</option>
            @foreach (AktivitasDosen::peranList() as $key => $label)
                <option value="{{ $key }}" @selected(old('peran', $aktivitas->peran) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('peran')<div class="invalid-feedback d-block" data-error-for="peran">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field data-show-for="produk,kolaborasi,kepemimpinan" hidden>
        <label for="sub_jenis" class="form-label"><span data-sub-label>Pilihan</span> <span class="text-danger">*</span></label>
        <select id="sub_jenis" name="sub_jenis" class="form-select @error('sub_jenis') is-invalid @enderror"
                data-label="Pilihan jenis" data-rules="required">
            <option value="">Pilih</option>
        </select>
        @error('sub_jenis')<div class="invalid-feedback d-block" data-error-for="sub_jenis">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field data-show-for="penelitian,abdimas,produk,kolaborasi" hidden>
        <label for="mitra" class="form-label">Mitra atau Sumber Dana <span class="text-danger" data-mitra-mark hidden>*</span></label>
        <input type="text" id="mitra" name="mitra" class="form-control @error('mitra') is-invalid @enderror"
               value="{{ old('mitra', $aktivitas->mitra) }}" placeholder="Contoh: Dinas Perhubungan Kota Bandung"
               data-label="Mitra" data-rules="min:2|max:150|safe">
        @error('mitra')<div class="invalid-feedback d-block" data-error-for="mitra">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-field>
        <label for="bidang_smart_city" class="form-label">Bidang Smart City</label>
        <select id="bidang_smart_city" name="bidang_smart_city" class="form-select @error('bidang_smart_city') is-invalid @enderror"
                data-label="Bidang Smart City">
            <option value="">Tidak ditentukan</option>
            @foreach (AktivitasDosen::bidangSmartCityList() as $key => $label)
                <option value="{{ $key }}" @selected(old('bidang_smart_city', $aktivitas->bidang_smart_city) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="form-text">Opsional. Jika dikosongkan, sistem mengenali bidang dari kata kunci pada judul.</div>
        @error('bidang_smart_city')<div class="invalid-feedback d-block" data-error-for="bidang_smart_city">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3" data-show-for="abdimas" hidden>
        <span class="form-label d-block">Keberlanjutan</span>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="berkelanjutan" name="berkelanjutan" value="1" @checked($berkelanjutan)>
            <label class="form-check-label" for="berkelanjutan">Program berkelanjutan (berlanjut lebih dari satu periode)</label>
        </div>
    </div>

    <div class="col-md-6 mb-3" data-field>
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                data-label="Status" data-rules="required">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $aktivitas->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <div class="form-text">Hanya aktivitas berstatus Publish yang dihitung dalam penilaian.</div>
        @error('status')<div class="invalid-feedback d-block" data-error-for="status">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3" data-field>
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                  data-label="Keterangan" data-rules="max:1000|safe">{{ old('keterangan', $aktivitas->keterangan) }}</textarea>
        @error('keterangan')<div class="invalid-feedback d-block" data-error-for="keterangan">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-1">
    <button type="submit" class="btn btn-success"
            data-confirm-submit="Apakah data yang diinputkan sudah benar?"
            data-confirm-second="Apakah Anda yakin ingin menyimpan perubahan?">
        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
    </button>
    <a href="{{ route('admin.aktivitas-dosen.index') }}" class="btn btn-light">Batal</a>
</div>

<script>
    window.aktivitasConfig = @json($konfigJs);
</script>
<script src="{{ asset('js/aktivitas-form.js') }}"></script>