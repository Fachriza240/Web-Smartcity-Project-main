<x-layout.admin title="Validasi Registrasi" subtitle="Kelola pendaftaran akun dosen dan content creator.">
    <div class="content-header">
        <div>
            <h2 class="mb-1">Validasi Registrasi</h2>
            <p class="mb-0">Terima atau tolak pendaftaran akun baru.</p>
        </div>
    </div>

    <div class="card-admin">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.validasi.index') }}" class="row g-2 g-md-3 mb-4 adm-filter">
                <div class="col-sm-6 col-md-4">
                    <label for="filterRole" class="visually-hidden">Peran</label>
                    <select name="role" id="filterRole" class="form-select">
                        <option value="">Semua Peran</option>
                        <option value="dosen" @selected(request('role') === 'dosen')>Dosen</option>
                        <option value="content_creator" @selected(request('role') === 'content_creator')>Content Creator</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label for="filterStatus" class="visually-hidden">Status</label>
                    <select name="status" id="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                        <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1" aria-hidden="true"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.validasi.index') }}" class="btn btn-light" title="Atur ulang filter" aria-label="Atur ulang filter">
                        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>NIP</th>
                            <th>Prodi / Fakultas</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftar as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($pendaftar->currentPage() - 1) * $pendaftar->perPage() }}</td>
                                <td><strong>{{ $user->fullname }}</strong></td>
                                <td class="small adm-break">{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === 'dosen')
                                        <span class="badge bg-primary">Dosen</span>
                                    @else
                                        <span class="badge bg-info text-dark">Content Creator</span>
                                    @endif
                                </td>
                                <td class="small">{{ $user->nip ?? '-' }}</td>
                                <td class="small">
                                    @if ($user->role === 'dosen')
                                        {{ $user->prodi ?? '-' }}
                                        @if ($user->fakultas)
                                            <span class="d-block text-muted">{{ $user->fakultas }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">{{ $user->created_at?->translatedFormat('d M Y') }}</td>
                                <td>
                                    @if ($user->registration_status === \App\Models\User::STATUS_PENDING)
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif ($user->registration_status === \App\Models\User::STATUS_REJECTED)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-success">Disetujui</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if ($user->registration_status !== \App\Models\User::STATUS_APPROVED)
                                            <form action="{{ route('admin.validasi.approve', $user->id) }}" method="POST" data-confirm="Terima akun {{ $user->fullname }}?">
                                                @csrf
                                                <button type="submit" class="adm-btn-soft adm-btn-soft--success">Terima</button>
                                            </form>
                                        @endif
                                        @if ($user->registration_status === \App\Models\User::STATUS_PENDING)
                                            <form action="{{ route('admin.validasi.reject', $user->id) }}" method="POST" data-confirm="Tolak akun {{ $user->fullname }}?">
                                                @csrf
                                                <button type="submit" class="adm-btn-soft adm-btn-soft--danger">Tolak</button>
                                            </form>
                                        @endif
                                        @if ($user->registration_status === \App\Models\User::STATUS_APPROVED)
                                            <span class="adm-muted">Tidak ada aksi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Belum ada pendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $pendaftar->links() }}</div>
        </div>
    </div>
</x-layout.admin>
