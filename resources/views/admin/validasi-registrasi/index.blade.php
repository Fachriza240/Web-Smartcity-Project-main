<x-layout.admin title="Validasi Registrasi" subtitle="Kelola pendaftaran akun dosen dan content creator.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Validasi Registrasi</h2>
            <p class="mb-0">Approve atau tolak pendaftaran akun baru.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-admin">
        <div class="card-body">

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.validasi.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="dosen"           @selected(request('role') === 'dosen')>Dosen</option>
                        <option value="content_creator" @selected(request('role') === 'content_creator')>Content Creator</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.validasi.index') }}" class="btn btn-light" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
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
                            <th>Role</th>
                            <th>NIP</th>
                            <th>Prodi / Fakultas</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftar as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($pendaftar->currentPage() - 1) * $pendaftar->perPage() }}</td>
                                <td><strong>{{ $user->fullname }}</strong></td>
                                <td class="small">{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'dosen')
                                        <span class="badge bg-primary">Dosen</span>
                                    @else
                                        <span class="badge bg-info text-dark">Content Creator</span>
                                    @endif
                                </td>
                                <td class="small">{{ $user->nip ?? '-' }}</td>
                                <td class="small">
                                    @if($user->role === 'dosen')
                                        {{ $user->prodi ?? '-' }}<br>
                                        <span class="text-muted">{{ $user->fakultas ?? '' }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">{{ optional($user->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($user->registration_status === \App\Models\User::STATUS_PENDING)
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($user->registration_status === \App\Models\User::STATUS_REJECTED)
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($user->registration_status === \App\Models\User::STATUS_PENDING)
                                            {{-- Pending: tampilkan Terima dan Tolak --}}
                                            <form action="{{ route('admin.validasi.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Terima akun {{ addslashes($user->fullname) }}?')"
                                                        style="padding:6px 14px; font-size:12px; font-weight:700;
                                                               background:#f0fdf4; color:#16a34a;
                                                               border:1.5px solid #bbf7d0; border-radius:8px;
                                                               cursor:pointer; white-space:nowrap;">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.validasi.reject', $user->id) }}" method="POST" onsubmit="return handleReject(this, '{{ addslashes($user->fullname) }}')">
                                                @csrf
                                                <input type="hidden" name="rejection_reason">
                                                <button type="submit"
                                                        style="padding:6px 14px; font-size:12px; font-weight:700;
                                                            background:#fef2f2; color:#dc2626;
                                                            border:1.5px solid #fecaca; border-radius:8px;
                                                            cursor:pointer; white-space:nowrap;">
                                                    Tolak
                                                </button>
                                            </form>
                                            <script>
                                                function handleReject(form, fullname) {
                                                    const reason = prompt('Alasan penolakan untuk akun ' + fullname + ' (minimal 10 karakter):');
                                                    if (reason === null) {
                                                        return false; // dibatalkan
                                                    }
                                                    if (reason.trim().length < 10) {
                                                        alert('Alasan penolakan minimal 10 karakter.');
                                                        return false;
                                                    }
                                                    form.querySelector('input[name="rejection_reason"]').value = reason.trim();
                                                    return true;
                                                }
                                            </script>

                                        @elseif($user->registration_status === \App\Models\User::STATUS_REJECTED)
                                            {{-- Rejected: hanya bisa di-Terima --}}
                                            <form action="{{ route('admin.validasi.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Terima akun {{ addslashes($user->fullname) }}?')"
                                                        style="padding:6px 14px; font-size:12px; font-weight:700;
                                                               background:#f0fdf4; color:#16a34a;
                                                               border:1.5px solid #bbf7d0; border-radius:8px;
                                                               cursor:pointer; white-space:nowrap;">
                                                    Terima
                                                </button>
                                            </form>

                                        @else
                                            {{-- Approved: tidak ada aksi --}}
                                            <span style="font-size:12px; color:var(--adm-text-3);">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada pendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pendaftar->links() }}
            </div>

        </div>
    </div>

</x-layout.admin>
