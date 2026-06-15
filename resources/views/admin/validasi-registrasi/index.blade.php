<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Validasi Registrasi</h2>
                    <p class="mb-0">Kelola pendaftaran akun dosen.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Pendaftar Dosen</h5>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>NIP</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftar as $user)
                                    <tr>
                                        <td>{{ $loop->iteration + ($pendaftar->currentPage() - 1) * $pendaftar->perPage() }}</td>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->nip }}</td>
                                        <td>{{ optional($user->created_at)->format('d M Y') }}</td>
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
                                                <form action="{{ route('admin.validasi.approve', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve pendaftar ini?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.validasi.reject', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject pendaftar ini?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada pendaftar dosen.</td>
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
