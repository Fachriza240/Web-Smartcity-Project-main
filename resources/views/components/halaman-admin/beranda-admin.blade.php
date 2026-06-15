{{-- Konten ini dirender di dalam <x-layout.admin> --}}

{{-- Header --}}
<div class="content-header">
    <div>
        <h2 class="mb-1">Dashboard Admin</h2>
        <p class="mb-0">Selamat datang di panel admin COE Smart City.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.settings') }}" class="btn btn-light btn-sm">
            <i class="bi bi-gear me-1"></i> Pengaturan
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Program</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\Program::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-blue"><i class="bi bi-layers"></i></div>
            </div>
            <a href="{{ route('admin.programs.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Project</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\Project::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-green"><i class="bi bi-kanban"></i></div>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Berita</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\News::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-yellow"><i class="bi bi-newspaper"></i></div>
            </div>
            <a href="{{ route('admin.news.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Publication</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\Publication::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-cyan"><i class="bi bi-journal-text"></i></div>
            </div>
            <a href="{{ route('admin.publications.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Tim</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\Team::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-blue"><i class="bi bi-people-fill"></i></div>
            </div>
            <a href="{{ route('admin.teams.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Mitra</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\Partner::where('status','Publish')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-gray"><i class="bi bi-handshake"></i></div>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="adm-stat__link">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="adm-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="adm-stat__label">Registrasi Pending</div>
                    <div class="adm-stat__num mt-1">{{ \App\Models\User::where('registration_status','pending')->count() }}</div>
                </div>
                <div class="adm-stat__icon icon-bg-red"><i class="bi bi-person-check"></i></div>
            </div>
            <a href="{{ route('admin.validasi.index') }}" class="adm-stat__link">Validasi <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

{{-- Quick Access --}}
<div class="card-admin">
    <div class="card-body">
        <h5 class="card-title mb-3">Quick Access</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.programs.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus me-1"></i>Program
            </a>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-plus me-1"></i>Project
            </a>
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-plus me-1"></i>Berita
            </a>
            <a href="{{ route('admin.publications.create') }}" class="btn btn-sm btn-outline-info">
                <i class="bi bi-plus me-1"></i>Publication
            </a>
            <a href="{{ route('admin.teams.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus me-1"></i>Tim
            </a>
            <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-plus me-1"></i>Mitra
            </a>
        </div>
    </div>
</div>
