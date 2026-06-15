<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Mitra</h2><p class="mb-0">Kelola mitra/partner COE Smart City.</p></div>
                <div class="header-actions">
                    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Mitra
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.partners.index') }}" class="row g-3 mb-4">
                        <div class="col-md-7">
                            <input type="text" name="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Cari nama, deskripsi...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Nama</th>
                                    <th>Website</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($partners as $partner)
                                    <tr>
                                        <td>
                                            @if($partner->logo_path)
                                                <img src="{{ asset('storage/'.$partner->logo_path) }}"
                                                     alt="{{ $partner->nama }}"
                                                     style="height:40px;max-width:80px;object-fit:contain;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width:72px;height:40px;border-radius:8px;">
                                                    <i class="bi bi-building text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $partner->nama }}</strong>
                                            @if($partner->deskripsi)
                                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($partner->deskripsi, 60) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($partner->website)
                                                <a href="{{ $partner->website }}" target="_blank" class="small">
                                                    <i class="bi bi-link-45deg"></i> Link
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $partner->urutan }}</td>
                                        <td>
                                            <span class="badge {{ $partner->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $partner->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                                                      onsubmit="return confirm('Hapus mitra ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Belum ada mitra.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $partners->links() }}</div>
                </div>
            </div>
        </x-layout.admin>
