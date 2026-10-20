<x-layout.admin title="Edit Statistik Proyek">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Edit Data</h2>
            <p class="mb-0">Perbarui nama, kategori, atau urutan data pada Statistik Smart City.</p>
        </div>
    </div>

    <div class="card-admin">
        <div class="card-body">
            <form action="{{ route('admin.statistik-proyek.update', $item) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3" data-field>
                        <label for="tipe" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror" required
                                data-label="Kategori" data-rules="required">
                            @foreach ($tipes as $key => $label)
                                <option value="{{ $key }}" @selected(old('tipe', $item->tipe) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipe')<div class="invalid-feedback d-block" data-error-for="tipe">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-5 mb-3" data-field>
                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $item->nama) }}" required
                               data-label="Nama" data-rules="required|min:2|max:150|safe">
                        <div class="form-text">Minimal 2 karakter, maksimal 150 karakter.</div>
                        @error('nama')<div class="invalid-feedback d-block" data-error-for="nama">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3 mb-3" data-field>
                        <label for="urutan" class="form-label">Urutan</label>
                        <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                               value="{{ old('urutan', $item->urutan) }}" min="0" max="9999"
                               data-label="Urutan" data-rules="range:0,9999">
                        <div class="form-text">Angka kecil tampil lebih dulu.</div>
                        @error('urutan')<div class="invalid-feedback d-block" data-error-for="urutan">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-1">
                    <button type="submit" class="btn btn-success" data-confirm-submit="Apakah Anda yakin ingin menyimpan perubahan ini?">
                        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
                    </button>
                    <a href="{{ route('admin.statistik-proyek.index', ['tipe' => $item->tipe]) }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layout.admin>