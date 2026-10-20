<x-layout.admin title="Statistik Proyek">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Statistik Proyek</h2>
            <p class="mb-0">Kelola daftar proyek internasional, proyek nasional, dan produk unggulan yang tampil pada bagian Statistik Smart City di beranda.</p>
        </div>
    </div>

    <div class="card-admin mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Tambah Data</h5>

            <form action="{{ route('admin.statistik-proyek.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3" data-field>
                    <label for="tipe" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror" required
                            data-label="Kategori" data-rules="required">
                        @foreach ($tipes as $key => $label)
                            <option value="{{ $key }}" @selected(old('tipe', $tipeDipilih) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tipe')<div class="invalid-feedback d-block" data-error-for="tipe">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-5" data-field>
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}" required placeholder="Contoh: Quantum MMU"
                           data-label="Nama" data-rules="required|min:2|max:150|safe">
                    @error('nama')<div class="invalid-feedback d-block" data-error-for="nama">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2" data-field>
                    <label for="urutan" class="form-label">Urutan</label>
                    <input type="number" id="urutan" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                           value="{{ old('urutan') }}" min="0" max="9999" placeholder="Otomatis"
                           data-label="Urutan" data-rules="range:0,9999">
                    @error('urutan')<div class="invalid-feedback d-block" data-error-for="urutan">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah
                    </button>
                </div>
            </form>

            <div class="form-text mt-3">
                Angka <strong>Jumlah Proyek</strong> di beranda dihitung otomatis dari total Proyek Internasional dan Proyek Nasional.
                Produk Unggulan tidak ikut dihitung sebagai proyek. Kosongkan kolom urutan agar data ditempatkan di urutan paling akhir.
            </div>
        </div>
    </div>

    @foreach ($tipes as $key => $label)
        @php $items = $daftar->get($key, collect()); @endphp
        <div class="card-admin mb-4" id="kategori-{{ $key }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">{{ $label }}</h5>
                    <span class="badge bg-primary">{{ $items->count() }} data</span>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Nama</th>
                                <th style="width: 110px;">Urutan</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->nama }}</strong></td>
                                    <td>{{ $item->urutan }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.statistik-proyek.edit', $item) }}" class="btn-icon" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.statistik-proyek.destroy', $item) }}" method="POST"
                                                  data-confirm="Hapus &quot;{{ $item->nama }}&quot; dari {{ $label }}?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4">Belum ada data {{ strtolower($label) }}.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

</x-layout.admin>