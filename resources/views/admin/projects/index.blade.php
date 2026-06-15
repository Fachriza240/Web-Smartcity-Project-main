<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Project</h2>
                    <p class="mb-0">Kelola proyek-proyek COE Smart City.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>
                        Tambah Project
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.projects.index') }}" class="row g-3 mb-4">
                        <div class="col-md-7">
                            <input type="text" name="search" class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="Cari judul, deskripsi, kategori, partner...">
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
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-light" title="Reset">
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
                                    <th>Kategori</th>
                                    <th>Partner</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>
                                            @if($project->thumbnail_path)
                                                <img src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                                     alt="{{ $project->judul }}"
                                                     style="width: 72px; height: 48px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 72px; height: 48px; border-radius: 8px;">
                                                    <i class="bi bi-kanban text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $project->judul }}</strong>
                                            @if($project->deskripsi)
                                                <div class="small text-muted">
                                                    {{ \Illuminate\Support\Str::limit($project->deskripsi, 60) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $project->kategori ?? '-' }}</td>
                                        <td>{{ $project->partner ?? '-' }}</td>
                                        <td>{{ $project->tahun ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $project->status === \App\Models\Project::STATUS_PUBLISH ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.projects.edit', $project) }}"
                                                   class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.projects.destroy', $project) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus project ini?')">
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
                                        <td colspan="7" class="text-center py-4">Belum ada project.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </x-layout.admin>
