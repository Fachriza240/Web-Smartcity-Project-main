<x-layout.admin title="HKI" subtitle="Kelola Hak Kekayaan Intelektual COE Smart City.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">HKI</h2>
            <p class="mb-0">Kelola Hak Kekayaan Intelektual COE Smart City.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.hki.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah HKI
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-admin">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hki.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Cari judul, nomor, pencipta...">
                </div>
                <div class="col-md-3">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j }}" @selected(request('jenis') === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.hki.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Sertifikat</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Pencipta</th>
                            <th>Pengusul</th>
                            <th>Tgl Terbit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hkis as $item)
                            <tr>
                                <td class="small fw-semibold">{{ $item->nomor_sertifikat }}</td>
                                <td>
                                    <strong>{{ $item->judul_sertifikat }}</strong>
                                    @if($item->file_sertifikat)
                                        <div class="small">
                                            <a href="{{ asset('storage/'.$item->file_sertifikat) }}" target="_blank">
                                                <i class="bi bi-file-earmark-text"></i> Lihat
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="badge bg-primary">{{ $item->jenis_sertifikat }}</span></td>
                                <td class="small">{{ $item->pencipta }}</td>
                                <td class="small">
                                    @if($item->submission_type === 'member')
                                        <span class="badge bg-info text-dark">Member</span><br>
                                        {{ $item->recommender?->fullname ?? '-' }}
                                    @else
                                        <span class="badge bg-secondary">Non-Member</span><br>
                                        {{ $item->recommended_by ?? '-' }}
                                    @endif
                                </td>
                                <td class="small">{{ $item->tgl_terbit?->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.hki.edit', $item) }}" class="btn-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.hki.destroy', $item) }}" method="POST"
                                              onsubmit="return confirm('Hapus HKI ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4">Belum ada data HKI.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $hkis->links() }}</div>
        </div>
    </div>

</x-layout.admin>
