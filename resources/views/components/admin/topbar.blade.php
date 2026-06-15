@props(['title' => 'Dashboard', 'subtitle' => ''])

@php
    $user = auth()->user();
    $initial = strtoupper(substr($user?->fullname ?? $user?->name ?? 'U', 0, 1));
    $roleBadge = match($user?->role) {
        'admin'           => 'Admin',
        'content_creator' => 'Creator',
        'dosen'           => 'Dosen',
        default           => 'User',
    };
    $dashUrl = match($user?->role) {
        'admin'           => '/beranda-admin',
        'content_creator' => '/beranda-creator',
        default           => '/',
    };
@endphp

<header class="adm-topbar">
    {{-- Left --}}
    <div class="adm-topbar__left d-flex align-items-center gap-3">
        {{-- Mobile hamburger --}}
        <button class="adm-topbar-btn adm-mobile-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div>
            <h5>{{ $title }}</h5>
            @if($subtitle)
                <p>{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Right --}}
    <div class="adm-topbar__right">

        {{-- Dark mode quick toggle --}}
        <button class="adm-topbar-btn" id="quickDarkToggle" title="Toggle Dark Mode">
            <i class="bi bi-moon-fill adm-dm-icon-light"></i>
            <i class="bi bi-sun-fill adm-dm-icon-dark"></i>
        </button>

        {{-- Pengaturan --}}
        <a href="{{ route('admin.settings') }}" class="adm-topbar-btn" title="Pengaturan">
            <i class="bi bi-gear"></i>
        </a>

        {{-- User pill --}}
        <div class="adm-user-pill">
            <div class="adm-user-avatar">{{ $initial }}</div>
            <div>
                <div class="adm-user-name">{{ $user?->fullname ?? $user?->name ?? 'User' }}</div>
                <div style="font-size:11px; color:var(--adm-text-3);">{{ $roleBadge }}</div>
            </div>
        </div>

    </div>
</header>
