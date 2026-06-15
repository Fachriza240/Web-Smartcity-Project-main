<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Team</h2><p class="mb-0">Kelola anggota tim COE Smart City.</p></div>
                <div class="header-actions">
                    <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Anggota
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card-admin">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.teams.index') }}" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Cari nama, jabatan, bidang...">
                        </div>
                        <div class="col-md-2">
                            <select name="tipe" class="form-select">
                                <option value="">Semua Tipe</option>
                                @foreach($tipes as $tipe)
                                    <option value="{{ $tipe }}" @selected(request('tipe') === $tipe)>{{ $tipe }}</option>
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
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teams as $team)
                                    <tr>
                                        <td>
                                            @if($team->foto_path)
                                                <img src="{{ asset('storage/'.$team->foto_path) }}" alt="{{ $team->nama }}"
                                                     style="width:48px;height:48px;object-fit:cover;border-radius:50%;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width:48px;height:48px;border-radius:50%;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $team->nama }}</strong></td>
                                        <td>{{ $team->jabatan }}</td>
                                        <td>{{ $team->bidang ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $team->tipe === 'Staff' ? 'bg-primary' : 'bg-info text-dark' }}">
                                                {{ $team->tipe }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $team->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $team->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.teams.edit', $team) }}" class="btn-icon" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.teams.destroy', $team) }}" method="POST"
                                                      onsubmit="return confirm('Hapus anggota ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-4">Belum ada anggota tim.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $teams->links() }}</div>
                </div>
            </div>
        </x-layout.admin>
