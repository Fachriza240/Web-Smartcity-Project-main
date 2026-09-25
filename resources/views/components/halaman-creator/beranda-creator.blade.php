@php
    $stats = [
        ['label' => 'Program', 'value' => \App\Models\Program::published()->count(), 'icon' => 'bi-layers', 'color' => 'icon-bg-blue', 'url' => route('admin.programs.index')],
        ['label' => 'Proyek', 'value' => \App\Models\Project::published()->count(), 'icon' => 'bi-kanban', 'color' => 'icon-bg-green', 'url' => route('admin.projects.index')],
        ['label' => 'Berita', 'value' => \App\Models\News::published()->count(), 'icon' => 'bi-newspaper', 'color' => 'icon-bg-yellow', 'url' => route('admin.news.index')],
        ['label' => 'Mitra', 'value' => \App\Models\Partner::published()->count(), 'icon' => 'bi-buildings', 'color' => 'icon-bg-gray', 'url' => route('admin.partners.index')],
    ];
    $latestNews = \App\Models\News::latest()->limit(5)->get();
@endphp

<div class="content-header">
    <div>
        <h2 class="mb-1">Dashboard Content Creator</h2>
        <p class="mb-0">Kelola konten Program, Proyek, Berita, dan Mitra.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tulis Berita
        </a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Proyek
        </a>
    </div>
</div>

<x-admin.stats :items="$stats" />

<div class="card-admin">
    <div class="card-body">
        <div class="adm-card-head">
            <h3 class="card-title mb-0">Berita Terbaru</h3>
            <a href="{{ route('admin.news.index') }}" class="adm-stat__link">Lihat semua <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
        @if ($latestNews->isEmpty())
            <p class="adm-muted mb-0">Belum ada berita. Mulai dengan menulis berita pertama.</p>
        @else
            <ul class="adm-simple-list">
                @foreach ($latestNews as $item)
                    <li>
                        <a href="{{ route('admin.news.edit', $item) }}">{{ $item->judul }}</a>
                        <span class="badge {{ $item->status === 'Publish' ? 'bg-success' : 'bg-secondary' }}">{{ $item->status }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
