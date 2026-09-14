<x-halaman-dosen.konten-layout active="publikasi"
    title="{{ $mode === 'create' ? 'Tambah Publikasi' : 'Edit Publikasi' }}">

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;
                box-shadow:0 2px 8px rgba(0,0,0,.05);padding:28px 30px;">

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;
                    padding-bottom:16px;border-bottom:2px solid #f1f5f9;">
            <a href="{{ route('dosen.publikasi.index') }}"
               style="color:#94a3b8;font-size:18px;text-decoration:none;transition:color .2s;"
               onmouseover="this.style.color='#0f172a'"
               onmouseout="this.style.color='#94a3b8'">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0;">
                {{ $mode === 'create' ? 'Tambah Publikasi Baru' : 'Edit Publikasi' }}
            </h3>
        </div>

        {{-- Info note --}}
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;
                    padding:12px 16px;font-size:13px;color:#1d4ed8;
                    display:flex;align-items:flex-start;gap:8px;margin-bottom:24px;">
            <i class="bi bi-info-circle-fill" style="margin-top:1px;flex-shrink:0;"></i>
            <span>
                Publikasi yang Anda tambahkan/ubah akan otomatis berstatus <strong>Draft</strong>
                dan perlu direview serta disetujui oleh admin sebelum tampil di halaman publik.
                @if($mode === 'edit' && $publication->status === 'Publish')
                    Publikasi ini saat ini sudah <strong>Publish</strong> — jika Anda menyimpan perubahan,
                    statusnya akan kembali menjadi <strong>Draft</strong> dan perlu disetujui ulang oleh admin.
                @endif
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

        <form action="{{ $mode === 'create' ? route('dosen.publikasi.store') : route('dosen.publikasi.update', $publication) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            {{-- Judul + Tahun --}}
            <div style="display:grid;grid-template-columns:1fr 140px;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul', $publication->judul) }}" required
                           placeholder="Judul lengkap publikasi">
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                           value="{{ old('tahun', $publication->tahun ?? date('Y')) }}"
                           min="1900" max="{{ date('Y') + 1 }}" required>
                    @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Penulis --}}
            <div class="mb-3">
                <label class="form-label">Penulis <span class="text-danger">*</span></label>
                <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                       value="{{ old('penulis', $publication->penulis) }}" required
                       placeholder="Nama penulis (pisahkan dengan koma)">
                @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Abstrak --}}
            <div class="mb-3">
                <label class="form-label">Abstrak <span class="text-danger">*</span></label>
                <textarea name="abstrak" class="form-control @error('abstrak') is-invalid @enderror"
                          rows="5" required
                          placeholder="Tulis abstrak publikasi...">{{ old('abstrak', $publication->abstrak) }}</textarea>
                @error('abstrak') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Kategori + Status --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"
                                    @selected(old('kategori', $publication->kategori) === $cat)>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <div>
                        @php $currentStatus = $publication->status ?? \App\Models\Publication::STATUS_DRAFT; @endphp
                        <span style="display:inline-block;font-size:13px;font-weight:700;padding:7px 14px;
                                     border-radius:20px;
                                     {{ $currentStatus === 'Publish'
                                        ? 'background:#dcfce7;color:#16a34a;'
                                        : 'background:#f1f5f9;color:#64748b;' }}">
                            {{ $currentStatus }}
                        </span>
                    </div>
                    <div class="form-text">
                        Status tidak bisa diubah manual. Publikasi baru/hasil edit otomatis
                        berstatus <strong>Draft</strong> dan menunggu review admin.
                    </div>
                </div>
            </div>

            {{-- Penerbit + DOI --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Penerbit / Jurnal</label>
                    <input type="text" name="penerbit" class="form-control"
                           value="{{ old('penerbit', $publication->penerbit) }}"
                           placeholder="Nama jurnal / penerbit">
                </div>
                <div>
                    <label class="form-label">DOI</label>
                    <input type="text" name="doi" class="form-control"
                           value="{{ old('doi', $publication->doi) }}"
                           placeholder="10.xxxx/xxxxx">
                </div>
            </div>

            {{-- PDF + Thumbnail --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div>
                    <label class="form-label">
                        File PDF {{ $publication->exists ? '' : '<span class="text-danger">*</span>' }}
                    </label>
                    <input type="file" name="pdf"
                           class="form-control @error('pdf') is-invalid @enderror"
                           accept="application/pdf"
                           @if(!$publication->exists) required @endif>
                    @if($publication->pdf_path)
                        <div class="small mt-1">
                            File saat ini:
                            <a href="{{ route('dosen.publikasi.file', $publication) }}" target="_blank">
                                Download PDF
                            </a>
                        </div>
                    @endif
                    <div class="form-text">Maks 20MB</div>
                    @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    @if($publication->thumbnail_path)
                        <img src="{{ asset('storage/'.$publication->thumbnail_path) }}"
                             style="width:80px;height:55px;object-fit:cover;border-radius:6px;margin-top:8px;">
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>
                    {{ $mode === 'create' ? 'Simpan Publikasi' : 'Perbarui Publikasi' }}
                </button>
                <a href="{{ route('dosen.publikasi.index') }}" class="btn btn-light">Batal</a>
            </div>

        </form>
    </div>

</x-halaman-dosen.konten-layout>