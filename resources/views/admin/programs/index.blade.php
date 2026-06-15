<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Program</h2><p class="mb-0">Kelola program COE Smart City.</p></div>
                <div class="header-actions">
                    <a href="{{ route('admin.programs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Program
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.programs.index') }}" class="row g-3 mb-4">
                        <div class="col-md-7">
                            <input type="text" name="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Cari judul, deskripsi...">
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
                            <a href="{{ route('admin.programs.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Judul</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programs as $program)
                                    <tr>
                                        <td>
                                            @if($program->thumbnail_path)
                                                <img src="{{ asset('storage/'.$program->thumbnail_path) }}"
                                                     alt="{{ $program->judul }}"
                                                     style="width:72px;height:48px;object-fit:cover;border-radius:8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width:72px;height:48px;border-radius:8px;">
                                                    <i class="bi bi-layers text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $program->judul }}</strong>
                                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit($program->deskripsi, 60) }}</div>
                                        </td>
                                        <td>{{ $program->urutan }}</td>
                                        <td>
                                            <span class="badge {{ $program->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">{{ $program->status }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.programs.edit', $program) }}" class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.programs.destroy', $program) }}" method="POST"
                                                      onsubmit="return confirm('Hapus program ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Belum ada program.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $programs->links() }}</div>
                </div>
            </div>
        </x-layout.admin>
