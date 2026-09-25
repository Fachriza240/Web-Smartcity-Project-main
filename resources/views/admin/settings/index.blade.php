<x-layout.admin title="Pengaturan" subtitle="Kelola preferensi tampilan panel admin.">

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-person-circle"></i> Profil Akun
        </div>
        <p class="adm-settings-section__desc">Informasi akun yang sedang aktif.</p>

        <div class="adm-profile-card mb-3">
            <div class="adm-profile-avatar">
                {{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <div class="adm-profile-name">
                    {{ auth()->user()->fullname ?? auth()->user()->name ?? '-' }}
                </div>
                <span class="adm-profile-role">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                </span>
            </div>
        </div>

        <ul class="adm-info-list">
            <li>
                <span>Email</span>
                <span>{{ auth()->user()->email }}</span>
            </li>
            <li>
                <span>Peran</span>
                <span>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
            </li>
            @if(auth()->user()->nip)
                <li>
                    <span>NIP</span>
                    <span>{{ auth()->user()->nip }}</span>
                </li>
            @endif
            <li>
                <span>Bergabung</span>
                <span>{{ auth()->user()->created_at?->format('d M Y') }}</span>
            </li>
        </ul>
    </div>

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-palette"></i> Tampilan
        </div>
        <p class="adm-settings-section__desc">
            Atur preferensi visual panel admin. Perubahan disimpan di browser.
        </p>

        <label class="adm-dm-toggle" for="darkModeSwitch" id="darkModeLabel">
            <div class="adm-dm-toggle__left">
                <div class="adm-dm-toggle__icon">
                    <i class="bi bi-moon-stars-fill adm-dm-icon-light"></i>
                    <i class="bi bi-sun-fill adm-dm-icon-dark"></i>
                </div>
                <div>
                    <div class="adm-dm-toggle__label">Mode Gelap</div>
                    <div class="adm-dm-toggle__sub" id="dmStatusText">
                        Aktifkan untuk tampilan gelap yang nyaman di malam hari.
                    </div>
                </div>
            </div>
            <span class="adm-switch">
                <input type="checkbox" id="darkModeSwitch">
                <span class="adm-switch-slider"></span>
            </span>
        </label>

        <div class="mt-4">
            <div class="adm-settings-label">
                Warna Aksen
            </div>
            <div class="adm-color-grid" id="accentGrid">
                <button type="button" class="adm-color-dot selected" data-accent="#4c8dc9" data-color="#4c8dc9" title="Biru (default)" aria-label="Warna aksen Biru (default)"></button>
                <button type="button" class="adm-color-dot" data-accent="#7c3aed" data-color="#7c3aed" title="Ungu" aria-label="Warna aksen Ungu"></button>
                <button type="button" class="adm-color-dot" data-accent="#0891b2" data-color="#0891b2" title="Cyan" aria-label="Warna aksen Cyan"></button>
                <button type="button" class="adm-color-dot" data-accent="#16a34a" data-color="#16a34a" title="Hijau" aria-label="Warna aksen Hijau"></button>
                <button type="button" class="adm-color-dot" data-accent="#d97706" data-color="#d97706" title="Amber" aria-label="Warna aksen Amber"></button>
                <button type="button" class="adm-color-dot" data-accent="#e11d48" data-color="#e11d48" title="Merah" aria-label="Warna aksen Merah"></button>
                <button type="button" class="adm-color-dot" data-accent="#475569" data-color="#475569" title="Abu-abu" aria-label="Warna aksen Abu-abu"></button>
            </div>
        </div>
    </div>

    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-info-circle"></i> Informasi Sistem
        </div>
        <p class="adm-settings-section__desc">Detail teknis aplikasi.</p>

        <ul class="adm-info-list">
            <li>
                <span>Aplikasi</span>
                <span>CoE Smart City CMS</span>
            </li>
            <li>
                <span>Framework</span>
                <span>Laravel {{ app()->version() }}</span>
            </li>
            <li>
                <span>PHP</span>
                <span>{{ PHP_VERSION }}</span>
            </li>
            <li>
                <span>Lingkungan</span>
                <span>{{ ucfirst(app()->environment()) }}</span>
            </li>
            <li>
                <span>Timezone</span>
                <span>{{ config('app.timezone') }}</span>
            </li>
        </ul>
    </div>

    <div class="adm-settings-section">
        <div class="adm-settings-section__title adm-text-danger">
            <i class="bi bi-box-arrow-right"></i> Sesi
        </div>
        <p class="adm-settings-section__desc">Keluar dari panel admin.</p>
        <form action="{{ route('logout') }}" method="POST" data-confirm="Apakah Anda yakin ingin keluar dari akun?">
            @csrf
            <button type="submit" class="adm-btn-soft adm-btn-soft--danger adm-btn-soft--lg">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar Sekarang
            </button>
        </form>
    </div>

</x-layout.admin>
