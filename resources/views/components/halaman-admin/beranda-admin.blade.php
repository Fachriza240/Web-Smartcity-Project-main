@php
    $stats = [
        ['label' => 'Program', 'value' => \App\Models\Program::published()->count(), 'icon' => 'bi-layers', 'color' => 'icon-bg-blue', 'url' => route('admin.programs.index')],
        ['label' => 'Proyek', 'value' => \App\Models\Project::published()->count(), 'icon' => 'bi-kanban', 'color' => 'icon-bg-green', 'url' => route('admin.projects.index')],
        ['label' => 'Berita', 'value' => \App\Models\News::published()->count(), 'icon' => 'bi-newspaper', 'color' => 'icon-bg-yellow', 'url' => route('admin.news.index')],
        ['label' => 'Publikasi', 'value' => \App\Models\Publication::published()->count(), 'icon' => 'bi-journal-text', 'color' => 'icon-bg-cyan', 'url' => route('admin.publications.index')],
        ['label' => 'HKI', 'value' => \App\Models\Hki::published()->count(), 'icon' => 'bi-award', 'color' => 'icon-bg-purple', 'url' => route('admin.hki.index')],
        ['label' => 'Tim', 'value' => \App\Models\Team::published()->count(), 'icon' => 'bi-people-fill', 'color' => 'icon-bg-blue', 'url' => route('admin.teams.index')],
        ['label' => 'Mitra', 'value' => \App\Models\Partner::published()->count(), 'icon' => 'bi-buildings', 'color' => 'icon-bg-gray', 'url' => route('admin.partners.index')],
        ['label' => 'Registrasi Menunggu', 'value' => \App\Models\User::where('registration_status', \App\Models\User::STATUS_PENDING)->count(), 'icon' => 'bi-person-check', 'color' => 'icon-bg-red', 'url' => route('admin.validasi.index', ['status' => 'pending']), 'action' => 'Validasi'],
    ];
    $shortcuts = [
        ['label' => 'Program', 'url' => route('admin.programs.create')],
        ['label' => 'Proyek', 'url' => route('admin.projects.create')],
        ['label' => 'Berita', 'url' => route('admin.news.create')],
        ['label' => 'Publikasi', 'url' => route('admin.publications.create')],
        ['label' => 'HKI', 'url' => route('admin.hki.create')],
        ['label' => 'Anggota Tim', 'url' => route('admin.teams.create')],
        ['label' => 'Mitra', 'url' => route('admin.partners.create')],
    ];
@endphp

<div class="content-header">
    <div>
        <h2 class="mb-1">Dashboard Admin</h2>
        <p class="mb-0">Selamat datang di panel admin CoE Smart City.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.settings') }}" class="btn btn-light btn-sm">
            <i class="bi bi-gear me-1" aria-hidden="true"></i> Pengaturan
        </a>
    </div>
</div>

<x-admin.stats :items="$stats" />

<div class="card-admin">
    <div class="card-body">
        <h3 class="card-title">Tambah Cepat</h3>
        <div class="adm-shortcuts">
            @foreach ($shortcuts as $shortcut)
                <a href="{{ $shortcut['url'] }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>{{ $shortcut['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
