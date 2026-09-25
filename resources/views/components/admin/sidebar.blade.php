@php
    $role = auth()->user()?->role;
    $isAdmin = $role === 'admin';
    $dashboard = $isAdmin ? '/beranda-admin' : '/beranda-creator';
    $menus = [
        'Menu Utama' => array_filter([
            ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => url($dashboard), 'active' => request()->is(ltrim($dashboard, '/'))],
            $isAdmin ? ['label' => 'Validasi Registrasi', 'icon' => 'bi-person-check', 'url' => route('admin.validasi.index'), 'active' => request()->routeIs('admin.validasi.*')] : null,
        ]),
        'Kelola Konten' => array_filter([
            ['label' => 'Program', 'icon' => 'bi-layers', 'url' => route('admin.programs.index'), 'active' => request()->routeIs('admin.programs.*')],
            ['label' => 'Proyek', 'icon' => 'bi-kanban', 'url' => route('admin.projects.index'), 'active' => request()->routeIs('admin.projects.*')],
            $isAdmin ? ['label' => 'Publikasi', 'icon' => 'bi-journal-text', 'url' => route('admin.publications.index'), 'active' => request()->routeIs('admin.publications.*')] : null,
            $isAdmin ? ['label' => 'HKI', 'icon' => 'bi-award', 'url' => route('admin.hki.index'), 'active' => request()->routeIs('admin.hki.*')] : null,
            ['label' => 'Berita', 'icon' => 'bi-newspaper', 'url' => route('admin.news.index'), 'active' => request()->routeIs('admin.news.*')],
            $isAdmin ? ['label' => 'Tim', 'icon' => 'bi-people-fill', 'url' => route('admin.teams.index'), 'active' => request()->routeIs('admin.teams.*')] : null,
            ['label' => 'Mitra', 'icon' => 'bi-buildings', 'url' => route('admin.partners.index'), 'active' => request()->routeIs('admin.partners.*')],
        ]),
        'Lainnya' => [
            ['label' => 'Pengaturan', 'icon' => 'bi-gear', 'url' => route('admin.settings'), 'active' => request()->routeIs('admin.settings')],
            ['label' => 'Lihat Website', 'icon' => 'bi-box-arrow-up-right', 'url' => url('/'), 'active' => false, 'external' => true],
        ],
    ];
@endphp

<aside class="sidebar" id="admSidebar" aria-label="Menu panel">
    <div class="brand">
        <div class="brand-logo">{{ $isAdmin ? 'A' : 'C' }}</div>
        <h4 class="mb-0">{{ $isAdmin ? 'Panel Admin' : 'Panel Creator' }}</h4>
        <button type="button" class="adm-sidebar-close" id="sidebarClose" aria-label="Tutup menu">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
    </div>

    <div class="sidebar-inner">
        <ul class="nav-menu">
            @foreach ($menus as $group => $items)
                <li class="menu-category">{{ $group }}</li>
                @foreach ($items as $item)
                    <li class="nav-item">
                        <a href="{{ $item['url'] }}" class="nav-link {{ $item['active'] ? 'active' : '' }}"
                            @if ($item['active']) aria-current="page" @endif
                            @if (! empty($item['external'])) target="_blank" rel="noopener" @endif>
                            <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i> {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            @endforeach
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="cms-logout-form" data-confirm="Apakah Anda yakin ingin keluar dari akun?">
                    @csrf
                    <button type="submit" class="nav-link adm-nav-button">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
