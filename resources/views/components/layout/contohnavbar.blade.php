{{-- =====================================================
     navbar.blade.php
     Lokasi: resources/views/components/layout/navbar.blade.php
     ===================================================== --}}

<style>
    /* ── Override tinggi navbar dari main.css (80px → 90px) ── */
    .navbar {
        padding: 0 !important;
        height: 90px;
    }

    .navbar .container {
        height: 90px;
        display: flex;
        align-items: center;
    }

    /* Logo lebih besar dari main.css (40px → 70px) */
    .navbar-brand img {
        height: 70px;
        width: auto;
        transition: height 0.3s ease;
    }

    .navbar.scrolled .navbar-brand img {
        height: 60px;
    }

    /* Nav link ukuran font sedikit lebih besar & jelas */
    .nav-link {
        font-size: 15px !important;
        padding: 0.5rem 0.9rem !important;
    }

    /* Search input lebar cukup */
    .search-box input {
        width: 150px;
    }

    /* Tombol Masuk */
    .login-btn {
        white-space: nowrap;
        font-size: 14px;
        padding: 0.5rem 1.5rem;
    }

    /* Mobile: collapse background putih */
    @media (max-width: 991px) {
        .navbar {
            height: auto !important;
            padding: 12px 0 !important;
        }
        .navbar .container {
            height: auto;
        }
        .navbar-collapse {
            background-color: #fff;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand" href="/">
            <img src="img/logosc.png" alt="Smart City Logo">
        </a>

        {{-- Hamburger mobile --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-label="Toggle navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="nav-and-search">

                {{-- Menu — Bahasa Indonesia + Mitra --}}
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about-user') ? 'active' : '' }}" href="/about-user">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('program-user') ? 'active' : '' }}" href="/program-user">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('project-user') ? 'active' : '' }}" href="/project-user">Proyek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('news-user') ? 'active' : '' }}" href="/news-user">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('team-user') ? 'active' : '' }}" href="/team-user">Tim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mitra</a>
                    </li>
                </ul>

                {{-- Search + Tombol Masuk --}}
                <div class="d-flex align-items-center">
                    <div class="search-box">
                        <input type="text" placeholder="Cari...">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="/login" class="login-btn">Masuk</a>
                </div>

            </div>
        </div>
    </div>
</nav>

<script>
    (function () {
        const nav = document.getElementById('mainNavbar');
        if (!nav) return;
        function onScroll() {
            nav.classList.toggle('scrolled', window.scrollY > 60);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    })();
</script>