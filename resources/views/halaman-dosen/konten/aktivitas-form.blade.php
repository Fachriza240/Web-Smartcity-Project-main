@use('App\Models\AktivitasDosen')

@php
    $isCreate = $mode === 'create';
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

<x-halaman-dosen.konten-layout active="aktivitas" :title="$isCreate ? 'Tambah Aktivitas' : 'Edit Aktivitas'">
    <div class="dsn-card dsn-card--padded">
        <div class="dsn-form-head">
            <a href="{{ route('dosen.aktivitas.index') }}" class="dsn-icon-btn" aria-label="Kembali ke daftar aktivitas">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
            <h2>{{ $isCreate ? 'Tambah Aktivitas Baru' : 'Edit Aktivitas' }}</h2>
        </div>

        <div class="dsn-note">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span>
                Aktivitas dipakai dalam penilaian potensi dosen (DSS). Isi data sesuai kenyataan karena Admin dapat memeriksa dan mengubahnya.
                Hanya aktivitas tahun {{ $tahunMulai }} sampai {{ date('Y') }} yang dihitung.
            </span>
        </div>

        @if ($errors->any())
            <div class="dsn-alert" role="alert">
                <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                <span>Periksa kembali isian yang ditandai merah.</span>
            </div>
        @endif

        <form action="{{ $isCreate ? route('dosen.aktivitas.store') : route('dosen.aktivitas.update', $aktivitas) }}"
            method="POST" novalidate data-validate data-aktivitas-form>
            @csrf
            @unless ($isCreate)
                @method('PUT')
            @endunless

            <div class="row g-3">
                <div class="col-md-6" data-field>
                    <label for="jenis" class="form-label">Jenis Aktivitas <span class="text-danger">*</span></label>
                    <select id="jenis" name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                        data-label="Jenis aktivitas" data-rules="required">
                        <option value="">Pilih jenis</option>
                        @foreach (AktivitasDosen::jenisList() as $key => $label)
                            <option value="{{ $key }}" @selected(old('jenis', $aktivitas->jenis) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" data-error-for="jenis">@error('jenis'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field>
                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                        value="{{ old('tahun', $aktivitas->tahun) }}" min="2000" max="{{ date('Y') }}"
                        data-label="Tahun" data-rules="required|range:2000,{{ date('Y') }}">
                    <div class="field-error" data-error-for="tahun">@error('tahun'){{ $message }}@enderror</div>
                </div>

                <div class="col-12" data-field>
                    <label for="judul" class="form-label">Judul atau Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
                        value="{{ old('judul', $aktivitas->judul) }}" placeholder="Contoh: Sistem Pemantauan Kualitas Udara Berbasis IoT"
                        data-label="Judul" data-rules="required|min:5|max:255|safe">
                    <div class="field-error" data-error-for="judul">@error('judul'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field data-show-for="penelitian,abdimas" hidden>
                    <label for="skala" class="form-label">Skala <span class="text-danger">*</span></label>
                    <select id="skala" name="skala" class="form-select @error('skala') is-invalid @enderror"
                        data-label="Skala" data-rules="required">
                        <option value="">Pilih skala</option>
                        @foreach (AktivitasDosen::skalaList() as $key => $label)
                            <option value="{{ $key }}" @selected(old('skala', $aktivitas->skala) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" data-error-for="skala">@error('skala'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field data-show-for="penelitian,abdimas,produk" hidden>
                    <label for="peran" class="form-label">Peran Anda <span class="text-danger">*</span></label>
                    <select id="peran" name="peran" class="form-select @error('peran') is-invalid @enderror"
                        data-label="Peran" data-rules="required">
                        <option value="">Pilih peran</option>
                        @foreach (AktivitasDosen::peranList() as $key => $label)
                            <option value="{{ $key }}" @selected(old('peran', $aktivitas->peran) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" data-error-for="peran">@error('peran'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field data-show-for="produk,kolaborasi,kepemimpinan" hidden>
                    <label for="sub_jenis" class="form-label"><span data-sub-label>Pilihan</span> <span class="text-danger">*</span></label>
                    <select id="sub_jenis" name="sub_jenis" class="form-select @error('sub_jenis') is-invalid @enderror"
                        data-label="Pilihan jenis" data-rules="required">
                        <option value="">Pilih</option>
                    </select>
                    <div class="field-error" data-error-for="sub_jenis">@error('sub_jenis'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field data-show-for="penelitian,abdimas,produk,kolaborasi" hidden>
                    <label for="mitra" class="form-label">Mitra atau Sumber Dana <span class="text-danger" data-mitra-mark hidden>*</span></label>
                    <input type="text" id="mitra" name="mitra" class="form-control @error('mitra') is-invalid @enderror"
                        value="{{ old('mitra', $aktivitas->mitra) }}" placeholder="Contoh: Dinas Perhubungan Kota Bandung"
                        data-label="Mitra" data-rules="min:2|max:150|safe">
                    <div class="field-error" data-error-for="mitra">@error('mitra'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field>
                    <label for="bidang_smart_city" class="form-label">Bidang Smart City</label>
                    <select id="bidang_smart_city" name="bidang_smart_city" class="form-select @error('bidang_smart_city') is-invalid @enderror"
                        data-label="Bidang Smart City">
                        <option value="">Tidak ditentukan</option>
                        @foreach (AktivitasDosen::bidangSmartCityList() as $key => $label)
                            <option value="{{ $key }}" @selected(old('bidang_smart_city', $aktivitas->bidang_smart_city) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Opsional. Jika dikosongkan, sistem mengenali bidang dari kata kunci pada judul.</div>
                    <div class="field-error" data-error-for="bidang_smart_city">@error('bidang_smart_city'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-show-for="abdimas" hidden>
                    <span class="form-label d-block">Keberlanjutan</span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="berkelanjutan" name="berkelanjutan" value="1" @checked($berkelanjutan)>
                        <label class="form-check-label" for="berkelanjutan">Program ini berkelanjutan (berlanjut lebih dari satu periode)</label>
                    </div>
                </div>

                <div class="col-12" data-field>
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                        data-label="Keterangan" data-rules="max:1000|safe">{{ old('keterangan', $aktivitas->keterangan) }}</textarea>
                    <div class="field-error" data-error-for="keterangan">@error('keterangan'){{ $message }}@enderror</div>
                </div>
            </div>

            <div class="dsn-form-actions">
                <button type="submit" class="sc-btn sc-btn--primary" data-confirm-submit="Apakah Anda yakin ingin menyimpan data aktivitas ini?">
                    <i class="bi bi-save" aria-hidden="true"></i> {{ $isCreate ? 'Simpan Aktivitas' : 'Perbarui Aktivitas' }}
                </button>
                <a href="{{ route('dosen.aktivitas.index') }}" class="sc-btn sc-btn--ghost">Batal</a>
            </div>
        </form>
    </div>

    <script>
        window.aktivitasConfig = @json($konfigJs);
    </script>
    <script src="{{ asset('js/aktivitas-form.js') }}"></script>
</x-halaman-dosen.konten-layout>