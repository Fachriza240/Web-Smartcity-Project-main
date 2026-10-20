<x-layout.admin title="Aktivitas Dosen" subtitle="Data penelitian, abdimas, produk, kolaborasi, dan kepemimpinan dosen untuk penilaian DSS.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Aktivitas Dosen</h2>
            <p class="mb-0">Data penelitian, abdimas, produk, kolaborasi, dan kepemimpinan dosen untuk penilaian DSS.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.aktivitas-dosen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Aktivitas
            </a>
        </div>
    </div>

    <div class="card-admin">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.aktivitas-dosen.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                           placeholder="Cari judul, mitra, nama dosen...">
                </div>
                <div class="col-md-2">
                    <select name="user_id" class="form-select" aria-label="Filter dosen">
                        <option value="">Semua Dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected((int) request('user_id') === $dosen->id)>{{ $dosen->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="jenis" class="form-select" aria-label="Filter jenis">
                        <option value="">Semua Jenis</option>
                        @foreach ($jenisList as $key => $label)
                            <option value="{{ $key }}" @selected(request('jenis') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="number" name="tahun" class="form-control" value="{{ request('tahun') }}"
                           placeholder="Tahun" min="2000" max="{{ date('Y') }}" aria-label="Filter tahun">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.aktivitas-dosen.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Dosen</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Detail</th>
                            <th>Tahun</th>
                            <th>Bidang Smart City</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aktivitas as $item)
                            <tr>
                                <td class="small">
                                    <strong>{{ $item->user?->fullname ?? '-' }}</strong>
                                    @if ($item->user?->prodi)
                                        <div class="text-muted">{{ $item->user->prodi }}</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->judul }}</strong>
                                    @if ($item->mitra)
                                        <div class="small text-muted">Mitra: {{ $item->mitra }}</div>
                                    @endif
                                </td>
                                <td><span class="badge bg-primary">{{ $item->label_jenis }}</span></td>
                                <td class="small">{{ $item->ringkasan }}</td>
                                <td class="small">{{ $item->tahun }}</td>
                                <td class="small">{{ $item->label_bidang }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.aktivitas-dosen.edit', $item) }}" class="btn-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.aktivitas-dosen.destroy', $item) }}" method="POST"
                                              data-confirm="Hapus aktivitas ini?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4">Belum ada data aktivitas dosen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $aktivitas->links() }}</div>
        </div>
    </div>

</x-layout.admin>