<?php
?>

<!-- ===== GOOGLE FONTS ===== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- ===== NAVBAR CSS ===== -->
<style>
    :root {
        --sc-primary: #3a7ab3;
        /* biru tua  */
        --sc-accent: #4c8dc9;
        /* biru aksen */
        --primary-blue: #4c8dc9;
        --primary-dark: #3a7ab3;
        --sc-cta: #D62828;
        /* merah CTA (seperti AICOMS) */
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

    /* Right Side: Search + Login */
    .sc-navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Search Icon Button */
    .sc-search-toggle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1.5px solid #d0dff0;
        background: #f4f8fd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--sc-primary);
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        text-decoration: none;
    }

    .sc-search-toggle:hover {
        background: var(--sc-accent);
        border-color: var(--sc-accent);
        color: #fff;
    }

    /* Expanded Search Box (opsional, muncul saat toggle diklik) */
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

    /* Contact Us Button */
    .sc-contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
        color: var(--sc-primary) !important;
        font-family: var(--sc-nav-font);
        font-size: 13.5px;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        letter-spacing: 0.01em;
        border: 2px solid var(--sc-primary);
        transition: background 0.2s, color 0.2s, transform 0.15s, box-shadow 0.2s;
        white-space: nowrap;
    }

    .sc-contact-btn:hover {
        background: var(--sc-primary);
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 18px rgba(27, 63, 114, 0.22);
    }

    .sc-contact-btn i {
        font-size: 13px;
    }

    /* Login / CTA Button */
    .sc-login-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--sc-accent);
        color: #fff !important;
        font-family: var(--sc-nav-font);
        font-size: 13.5px;
        font-weight: 700;
        padding: 9px 22px;
        border-radius: 8px;
        text-decoration: none;
        letter-spacing: 0.01em;
        border: none;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 3px 12px rgba(33, 118, 174, 0.25);
        white-space: nowrap;
    }

    .sc-login-btn:hover {
        background: var(--sc-primary);
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 18px rgba(27, 63, 114, 0.32);
    }

    .sc-login-btn i {
        font-size: 13px;
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
        <a class="navbar-brand" href="index.php">
            <img src="img/logosc.png" alt="Smart City Logo">
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
                    <a class="nav-link active" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about-user">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/program-user">Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project-user">Proyek</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/news-user">Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/team-user">Tim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/team-user">Mitra</a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="sc-navbar-right">
                <!-- Search Box -->
                <div class="sc-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>

                <div class="sc-nav-divider"></div>

                <!-- Contact Us Button -->
                <a href="/contact" class="sc-contact-btn">
                    <i class="fas fa-envelope"></i>
                    Kontak Kami
                </a>

                <!-- Login Button -->
                <a href="/login" class="sc-login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
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
    document.querySelectorAll('.sc-navbar .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            document.querySelectorAll('.sc-navbar .nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        }
    });
</script>
