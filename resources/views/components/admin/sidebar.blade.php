@php
    $isAdmin   = auth()->check() && auth()->user()->role === 'admin';
    $isCreator = auth()->check() && auth()->user()->role === 'content_creator';
    $dashboard = $isAdmin ? '/beranda-admin' : '/beranda-creator';
    $brandLogo = $isAdmin ? 'A' : 'C';
    $brandName = $isAdmin ? 'Admin Panel' : 'Creator Panel';
@endphp

<aside class="sidebar" id="admSidebar">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-logo">{{ $brandLogo }}</div>
        <h4 class="mb-0">{{ $brandName }}</h4>
    </div>

    {{-- Scrollable nav area --}}
    <div class="sidebar-inner">
        <ul class="nav-menu">

            <li class="menu-category">Main Menu</li>
            <li class="nav-item">
                <a href="{{ $dashboard }}"
                   class="nav-link {{ request()->is(ltrim($dashboard, '/')) ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            @if($isAdmin)
                <li class="nav-item">
                    <a href="{{ route('admin.validasi.index') }}"
                       class="nav-link {{ request()->routeIs('admin.validasi.*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i> Validasi Registrasi
                    </a>
                </li>
            @endif

            <li class="menu-category">Content Management</li>
            <li class="nav-item">
                <a href="{{ route('admin.programs.index') }}"
                   class="nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                    <i class="bi bi-layers"></i> Program
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.projects.index') }}"
                   class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <i class="bi bi-kanban"></i> Project
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.publications.index') }}"
                   class="nav-link {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Publication
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.news.index') }}"
                   class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <i class="bi bi-newspaper"></i> Berita
                </a>
            </li>
            @if($isAdmin)
                <li class="nav-item">
                    <a href="{{ route('admin.teams.index') }}"
                       class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Tim
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('admin.partners.index') }}"
                   class="nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                    <i class="bi bi-handshake"></i> Mitra
                </a>
            </li>

            <li class="menu-category">Settings</li>
            <li class="nav-item">
                <a href="{{ route('admin.settings') }}"
                   class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('cms-logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="cms-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>

</aside>
