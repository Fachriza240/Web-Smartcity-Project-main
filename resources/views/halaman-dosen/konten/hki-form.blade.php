@php
    $isCreate = $mode === 'create';
    $whitelist = isset($dosens)
        ? $dosens->map(fn ($d) => ['value' => $d->fullname, 'nip' => $d->nip, 'prodi' => $d->prodi, 'fakultas' => $d->fakultas])->values()
        : collect();
@endphp

<x-halaman-dosen.konten-layout active="hki" :title="$isCreate ? 'Tambah HKI' : 'Edit HKI'">
    <div class="dsn-card dsn-card--padded">
        <div class="dsn-form-head">
            <a href="{{ route('dosen.hki.index') }}" class="dsn-icon-btn" aria-label="Kembali ke daftar HKI">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
            <h2>{{ $isCreate ? 'Tambah HKI Baru' : 'Edit HKI' }}</h2>
        </div>

        <div class="dsn-note">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span>HKI yang Anda tambahkan masuk sebagai <strong>Draft</strong> dan akan tampil di halaman publik setelah direview admin.</span>
        </div>

        @if ($errors->any())
            <div class="dsn-alert" role="alert">
                <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                <span>Periksa kembali isian yang ditandai merah.</span>
            </div>
        @endif

        <form action="{{ $isCreate ? route('dosen.hki.store') : route('dosen.hki.update', $hki) }}"
            method="POST" enctype="multipart/form-data" novalidate data-validate>
            @csrf
            @unless ($isCreate)
                @method('PUT')
            @endunless

            <div class="row g-3">
                <div class="col-md-6" data-field>
                    <label for="nomor_sertifikat" class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                    <input type="text" id="nomor_sertifikat" name="nomor_sertifikat" class="form-control @error('nomor_sertifikat') is-invalid @enderror"
                        value="{{ old('nomor_sertifikat', $hki->nomor_sertifikat) }}" placeholder="Contoh: EC00202412345"
                        data-label="Nomor sertifikat" data-rules="required|min:3|max:100|pattern" data-pattern="^[A-Za-z0-9./\-\s]+$"
                        data-pattern-message="Nomor sertifikat hanya boleh berisi huruf, angka, titik, garis miring, dan tanda hubung.">
                    <div class="field-error" data-error-for="nomor_sertifikat">@error('nomor_sertifikat'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6" data-field>
                    <label for="tgl_terbit" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                    <input type="date" id="tgl_terbit" name="tgl_terbit" class="form-control @error('tgl_terbit') is-invalid @enderror"
                        value="{{ old('tgl_terbit', $hki->tgl_terbit?->format('Y-m-d')) }}" data-label="Tanggal terbit" data-rules="required">
                    <div class="field-error" data-error-for="tgl_terbit">@error('tgl_terbit'){{ $message }}@enderror</div>
                </div>

                <div class="col-12" data-field>
                    <label for="judul_sertifikat" class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
                    <input type="text" id="judul_sertifikat" name="judul_sertifikat" class="form-control @error('judul_sertifikat') is-invalid @enderror"
                        value="{{ old('judul_sertifikat', $hki->judul_sertifikat) }}" placeholder="Judul lengkap HKI"
                        data-label="Judul sertifikat" data-rules="required|min:5|max:200|safe">
                    <div class="field-error" data-error-for="judul_sertifikat">@error('judul_sertifikat'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field>
                    <label for="jenis_sertifikat" class="form-label">Jenis Sertifikat <span class="text-danger">*</span></label>
                    <select id="jenis_sertifikat" name="jenis_sertifikat" class="form-select @error('jenis_sertifikat') is-invalid @enderror" data-label="Jenis sertifikat" data-rules="required">
                        <option value="">Pilih jenis</option>
                        @foreach ($jenis as $j)
                            <option value="{{ $j }}" @selected(old('jenis_sertifikat', $hki->jenis_sertifikat) === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" data-error-for="jenis_sertifikat">@error('jenis_sertifikat'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6" data-field>
                    <label for="pencipta" class="form-label">Pencipta atau Pemegang Hak <span class="text-danger">*</span></label>
                    <input type="text" id="pencipta" name="pencipta" class="form-control @error('pencipta') is-invalid @enderror"
                        value="{{ old('pencipta', $hki->pencipta) }}" placeholder="Ketik nama, pisahkan dengan koma"
                        data-label="Pencipta" data-rules="required|min:3|max:255|name" data-dosen-picker>
                    <div class="form-text">Pilih dari daftar dosen terdaftar atau ketik nama secara manual.</div>
                    <div class="field-error" data-error-for="pencipta">@error('pencipta'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6">
                    <label for="file_sertifikat" class="form-label">File Sertifikat</label>
                    <input type="file" id="file_sertifikat" name="file_sertifikat" class="form-control @error('file_sertifikat') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">
                        PDF atau gambar, maksimal 10 MB.
                        @if ($hki->exists && $hki->file_sertifikat)
                            File saat ini: <a href="{{ asset('storage/'.$hki->file_sertifikat) }}" target="_blank" rel="noopener">lihat file</a>. Unggah file baru untuk mengganti.
                        @endif
                    </div>
                    <div class="field-error">@error('file_sertifikat'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(old('status', $hki->status) === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Status Publish perlu disetujui admin.</div>
                </div>
            </div>

            <div class="dsn-form-actions">
                <button type="submit" class="sc-btn sc-btn--primary">
                    <i class="bi bi-save" aria-hidden="true"></i> {{ $isCreate ? 'Simpan HKI' : 'Perbarui HKI' }}
                </button>
                <a href="{{ route('dosen.hki.index') }}" class="sc-btn sc-btn--ghost">Batal</a>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script>
        window.dosenWhitelist = @json($whitelist);
    </script>
    <script src="{{ asset('js/dosen-picker.js') }}"></script>
</x-halaman-dosen.konten-layout>
