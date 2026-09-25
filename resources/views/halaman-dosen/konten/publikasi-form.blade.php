@php
    $isCreate = $mode === 'create';
@endphp

<x-halaman-dosen.konten-layout active="publikasi" :title="$isCreate ? 'Tambah Publikasi' : 'Edit Publikasi'">
    <div class="dsn-card dsn-card--padded">
        <div class="dsn-form-head">
            <a href="{{ route('dosen.publikasi.index') }}" class="dsn-icon-btn" aria-label="Kembali ke daftar publikasi">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
            <h2>{{ $isCreate ? 'Tambah Publikasi Baru' : 'Edit Publikasi' }}</h2>
        </div>

        <div class="dsn-note">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span>Publikasi yang Anda tambahkan masuk sebagai <strong>Draft</strong> dan akan tampil di halaman publik setelah direview admin.</span>
        </div>

        @if ($errors->any())
            <div class="dsn-alert" role="alert">
                <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                <span>Periksa kembali isian yang ditandai merah.</span>
            </div>
        @endif

        <form action="{{ $isCreate ? route('dosen.publikasi.store') : route('dosen.publikasi.update', $publication) }}"
            method="POST" enctype="multipart/form-data" novalidate data-validate>
            @csrf
            @unless ($isCreate)
                @method('PUT')
            @endunless

            <div class="row g-3">
                <div class="col-lg-9" data-field>
                    <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
                        value="{{ old('judul', $publication->judul) }}" placeholder="Judul lengkap publikasi"
                        data-label="Judul" data-rules="required|min:5|max:200|safe">
                    <div class="field-error" data-error-for="judul">@error('judul'){{ $message }}@enderror</div>
                </div>
                <div class="col-lg-3" data-field>
                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                        value="{{ old('tahun', $publication->tahun ?? date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}"
                        data-label="Tahun" data-rules="required|range:1900,{{ date('Y') + 1 }}">
                    <div class="field-error" data-error-for="tahun">@error('tahun'){{ $message }}@enderror</div>
                </div>

                <div class="col-12" data-field>
                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                    <input type="text" id="penulis" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                        value="{{ old('penulis', $publication->penulis) }}" placeholder="Nama penulis, pisahkan dengan koma"
                        data-label="Penulis" data-rules="required|min:3|max:255|name">
                    <div class="field-error" data-error-for="penulis">@error('penulis'){{ $message }}@enderror</div>
                </div>

                <div class="col-12" data-field>
                    <label for="abstrak" class="form-label">Abstrak <span class="text-danger">*</span></label>
                    <textarea id="abstrak" name="abstrak" rows="6" class="form-control @error('abstrak') is-invalid @enderror"
                        placeholder="Ringkasan isi publikasi" data-label="Abstrak" data-rules="required|min:10|max:10000|safe">{{ old('abstrak', $publication->abstrak) }}</textarea>
                    <div class="field-error" data-error-for="abstrak">@error('abstrak'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6" data-field>
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select id="kategori" name="kategori" class="form-select @error('kategori') is-invalid @enderror" data-label="Kategori" data-rules="required">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('kategori', $publication->kategori) === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <div class="field-error" data-error-for="kategori">@error('kategori'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(old('status', $publication->status) === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Status Publish berlaku setelah disetujui admin.</div>
                </div>

                <div class="col-md-6" data-field>
                    <label for="penerbit" class="form-label">Penerbit atau Jurnal</label>
                    <input type="text" id="penerbit" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                        value="{{ old('penerbit', $publication->penerbit) }}" placeholder="Nama jurnal atau penerbit"
                        data-label="Penerbit" data-rules="min:2|max:200|safe">
                    <div class="field-error" data-error-for="penerbit">@error('penerbit'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6" data-field>
                    <label for="doi" class="form-label">DOI</label>
                    <input type="text" id="doi" name="doi" class="form-control @error('doi') is-invalid @enderror"
                        value="{{ old('doi', $publication->doi) }}" placeholder="10.xxxx/xxxxx"
                        data-label="DOI" data-rules="max:255|pattern" data-pattern="^[A-Za-z0-9./:_()\-]+$"
                        data-pattern-message="DOI hanya boleh berisi huruf, angka, titik, garis miring, titik dua, garis bawah, kurung, dan tanda hubung.">
                    <div class="field-error" data-error-for="doi">@error('doi'){{ $message }}@enderror</div>
                </div>

                <div class="col-md-6">
                    <label for="pdf" class="form-label">File PDF @if ($isCreate)<span class="text-danger">*</span>@endif</label>
                    <input type="file" id="pdf" name="pdf" class="form-control @error('pdf') is-invalid @enderror" accept="application/pdf">
                    <div class="form-text">
                        Format PDF, maksimal 20 MB.
                        @if ($publication->pdf_path)
                            File saat ini: <a href="{{ asset('storage/'.$publication->pdf_path) }}" target="_blank" rel="noopener">lihat PDF</a>
                        @endif
                    </div>
                    <div class="field-error">@error('pdf'){{ $message }}@enderror</div>
                </div>
                <div class="col-md-6">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" id="thumbnail" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                    <div class="form-text">Gambar JPG, PNG, atau WEBP, maksimal 4 MB.</div>
                    @if ($publication->thumbnail_path)
                        <img src="{{ asset('storage/'.$publication->thumbnail_path) }}" alt="Thumbnail saat ini" class="dsn-thumb-preview">
                    @endif
                    <div class="field-error">@error('thumbnail'){{ $message }}@enderror</div>
                </div>
            </div>

            <div class="dsn-form-actions">
                <button type="submit" class="sc-btn sc-btn--primary">
                    <i class="bi bi-save" aria-hidden="true"></i> {{ $isCreate ? 'Simpan Publikasi' : 'Perbarui Publikasi' }}
                </button>
                <a href="{{ route('dosen.publikasi.index') }}" class="sc-btn sc-btn--ghost">Batal</a>
            </div>
        </form>
    </div>
</x-halaman-dosen.konten-layout>
