<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Publication</h2>
                    <p class="mb-0">Kelola publikasi ilmiah COE Smart City.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.publications.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>
                        Tambah Publication
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.publications.index') }}" class="row g-3 mb-4">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul, penulis, penerbit, DOI">
                        </div>
                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.publications.index') }}" class="btn btn-light" title="Reset">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tahun</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publications as $publication)
                                    <tr>
                                        <td>
                                            @if($publication->thumbnail_path)
                                                <img src="{{ asset('storage/' . $publication->thumbnail_path) }}" alt="{{ $publication->judul }}" style="width: 72px; height: 48px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 72px; height: 48px; border-radius: 8px;">
                                                    <i class="bi bi-journal-text text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $publication->judul }}</strong>
                                            @if($publication->doi)
                                                <div class="small text-muted">DOI: {{ $publication->doi }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $publication->penulis }}</td>
                                        <td>{{ $publication->tahun }}</td>
                                        <td>{{ $publication->kategori }}</td>
                                        <td>
                                            <span class="badge {{ $publication->status === \App\Models\Publication::STATUS_PUBLISH ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $publication->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if($publication->status === \App\Models\Publication::STATUS_PUBLISH)
                                                    <a href="{{ route('publications.show', $publication) }}" class="btn-icon" title="Lihat" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.publications.edit', $publication) }}" class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.publications.destroy', $publication) }}" method="POST" onsubmit="return confirm('Hapus publication ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-icon" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada publication.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $publications->links() }}
                    </div>
                </div>
            </div>
        </x-layout.admin>
