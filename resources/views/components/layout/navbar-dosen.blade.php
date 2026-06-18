<!-- ===== GOOGLE FONTS ===== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- ===== NAVBAR CSS (KONSISTEN DENGAN USER) ===== -->
<style>
    :root {
        --sc-primary: #3a7ab3;
        --sc-accent: #4c8dc9;
        --primary-blue: #4c8dc9;
        --primary-dark: #3a7ab3;
        --sc-cta: #D62828;
        --sc-cta-hover: #b01f1f;
        --sc-top-bg: #3a7ab3;
        --sc-top-text: #FFFFFF;
        --sc-nav-font: 'Plus Jakarta Sans', sans-serif;
    }

    /* ────────── TOP BAR ────────── */
    .sc-topbar {
        background: var(--sc-top-bg);
        font-family: var(--sc-nav-font);
        font-size: 12.5px;
        color: var(--sc-top-text);
        padding: 6px 0;
        letter-spacing: 0.01em;
    }

    .sc-topbar .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
    }

    .sc-topbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .sc-topbar-left span {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .sc-topbar-left i {
        color: var(--sc-top-text);
        font-size: 12px;
    }

    .sc-topbar-right {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sc-social-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        font-size: 11px;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }

    .sc-social-btn:hover {
        background: var(--sc-primary);
        color: #fff;
        border-color: var(--sc-primary);
    }

    /* ────────── MAIN NAVBAR ────────── */
    .navbar.sc-navbar {
        background: #fff;
        font-family: var(--sc-nav-font);
        padding: 0;
        border-bottom: 3px solid #e4edf8;
        box-shadow: 0 3px 20px rgba(27, 63, 114, 0.08);
        position: sticky;
        top: 0;
        z-index: 1030;
        transition: box-shadow 0.3s;
    }

    .navbar.sc-navbar.scrolled {
        box-shadow: 0 4px 28px rgba(27, 63, 114, 0.14);
    }

    .navbar.sc-navbar .container {
        height: 100px;
    }

    /* Logo */
    .sc-navbar .navbar-brand {
        display: flex;
        align-items: center;
        gap: 11px;
        text-decoration: none;
        padding: 0;
    }

    .sc-navbar .navbar-brand img {
        height: 60px;
        width: auto;
    }

    /* Nav Links */
    .sc-navbar .navbar-nav {
        gap: 2px;
    }

    .sc-navbar .nav-item .nav-link {
        font-size: 13.5px;
        font-weight: 700;
        color: #2c3e55;
        padding: 8px 13px;
        border-radius: 8px;
        position: relative;
        transition: color 0.2s, background 0.2s;
        letter-spacing: 0.01em;
    }

    .sc-navbar .nav-item .nav-link:hover,
    .sc-navbar .nav-item .nav-link.active {
        color: var(--sc-accent);
        background: #edf4fc;
    }

    .sc-navbar .nav-item .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 13px;
        right: 13px;
        height: 3px;
        background: var(--sc-accent);
        border-radius: 2px;
    }

    /* Right Side: Search + Profile */
    .sc-navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Search Box (Konsisten dengan User) */
    .sc-search-box {
        display: flex;
        align-items: center;
        background: #f4f8fd;
        border: 1.5px solid #d0dff0;
        border-radius: 24px;
        padding: 0 14px;
        height: 38px;
        gap: 8px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .sc-search-box:focus-within {
        border-color: var(--sc-accent);
        box-shadow: 0 0 0 3px rgba(33, 118, 174, 0.12);
    }

    .sc-search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-family: var(--sc-nav-font);
        font-size: 13px;
        color: #2c3e55;
        width: 160px;
    }

    .sc-search-box input::placeholder {
        color: #9ab0c8;
    }

    .sc-search-box i {
        color: #7a9bbf;
        font-size: 13px;
    }

    /* User Profile Dropdown (Style Konsisten) */
    .sc-user-dropdown {
        position: relative;
    }

    .sc-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid #d0dff0;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .sc-user-avatar:hover {
        border-color: var(--sc-accent);
        box-shadow: 0 0 0 3px rgba(33, 118, 174, 0.12);
    }

    /* Dropdown Menu Styling */
    .sc-dropdown-menu {
        border: 1px solid #d0dff0;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(27, 63, 114, 0.15);
        padding: 8px 0;
        font-family: var(--sc-nav-font);
        min-width: 180px;
    }

    .sc-dropdown-menu .dropdown-item {
        font-size: 13.5px;
        font-weight: 500;
        color: #2c3e55;
        padding: 10px 18px;
        transition: background 0.2s, color 0.2s;
    }

    .sc-dropdown-menu .dropdown-item:hover {
        background: #edf4fc;
        color: var(--sc-accent);
    }

    .sc-dropdown-menu .dropdown-item i {
        margin-right: 8px;
        font-size: 13px;
        width: 16px;
    }

    .sc-dropdown-divider {
        margin: 6px 0;
        border-top: 1px solid #e4edf8;
    }

    /* Hamburger (mobile) */
    .sc-navbar .navbar-toggler {
        border: 1.5px solid #c8d8ec;
        border-radius: 8px;
        padding: 6px 10px;
        color: var(--sc-primary);
    }

    .sc-navbar .navbar-toggler:focus {
        box-shadow: none;
    }

    .sc-navbar .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%231B3F72' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Divider antara nav links dan right side */
    .sc-nav-divider {
        width: 1px;
        height: 28px;
        background: #d8e8f4;
        margin: 0 6px;
    }

    /* FORCE: Mitra Active State */
    .sc-navbar .nav-link[href*="mitra"]:not(.active),
    .sc-navbar .nav-link[href*="partners"]:not(.active) {
        color: #2c3e55;
        background: transparent;
    }

    .sc-navbar .nav-link[href*="mitra"].active,
    .sc-navbar .nav-link[href*="partners"].active {
        color: var(--sc-accent) !important;
        background: #edf4fc !important;
    }

    .sc-navbar .nav-link[href*="mitra"].active::after,
    .sc-navbar .nav-link[href*="partners"].active::after {
        content: '' !important;
        position: absolute !important;
        bottom: -3px !important;
        left: 13px !important;
        right: 13px !important;
        height: 3px !important;
        background: #4c8dc9 !important;
        border-radius: 2px !important;
        display: block !important;
        z-index: 10 !important;
    }

    /* Manual underline untuk Mitra */
    .mitra-active-line {
        position: absolute !important;
        bottom: -3px !important;
        left: 13px !important;
        right: 13px !important;
        height: 3px !important;
        background: #4c8dc9 !important;
        border-radius: 2px !important;
        pointer-events: none !important;
        z-index: 10 !important;
    }

    /* ────────── RESPONSIVE ────────── */
    @media (max-width: 991px) {
        .sc-topbar-left {
            flex-wrap: wrap;
            gap: 10px;
        }

        .sc-navbar .container {
            height: auto;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .sc-navbar .navbar-collapse {
            padding: 12px 0 16px;
            border-top: 1px solid #e4edf8;
            margin-top: 10px;
        }

        .sc-navbar .nav-item .nav-link {
            padding: 10px 8px;
            border-radius: 0;
            border-bottom: 1px solid #edf4fc;
        }

        .sc-navbar .nav-item .nav-link.active::after {
            display: none;
        }

        .sc-navbar-right {
            margin-top: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .sc-search-box input {
            width: 100%;
        }

        .sc-search-box {
            flex: 1;
        }

        .sc-nav-divider {
            display: none;
        }

        /* Mobile User Avatar */
        .sc-user-dropdown {
            margin-top: 10px;
        }
    }
</style>

<!-- ===== TOP BAR ===== -->
<div class="sc-topbar">
    <div class="container">
        <div class="sc-topbar-left">
            <span>
                <i class="fas fa-map-marker-alt"></i>
                Jl. Smart City No. 1, Indonesia
            </span>
            <span>
                <i class="fas fa-envelope"></i>
                smartcity@example.ac.id
            </span>
        </div>
        <div class="sc-topbar-right">
            <a href="#" class="sc-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="sc-social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="#" class="sc-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="sc-social-btn" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</div>

<!-- ===== MAIN NAVBAR ===== -->
<nav class="navbar navbar-expand-lg sc-navbar" id="scNavbar">
    <div class="container">

        <!-- Brand / Logo -->
        <a class="navbar-brand" href="/beranda-dosen">
            <img src="{{ asset('img/logosc.png') }}" alt="Smart City Logo">
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/beranda-dosen">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about-dosen">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/program-dosen">Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project-dosen">Proyek</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/news-dosen">Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/publication-user">Publikasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/team-dosen">Tim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('partners.dosen') }}"
                        data-debug-href="{{ route('partners.dosen') }}">Mitra</a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="sc-navbar-right">
                <!-- Search Box -->
                <div class="sc-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari...">
                </div>

                <div class="sc-nav-divider"></div>

                <!-- User Profile Dropdown -->
                <div class="sc-user-dropdown dropdown">
                    <img src="{{ asset('img/rayyan.jpg') }}" class="sc-user-avatar dropdown-toggle" id="userDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" alt="User Avatar">
                    <ul class="dropdown-menu sc-dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="/profil-dosen">
                                <i class="fas fa-user"></i>
                                Profil
                            </a>
                        </li>
                        <li>
                            <hr class="sc-dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</nav>

<!-- ===== JS: Scroll shadow + active nav ===== -->
<script>
    // Shadow saat scroll
    const scNav = document.getElementById('scNavbar');
    window.addEventListener('scroll', () => {
        scNav.classList.toggle('scrolled', window.scrollY > 10);
    });

    // Set active link berdasarkan URL saat ini
    const currentPath = window.location.pathname;
    let activeSet = false;

    console.log('Current path (Dosen):', currentPath);

    document.querySelectorAll('.sc-navbar .nav-link').forEach(link => {
        const href = link.getAttribute('href');

        // Hapus active dari semua link terlebih dahulu
        link.classList.remove('active');

        // DEBUG: Log semua link dan href
        console.log('Link text (Dosen):', link.textContent.trim(), 'href:', href, 'data-debug-href:', link
            .getAttribute('data-debug-href'));

        // Pencocokan sederhana - exact match first  
        if (href === currentPath) {
            link.classList.add('active');
            activeSet = true;
            console.log('Active set by exact match (Dosen):', href);
        }
    });

    // FORCE: Jika di halaman publikasi, pastikan active state  
    if (currentPath === '/publication-user') {
        const publikasiLink = document.querySelector('.sc-navbar .nav-link[href="/publication-user"]');
        if (publikasiLink) {
            publikasiLink.classList.add('active');
            activeSet = true;
            console.log('Force active: Publikasi (Dosen)');
        }
    }

    // FORCE: Jika di halaman mitra, pastikan active state  
    if (currentPath === '/mitra-dosen' || currentPath.includes('partners')) {
        // Cari link dengan teks "Mitra"
        const mitraLink = Array.from(document.querySelectorAll('.sc-navbar .nav-link'))
            .find(link => link.textContent.trim() === 'Mitra');
        if (mitraLink) {
            mitraLink.classList.add('active');
            activeSet = true;
            console.log('Force active: Mitra (Dosen)');
        }
    }

    // Jika tidak ada yang cocok dan di halaman root, set beranda sebagai active
    if (!activeSet && (currentPath === '/' || currentPath === '/beranda-dosen')) {
        const berandaLink = document.querySelector(
            '.sc-navbar .nav-link[href="/"], .sc-navbar .nav-link[href="/beranda-dosen"]');
        if (berandaLink) {
            berandaLink.classList.add('active');
        }
    }
</script>

<!-- ===== ENHANCED MITRA ACTIVE STATE FIX FOR DOSEN ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        console.log('🔧 ENHANCED MITRA FIX DOSEN - Current path:', currentPath);

        // Khusus untuk halaman mitra dosen
        if (currentPath === '/mitra-dosen') {
            console.log('🎯 Detected mitra-dosen page');

            // Hapus semua active terlebih dahulu
            document.querySelectorAll('.sc-navbar .nav-link').forEach(link => {
                link.classList.remove('active');
                console.log('Removed active from:', link.textContent.trim());
            });

            // Cari dan aktifkan menu Mitra dengan berbagai cara
            let mitraActivated = false;

            // Metode 1: Cari berdasarkan teks "Mitra"
            const mitraByText = Array.from(document.querySelectorAll('.sc-navbar .nav-link'))
                .find(link => link.textContent.trim() === 'Mitra');

            if (mitraByText) {
                mitraByText.classList.add('active');
                // Force inline styles untuk memastikan terlihat
                mitraByText.style.setProperty('color', 'var(--sc-accent)', 'important');
                mitraByText.style.setProperty('background', '#edf4fc', 'important');
                mitraByText.style.setProperty('position', 'relative', 'important');

                // Tambahkan garis biru dibawah secara manual
                const existingLine = mitraByText.querySelector('.mitra-active-line');
                if (!existingLine) {
                    const activeLine = document.createElement('div');
                    activeLine.className = 'mitra-active-line';
                    activeLine.style.cssText = `
                        position: absolute !important;
                        bottom: -3px !important;
                        left: 13px !important;
                        right: 13px !important;
                        height: 3px !important;
                        background: var(--sc-accent) !important;
                        border-radius: 2px !important;
                        pointer-events: none !important;
                    `;
                    mitraByText.appendChild(activeLine);
                }

                mitraActivated = true;
                console.log('✅ MITRA DOSEN ACTIVATED by text with forced styles and underline!');
            }

            // Metode 2: Cari berdasarkan href yang mengandung 'partners' atau 'mitra'
            if (!mitraActivated) {
                const mitraByHref = Array.from(document.querySelectorAll('.sc-navbar .nav-link'))
                    .find(link => {
                        const href = link.getAttribute('href') || '';
                        return href.includes('partners') || href.includes('mitra');
                    });

                if (mitraByHref) {
                    mitraByHref.classList.add('active');
                    mitraActivated = true;
                    console.log('✅ MITRA DOSEN ACTIVATED by href!');
                }
            }

            // Metode 3: Force activation jika masih belum berhasil
            if (!mitraActivated) {
                setTimeout(() => {
                    const allLinks = document.querySelectorAll('.sc-navbar .nav-link');
                    console.log('Available links (Dosen):', Array.from(allLinks).map(l => l.textContent
                        .trim()));

                    // Cari link terakhir (biasanya Mitra)
                    const lastLink = allLinks[allLinks.length - 1];
                    if (lastLink && lastLink.textContent.trim() === 'Mitra') {
                        lastLink.classList.add('active');
                        console.log('✅ MITRA DOSEN ACTIVATED by last link!');
                    }
                }, 100);
            }
        }
    });
</script>
