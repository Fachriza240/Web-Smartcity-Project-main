<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">News</h2>
                    <p class="mb-0">Kelola berita COE Smart City.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Berita
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.news.index') }}" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Cari judul, konten...">
                        </div>
                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(request('kategori') === $cat)>{{ $cat }}</option>
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
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news as $item)
                                    <tr>
                                        <td>
                                            @if($item->thumbnail_path)
                                                <img src="{{ asset('storage/' . $item->thumbnail_path) }}"
                                                     alt="{{ $item->judul }}"
                                                     style="width:72px;height:48px;object-fit:cover;border-radius:8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width:72px;height:48px;border-radius:8px;">
                                                    <i class="bi bi-newspaper text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->judul }}</strong>
                                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 60) }}</div>
                                        </td>
                                        <td>{{ $item->kategori ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $item->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="small">
                                            {{ $item->published_at?->format('d M Y') ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if($item->status === 'Publish')
                                                    <a href="{{ route('news.show', $item) }}" class="btn-icon" title="Lihat" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.news.edit', $item) }}" class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.news.destroy', $item) }}" method="POST"
                                                      onsubmit="return confirm('Hapus berita ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Belum ada berita.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $news->links() }}</div>
                </div>
            </div>
        </x-layout.admin>
