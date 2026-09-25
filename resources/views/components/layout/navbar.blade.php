@php
    $user = auth()->user();
    $isDosen = $user && $user->role === 'dosen' && $user->registration_status === \App\Models\User::STATUS_APPROVED;
    $isManager = $user && in_array($user->role, ['admin', 'content_creator'], true);
    $area = $isDosen ? 'dosen' : 'user';
    $dashboardUrl = match ($user?->role) {
        'admin' => url('/beranda-admin'),
        'content_creator' => url('/beranda-creator'),
        default => url('/'),
    };
    $menu = [
        ['label' => 'Beranda', 'url' => $isDosen ? url('/beranda-dosen') : url('/'), 'active' => $isDosen ? ['beranda-dosen'] : ['/']],
        ['label' => 'Tentang Kami', 'url' => url("/about-{$area}"), 'active' => ["about-{$area}"]],
        ['label' => 'Program', 'url' => url("/program-{$area}"), 'active' => ["program-{$area}"]],
        ['label' => 'Proyek', 'url' => url("/project-{$area}"), 'active' => ["project-{$area}"]],
        ['label' => 'Berita', 'url' => url("/news-{$area}"), 'active' => ["news-{$area}", 'news/*']],
        ['label' => 'Publikasi', 'url' => url('/publication-user'), 'active' => ['publication-user', 'publications/*']],
        ['label' => 'Tim', 'url' => url("/team-{$area}"), 'active' => ["team-{$area}", 'biografi-*']],
        ['label' => 'Mitra', 'url' => url("/mitra-{$area}"), 'active' => ["mitra-{$area}"]],
    ];
    $socials = collect(config('smartcity.socials'))->filter(fn ($item) => ! empty($item['url']));
    $notifications = $isDosen ? $user->notifications()->latest()->limit(10)->get() : collect();
    $unreadCount = $isDosen ? $user->unreadNotifications()->count() : 0;
@endphp

<div class="sc-topbar">
    <div class="container">
        <div class="sc-topbar__info">
            <span class="d-none d-lg-inline-flex">
                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                {{ config('smartcity.address_short') }}
            </span>
            <a href="mailto:{{ config('smartcity.email') }}" class="d-none d-sm-inline-flex">
                <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                {{ config('smartcity.email') }}
            </a>
            <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                {{ config('smartcity.phone_display') }}
            </a>
        </div>
        <div class="sc-topbar__social">
            @foreach ($socials as $social)
                <a href="{{ $social['url'] }}" class="sc-social-btn" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                    <i class="bi {{ $social['icon'] }}" aria-hidden="true"></i>
                </a>
            @endforeach
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-xl sc-navbar" id="scNavbar" aria-label="Navigasi utama">
    <div class="container">
        <a class="navbar-brand" href="{{ $menu[0]['url'] }}">
            <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University" width="160" height="60">
        </a>

        <div class="sc-navbar__mobile-actions d-xl-none">
            @if ($isDosen)
                <a href="{{ route('profil.dosen') }}" class="sc-icon-btn" aria-label="Profil saya">
                    <i class="bi bi-person-circle" aria-hidden="true"></i>
                    @if ($unreadCount > 0)
                        <span class="sc-badge-dot" aria-hidden="true"></span>
                    @endif
                </a>
            @endif
            <button class="navbar-toggler sc-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Buka atau tutup menu">
                <span class="sc-toggler__bar"></span>
                <span class="sc-toggler__bar"></span>
                <span class="sc-toggler__bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav sc-navbar__menu">
                @foreach ($menu as $item)
                    @php $active = request()->is(...$item['active']); @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $active ? 'active' : '' }}" href="{{ $item['url'] }}" @if ($active) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="sc-navbar__right">
                <form action="{{ route('search') }}" method="GET" class="sc-search" role="search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}"
                        placeholder="Cari konten..." aria-label="Cari konten" maxlength="100" minlength="2">
                </form>

                @if ($isDosen)
                    <div class="dropdown sc-user-dropdown">
                        <button type="button" class="sc-icon-btn" id="notifDropdown" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifikasi">
                            <i class="bi bi-bell" aria-hidden="true"></i>
                            @if ($unreadCount > 0)
                                <span class="sc-badge-count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end sc-dropdown sc-notif" aria-labelledby="notifDropdown">
                            <div class="sc-notif__head">
                                <i class="bi bi-bell-fill" aria-hidden="true"></i> Notifikasi
                            </div>
                            <div class="sc-notif__list">
                                @forelse ($notifications as $notif)
                                    <a class="sc-notif__item {{ $notif->unread() ? 'is-unread' : '' }}" href="{{ route('dosen.notifications.read', $notif->id) }}">
                                        <span class="sc-notif__icon"><i class="bi bi-lightbulb" aria-hidden="true"></i></span>
                                        <span class="sc-notif__body">
                                            <span class="sc-notif__text">Anda ditambahkan sebagai pencipta HKI:</span>
                                            <strong class="sc-notif__title">{{ $notif->data['judul'] ?? \Illuminate\Support\Str::after($notif->data['message'] ?? '', ': ') }}</strong>
                                            <small class="sc-notif__time"><i class="bi bi-clock" aria-hidden="true"></i> {{ $notif->created_at->diffForHumans() }}</small>
                                        </span>
                                    </a>
                                @empty
                                    <div class="sc-notif__empty">
                                        <i class="bi bi-bell-slash" aria-hidden="true"></i>
                                        <p>Belum ada notifikasi baru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="dropdown sc-user-dropdown">
                        <button type="button" class="sc-avatar-btn dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun">
                            @if ($user->foto)
                                <img src="{{ asset('storage/'.$user->foto) }}" alt="" class="sc-avatar">
                            @else
                                <span class="sc-avatar sc-avatar--initial">{{ mb_strtoupper(mb_substr($user->fullname, 0, 1)) }}</span>
                            @endif
                            <span class="sc-avatar-btn__name">{{ \Illuminate\Support\Str::words($user->fullname, 2, '') }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end sc-dropdown" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profil.dosen') }}"><i class="bi bi-person" aria-hidden="true"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('dosen.publikasi.index') }}"><i class="bi bi-journal-text" aria-hidden="true"></i> Publikasi Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('dosen.hki.index') }}"><i class="bi bi-award" aria-hidden="true"></i> HKI Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form" data-confirm="Apakah Anda yakin ingin keluar dari akun?">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @elseif ($isManager)
                    <a href="{{ route('contact') }}" class="sc-btn sc-btn--outline" aria-label="Kontak Kami">
                        <i class="bi bi-envelope" aria-hidden="true"></i> <span class="sc-btn__label">Kontak Kami</span>
                    </a>
                    <a href="{{ $dashboardUrl }}" class="sc-btn sc-btn--primary">
                        <i class="bi bi-speedometer2" aria-hidden="true"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('contact') }}" aria-label="Kontak Kami" class="sc-btn sc-btn--outline {{ request()->routeIs('contact') ? 'is-active' : '' }}">
                        <i class="bi bi-envelope" aria-hidden="true"></i> <span class="sc-btn__label">Kontak Kami</span>
                    </a>
                    <a href="{{ route('login') }}" class="sc-btn sc-btn--primary">
                        <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>
