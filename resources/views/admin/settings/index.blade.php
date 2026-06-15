<x-layout.admin title="Pengaturan" subtitle="Kelola preferensi tampilan panel admin.">

    {{-- ── Profil ──────────────────────────────────────────── --}}
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
                <span>Role</span>
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

    {{-- ── Tampilan ─────────────────────────────────────────── --}}
    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-palette"></i> Tampilan
        </div>
        <p class="adm-settings-section__desc">
            Atur preferensi visual panel admin. Perubahan disimpan di browser.
        </p>

        {{-- Dark Mode Toggle --}}
        <label class="adm-dm-toggle" for="darkModeSwitch" id="darkModeLabel">
            <div class="adm-dm-toggle__left">
                <div class="adm-dm-toggle__icon">
                    <i class="bi bi-moon-stars-fill adm-dm-icon-light"></i>
                    <i class="bi bi-sun-fill adm-dm-icon-dark"></i>
                </div>
                <div>
                    <div class="adm-dm-toggle__label">Dark Mode</div>
                    <div class="adm-dm-toggle__sub" id="dmStatusText">
                        Aktifkan untuk tampilan gelap yang nyaman di malam hari.
                    </div>
                </div>
            </div>
            <label class="adm-switch" onclick="event.stopPropagation()">
                <input type="checkbox" id="darkModeSwitch">
                <span class="adm-switch-slider"></span>
            </label>
        </label>

        {{-- Sidebar color hint --}}
        <div class="mt-4">
            <div class="fw-semibold mb-2" style="font-size:13px; color:var(--adm-text-2);">
                Warna Aksen
            </div>
            <div class="adm-color-grid" id="accentGrid">
                <div class="adm-color-dot selected" data-accent="#4c8dc9" style="background:#4c8dc9;" title="Biru (default)"></div>
                <div class="adm-color-dot" data-accent="#7c3aed" style="background:#7c3aed;" title="Ungu"></div>
                <div class="adm-color-dot" data-accent="#0891b2" style="background:#0891b2;" title="Cyan"></div>
                <div class="adm-color-dot" data-accent="#16a34a" style="background:#16a34a;" title="Hijau"></div>
                <div class="adm-color-dot" data-accent="#d97706" style="background:#d97706;" title="Amber"></div>
                <div class="adm-color-dot" data-accent="#e11d48" style="background:#e11d48;" title="Merah"></div>
                <div class="adm-color-dot" data-accent="#475569" style="background:#475569;" title="Abu-abu"></div>
            </div>
        </div>
    </div>

    {{-- ── Sistem ───────────────────────────────────────────── --}}
    <div class="adm-settings-section">
        <div class="adm-settings-section__title">
            <i class="bi bi-info-circle"></i> Informasi Sistem
        </div>
        <p class="adm-settings-section__desc">Detail teknis aplikasi.</p>

        <ul class="adm-info-list">
            <li>
                <span>Aplikasi</span>
                <span>COE Smart City — CMS</span>
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

    {{-- ── Logout ───────────────────────────────────────────── --}}
    <div class="adm-settings-section">
        <div class="adm-settings-section__title" style="color:#ef4444;">
            <i class="bi bi-box-arrow-right" style="color:#ef4444;"></i> Sesi
        </div>
        <p class="adm-settings-section__desc">Keluar dari panel admin.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm"
                    style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;border-radius:8px;font-weight:600;padding:9px 20px;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout Sekarang
            </button>
        </form>
    </div>

</x-layout.admin>
