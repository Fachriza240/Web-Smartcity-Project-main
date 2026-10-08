<x-layout.admin title="Validasi Registrasi" subtitle="Kelola pendaftaran akun dosen dan content creator.">
    <div class="content-header">
        <div>
            <h2 class="mb-1">Validasi Registrasi</h2>
            <p class="mb-0">Terima atau tolak pendaftaran akun baru, serta aktifkan atau nonaktifkan akun pengguna.</p>
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
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu Validasi</option>
                        <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
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
                                        <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                    @elseif ($user->registration_status === \App\Models\User::STATUS_REJECTED)
                                        <span class="badge bg-danger">Ditolak</span>
                                        @if ($user->rejection_reason)
                                            <span class="d-block small text-muted mt-1">Alasan: {{ $user->rejection_reason }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Disetujui</span>
                                    @endif
                                    @unless ($user->isActive())
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endunless
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if ($user->registration_status !== \App\Models\User::STATUS_APPROVED)
                                            <form action="{{ route('admin.validasi.approve', $user->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menyetujui registrasi akun {{ $user->fullname }}?">
                                                @csrf
                                                <button type="submit" class="adm-btn-soft adm-btn-soft--success">Terima</button>
                                            </form>
                                        @endif
                                        @if ($user->registration_status === \App\Models\User::STATUS_PENDING)
                                            <button type="button" class="adm-btn-soft adm-btn-soft--danger" data-bs-toggle="modal" data-bs-target="#tolak-{{ $user->id }}">Tolak</button>
                                        @endif
                                        @if ($user->registration_status === \App\Models\User::STATUS_APPROVED)
                                            @if ($user->isActive())
                                                <form action="{{ route('admin.validasi.deactivate', $user->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menonaktifkan akun {{ $user->fullname }}? Pengguna tidak akan bisa login sampai akun diaktifkan kembali.">
                                                    @csrf
                                                    <button type="submit" class="adm-btn-soft adm-btn-soft--danger">Nonaktifkan</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.validasi.activate', $user->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin mengaktifkan kembali akun {{ $user->fullname }}?">
                                                    @csrf
                                                    <button type="submit" class="adm-btn-soft adm-btn-soft--success">Aktifkan</button>
                                                </form>
                                            @endif
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

    @foreach ($pendaftar as $user)
        @if ($user->registration_status === \App\Models\User::STATUS_PENDING)
            @php
                $hasError = (int) session('tolak_id') === (int) $user->id;
            @endphp
            <div class="modal fade" id="tolak-{{ $user->id }}" tabindex="-1" aria-labelledby="tolak-title-{{ $user->id }}" aria-hidden="true" @if ($hasError) data-show-on-load @endif>
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" action="{{ route('admin.validasi.reject', $user->id) }}" method="POST" novalidate data-validate>
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tolak-title-{{ $user->id }}">Tolak Registrasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">Tolak registrasi akun <strong>{{ $user->fullname }}</strong> ({{ $user->email }}). Alasan penolakan akan dikirim ke pengguna.</p>
                            <div data-field>
                                <label for="alasan-{{ $user->id }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea id="alasan-{{ $user->id }}" name="alasan" rows="4" class="form-control @if ($hasError && $errors->has('alasan')) is-invalid @endif"
                                    placeholder="Contoh: NIP yang dimasukkan tidak sesuai dengan data kepegawaian."
                                    data-label="Alasan penolakan" data-rules="required|min:10|max:500|safe">{{ $hasError ? old('alasan') : '' }}</textarea>
                                <div class="form-text">Minimal 10 karakter, maksimal 500 karakter.</div>
                                @if ($hasError && $errors->has('alasan'))
                                    <div class="invalid-feedback d-block" data-error-for="alasan">{{ $errors->first('alasan') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger" data-confirm-submit="Apakah Anda yakin ingin menolak registrasi akun {{ $user->fullname }}?">Tolak Registrasi</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
</x-layout.admin>
