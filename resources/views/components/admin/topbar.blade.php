@props(['title' => 'Dashboard', 'subtitle' => ''])

@php
    $user = auth()->user();
    $initial = mb_strtoupper(mb_substr($user?->fullname ?? 'U', 0, 1));
    $roleBadge = match ($user?->role) {
        'admin' => 'Admin',
        'content_creator' => 'Content Creator',
        'dosen' => 'Dosen',
        default => 'Pengguna',
    };
@endphp

<header class="adm-topbar">
    <div class="adm-topbar__left">
        <button type="button" class="adm-topbar-btn adm-mobile-toggle" id="sidebarToggle" aria-label="Buka menu" aria-controls="admSidebar" aria-expanded="false">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <div class="adm-topbar__titles">
            <h1 class="adm-topbar__title">{{ $title }}</h1>
            @if ($subtitle)
                <p class="adm-topbar__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="adm-topbar__right">
        <button type="button" class="adm-topbar-btn" id="quickDarkToggle" aria-label="Ganti mode gelap atau terang">
            <i class="bi bi-moon-fill adm-dm-icon-light" aria-hidden="true"></i>
            <i class="bi bi-sun-fill adm-dm-icon-dark" aria-hidden="true"></i>
        </button>

        <div class="dropdown">
            <button type="button" class="adm-user-pill" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun">
                <span class="adm-user-avatar">{{ $initial }}</span>
                <span class="adm-user-meta">
                    <span class="adm-user-name">{{ $user?->fullname ?? 'Pengguna' }}</span>
                    <span class="adm-user-role">{{ $roleBadge }}</span>
                </span>
                <i class="bi bi-chevron-down adm-user-caret" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end adm-dropdown">
                <li class="adm-dropdown__head">
                    <strong>{{ $user?->fullname }}</strong>
                    <span>{{ $user?->email }}</span>
                </li>
                <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="bi bi-gear" aria-hidden="true"></i> Pengaturan</a></li>
                <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Lihat Website</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" data-confirm="Apakah Anda yakin ingin keluar dari akun?">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
