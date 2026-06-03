{{--
=================================================================
  home.blade.php
  Lokasi : resources/views/components/layout/home.blade.php
  Dipanggil via: <x-layout.home></x-layout.home>
  (ada di resources/views/halaman-user/beranda-user.blade.php)

  ── URUTAN SECTION ──────────────────────────────────────────
  1. [HERO]         Hero baru: foto tim, teks, CTA, gelombang
  2. [TENTANG]      Tentang Kami + Aktifitas
  3. [FAKTA]        Statistik counter + tab proyek
  4. [ABOUT]        Section About asli (dipertahankan)
  5. [CARDS]        4 kartu program (dipertahankan)
  6. [GALLERY]      Carousel proyek (dipertahankan)

  ── PENJELASAN CSS YANG DIPAKAI PROJECT INI ─────────────────

  Project ini memakai 3 cara penulisan CSS:

  1. EXTERNAL CSS — public/css/main.css
     Dimuat di link.blade.php via <link href="css/main.css">
     Berisi style global: font, warna, navbar, about-section,
     cards, gallery, footer, scroll-top, dll.
     → Gunakan ini untuk style yang dipakai di banyak halaman.

  2. BOOTSTRAP (juga External, dari CDN)
     Class siap pakai: container, row, col-lg-6, d-flex,
     text-center, fixed-top, g-4, mb-5, dll.
     → Gunakan untuk layout, grid, dan utilitas umum.
     → Tidak perlu tulis CSS manual untuk hal-hal ini.

  3. INTERNAL CSS — tag <style> di dalam file blade ini
     Untuk style section BARU yang belum ada di main.css
     (Hero baru, Tentang, Fakta).
     → Jika project makin berkembang, pindahkan ke main.css.

  CATATAN: Inline CSS (style="...") dihindari sebisa mungkin,
  hanya dipakai untuk kondisi darurat (contoh: onerror foto).
=================================================================
--}}


{{-- ============================================================
     INTERNAL CSS
     Hanya untuk section baru: Hero, Tentang, Fakta.
     Section lama (About, Cards, Gallery) sudah ada di main.css.
     ============================================================ --}}
<style>
    /* ── Variabel warna (sinkron dengan main.css) ──────────── */
    :root {
        --primary-blue  : #4c8dc9;
        --primary-dark  : #3a7ab3;
        --text-black    : #000000;
        --text-gray     : #7b7b7b;
        --bg-light      : #f8f9fa;
        --nav-height    : 80px;   /* sesuai tinggi navbar */
    }

    /* ══════════════════════════════════════════════════════════
       [HERO] Styles
       ══════════════════════════════════════════════════════════ */

    /* Wrapper section hero — background putih, full-height */
    .sc-hero {
        min-height     : 100vh;
        background     : #ffffff;
        position       : relative;
        display        : flex;
        align-items    : center;
        overflow       : hidden;
        padding-top    : var(--nav-height);
    }

    /* Aksen: lingkaran biru besar di kanan atas (dekoratif) */
    .sc-hero__circle-lg {
        position      : absolute;
        top           : -80px;
        right         : -100px;
        width         : 600px;
        height        : 600px;
        background    : radial-gradient(
                          circle,
                          rgba(76,141,201,0.11) 0%,
                          rgba(76,141,201,0.03) 60%,
                          transparent 80%
                        );
        border-radius : 50%;
        pointer-events: none;
    }

    /* Aksen: lingkaran biru kecil di kiri bawah (dekoratif) */
    .sc-hero__circle-sm {
        position      : absolute;
        bottom        : 60px;
        left          : -80px;
        width         : 280px;
        height        : 280px;
        background    : radial-gradient(
                          circle,
                          rgba(76,141,201,0.07) 0%,
                          transparent 70%
                        );
        border-radius : 50%;
        pointer-events: none;
    }

    /* Aksen: pola titik-titik biru di area kanan atas */
    .sc-hero__dots {
        position         : absolute;
        inset            : 0;
        background-image : radial-gradient(rgba(76,141,201,0.14) 1.5px, transparent 1.5px);
        background-size  : 36px 36px;
        mask-image       : radial-gradient(
                             ellipse 50% 65% at 85% 25%,
                             black 10%, transparent 80%
                           );
        pointer-events   : none;
    }

    /* Aksen: objek kecil melayang (animasi naik-turun) */
    .sc-hero__geo {
        position      : absolute;
        border-radius : 50%;
        pointer-events: none;
        animation     : heroGeoFloat ease-in-out infinite;
    }
    .sc-hero__geo--a { width:16px; height:16px; background:var(--primary-blue); opacity:.22; top:22%; left:7%;  animation-duration:5s; }
    .sc-hero__geo--b { width: 9px; height: 9px; background:var(--primary-blue); opacity:.16; top:65%; left:14%; animation-duration:7s; animation-delay:-2s; }
    .sc-hero__geo--c { width:12px; height:12px; background:var(--primary-blue); opacity:.18; top:42%; right:6%; animation-duration:6s; animation-delay:-3s; }
    .sc-hero__geo--d { width: 7px; height: 7px; background:#a8d0f0;             opacity:.30; top:78%; right:22%;animation-duration:9s; animation-delay:-1s; }
    @keyframes heroGeoFloat {
        0%,100% { transform: translateY(0)    rotate(0deg);  }
        50%     { transform: translateY(-16px) rotate(18deg); }
    }

    /* Grid 2 kolom dalam hero: teks kiri, foto kanan */
    .sc-hero__grid {
        display              : grid;
        grid-template-columns: 1fr 1fr;
        gap                  : 3rem;
        align-items          : center;
        width                : 100%;
        max-width            : 1200px;
        margin               : 0 auto;
        padding              : 4rem 2rem 6rem;
        position             : relative;
        z-index              : 2;
    }

    /* Badge pill "Universitas Telkom" di atas judul */
    .sc-hero__badge {
        display        : inline-flex;
        align-items    : center;
        gap            : 8px;
        background     : rgba(76,141,201,0.1);
        border         : 1px solid rgba(76,141,201,0.3);
        color          : var(--primary-blue);
        font-size      : 11.5px;
        font-weight    : 600;
        letter-spacing : 2px;
        text-transform : uppercase;
        padding        : 7px 16px;
        border-radius  : 100px;
        margin-bottom  : 1.25rem;
        font-family    : "Spline Sans", sans-serif;
    }
    .sc-hero__badge-dot {
        width         : 6px;
        height        : 6px;
        background    : var(--primary-blue);
        border-radius : 50%;
        animation     : badgeDotPulse 2s infinite;
    }
    @keyframes badgeDotPulse {
        0%,100% { opacity:1;  transform:scale(1);  }
        50%     { opacity:.4; transform:scale(.7); }
    }

    /* Judul utama H1 */
    .sc-hero__h1 {
        font-size    : clamp(2.2rem, 4.5vw, 3.5rem);
        font-weight  : 700;
        color        : var(--text-black);
        line-height  : 1.1;
        margin-bottom: 0.5rem;
        font-family  : "Spline Sans", sans-serif;
    }
    .sc-hero__h1 .text-blue { color: var(--primary-blue); }

    /* Garis pendek biru pemisah (seperti referensi AICOMS) */
    .sc-hero__divider {
        width         : 48px;
        height        : 3px;
        background    : var(--primary-blue);
        border-radius : 2px;
        margin        : 1.25rem 0;
    }

    /* Sub-judul di bawah garis */
    .sc-hero__subtitle {
        font-size    : 1.25rem;
        font-weight  : 700;
        color        : var(--text-black);
        margin-bottom: 0.5rem;
        font-family  : "Spline Sans", sans-serif;
    }

    /* Paragraf deskripsi */
    .sc-hero__desc {
        font-size    : 15px;
        color        : var(--text-gray);
        line-height  : 1.75;
        margin-bottom: 2.25rem;
        max-width    : 450px;
        font-family  : "Lato", sans-serif;
    }

    /* Grup tombol CTA */
    .sc-hero__cta {
        display    : flex;
        align-items: center;
        gap        : 1.5rem;
        flex-wrap  : wrap;
    }

    /* Tombol "Proyek Kami" */
    .sc-btn-proyek {
        display        : inline-flex;
        align-items    : center;
        gap            : 10px;
        background     : var(--primary-blue);
        color          : #fff;
        text-decoration: none;
        padding        : 13px 26px;
        border-radius  : 30px;
        font-weight    : 700;
        font-size      : 15px;
        font-family    : "Spline Sans", sans-serif;
        transition     : all 0.3s;
        box-shadow     : 0 6px 20px rgba(76,141,201,0.35);
    }
    .sc-btn-proyek:hover {
        background : var(--primary-dark);
        transform  : translateY(-2px);
        box-shadow : 0 10px 28px rgba(76,141,201,0.45);
        color      : #fff;
    }
    .sc-btn-proyek__arrow {
        width          : 30px;
        height         : 30px;
        background     : rgba(255,255,255,0.25);
        border-radius  : 50%;
        display        : flex;
        align-items    : center;
        justify-content: center;
        font-size      : 14px;
    }

    /* Tombol WhatsApp */
    .sc-btn-wa {
        display        : inline-flex;
        align-items    : center;
        gap            : 12px;
        text-decoration: none;
        color          : var(--text-black);
        transition     : color 0.3s;
    }
    .sc-btn-wa:hover { color: var(--primary-blue); }
    .sc-btn-wa__icon {
        width          : 48px;
        height         : 48px;
        background     : #eef6ff;
        border         : 2px solid rgba(76,141,201,0.2);
        border-radius  : 50%;
        display        : flex;
        align-items    : center;
        justify-content: center;
        font-size      : 20px;
        color          : var(--primary-blue);
        transition     : all 0.3s;
        flex-shrink    : 0;
    }
    .sc-btn-wa:hover .sc-btn-wa__icon {
        background   : var(--primary-blue);
        color        : #fff;
        border-color : var(--primary-blue);
    }
    .sc-btn-wa__label  { font-size:11px; color:var(--text-gray); font-family:"Lato",sans-serif; line-height:1; }
    .sc-btn-wa__number { font-size:15px; font-weight:700; color:var(--text-black); font-family:"Spline Sans",sans-serif; }

    /* Kolom foto tim (kanan hero) */
    .sc-hero__photo-col {
        position       : relative;
        display        : flex;
        justify-content: center;
        align-items    : center;
    }

    /* Blob aksen di belakang foto — animasi morph */
    .sc-hero__blob {
        position      : absolute;
        right         : -20px;
        top           : 8%;
        width         : 84%;
        height        : 82%;
        background    : linear-gradient(135deg, var(--primary-blue), #1a4f80);
        border-radius : 50% 40% 50% 42%;
        z-index       : 0;
        opacity       : 0.1;
        animation     : blobMorph 9s ease-in-out infinite alternate;
    }
    @keyframes blobMorph {
        0%   { border-radius: 50% 40% 50% 42%; }
        100% { border-radius: 42% 55% 40% 55%; }
    }

    /* Foto tim berbentuk bulat */
    .sc-hero__photo {
        position       : relative;
        z-index        : 1;
        width          : 88%;
        aspect-ratio   : 1;
        border-radius  : 50%;
        object-fit     : cover;
        object-position: center top;
        box-shadow     :
            0 20px 60px rgba(76,141,201,0.2),
            0 0 0 8px  rgba(76,141,201,0.08),
            0 0 0 16px rgba(76,141,201,0.04);
        display        : block;
    }

    /* Placeholder bila foto belum ada / gagal load */
    .sc-hero__photo-placeholder {
        position       : relative;
        z-index        : 1;
        width          : 88%;
        aspect-ratio   : 1;
        border-radius  : 50%;
        background     : linear-gradient(135deg, #e8f4ff, #c5dff5);
        display        : none;          /* ditampilkan via onerror JS */
        align-items    : center;
        justify-content: center;
        flex-direction : column;
        gap            : 8px;
        border         : 3px solid rgba(76,141,201,0.15);
        box-shadow     : 0 20px 60px rgba(76,141,201,0.12);
    }
    .sc-hero__photo-placeholder i    { font-size:48px; color:rgba(76,141,201,0.35); }
    .sc-hero__photo-placeholder span { font-size:12px; color:rgba(76,141,201,0.45); text-align:center; font-family:"Lato",sans-serif; }

    /* Gelombang SVG di bawah hero — transisi ke section Tentang */
    .sc-hero__wave {
        position   : absolute;
        bottom     : -1px;
        left       : 0;
        right      : 0;
        line-height: 0;
        z-index    : 3;
    }
    .sc-hero__wave svg { display:block; }


    /* ══════════════════════════════════════════════════════════
       [TENTANG] Styles
       ══════════════════════════════════════════════════════════ */
    .sc-tentang {
        background: var(--bg-light);
        padding   : 90px 0;
        position  : relative;
    }

    /* Label section bergaya "• LABEL •" */
    .sc-label {
        display        : flex;
        align-items    : center;
        gap            : 8px;
        font-size      : 11.5px;
        font-weight    : 700;
        letter-spacing : 2.5px;
        text-transform : uppercase;
        color          : var(--primary-blue);
        margin-bottom  : 1rem;
        font-family    : "Spline Sans", sans-serif;
    }
    .sc-label::before, .sc-label::after { content:'•'; font-size:14px; }

    .sc-tentang__heading {
        font-size    : clamp(1.8rem, 3.5vw, 2.7rem);
        font-weight  : 700;
        color        : var(--text-black);
        margin-bottom: 1.25rem;
        line-height  : 1.15;
        font-family  : "Spline Sans", sans-serif;
    }
    .sc-tentang__desc {
        font-size    : 16px;
        line-height  : 1.8;
        color        : var(--text-gray);
        margin-bottom: 2rem;
        max-width    : 530px;
        font-family  : "Lato", sans-serif;
    }

    /* Tombol Pelajari Lebih Lanjut */
    .sc-btn-pelajari {
        display        : inline-flex;
        align-items    : center;
        gap            : 10px;
        background     : var(--primary-blue);
        color          : #fff;
        text-decoration: none;
        padding        : 13px 26px;
        border-radius  : 30px;
        font-weight    : 700;
        font-size      : 15px;
        font-family    : "Spline Sans", sans-serif;
        box-shadow     : 0 6px 20px rgba(76,141,201,0.3);
        transition     : all 0.3s;
    }
    .sc-btn-pelajari:hover { background:var(--primary-dark); transform:translateY(-2px); color:#fff; }
    .sc-btn-pelajari__arrow {
        width          : 28px;
        height         : 28px;
        background     : rgba(255,255,255,0.25);
        border-radius  : 50%;
        display        : flex;
        align-items    : center;
        justify-content: center;
        font-size      : 13px;
    }

    /* Baris item aktifitas */
    .sc-akt__row {
        display    : flex;
        align-items: center;
        gap        : 18px;
        margin-bottom: 1.4rem;
    }
    .sc-akt__icon {
        width          : 52px;
        height         : 52px;
        border-radius  : 50%;
        background     : linear-gradient(135deg, var(--primary-blue), #2b5c8e);
        display        : flex;
        align-items    : center;
        justify-content: center;
        font-size      : 22px;
        color          : #fff;
        flex-shrink    : 0;
        box-shadow     : 0 4px 14px rgba(76,141,201,0.3);
    }
    .sc-akt__text {
        font-size  : 16px;
        font-weight: 600;
        color      : var(--text-black);
        font-family: "Spline Sans", sans-serif;
        line-height: 1.35;
    }
    .sc-akt__text small {
        font-family: "Lato", sans-serif;
        font-weight: 400;
        color      : var(--text-gray);
        font-size  : 13px;
    }


    /* ══════════════════════════════════════════════════════════
       [FAKTA] Styles
       ══════════════════════════════════════════════════════════ */
    .sc-fakta {
        background: #ffffff;
        padding   : 90px 0;
    }
    .sc-fakta__heading {
        font-size    : clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight  : 700;
        color        : var(--text-black);
        text-align   : center;
        margin-bottom: 0.5rem;
        font-family  : "Spline Sans", sans-serif;
    }

    /* Grid angka statistik */
    .sc-fakta__stats {
        display              : grid;
        grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
        gap                  : 2.5rem 2rem;
        margin               : 2.75rem 0 3.5rem;
    }
    .sc-fakta__num {
        font-size    : clamp(2.4rem, 5vw, 3.8rem);
        font-weight  : 700;
        color        : var(--text-black);
        line-height  : 1;
        margin-bottom: 6px;
        font-family  : "Spline Sans", sans-serif;
    }
    .sc-fakta__num .plus { color: var(--primary-blue); }
    .sc-fakta__lbl {
        font-size     : 11px;
        font-weight   : 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color         : var(--primary-blue);
        display       : flex;
        align-items   : center;
        gap           : 5px;
        font-family   : "Spline Sans", sans-serif;
    }
    .sc-fakta__lbl::before { content:'•'; font-size:14px; }

    /* Baris tombol tab */
    .sc-fakta__tabbar {
        display    : flex;
        gap        : 12px;
        margin-bottom: 1.75rem;
        flex-wrap  : wrap;
    }
    .sc-fakta__tab {
        padding      : 11px 22px;
        border-radius: 30px;
        border       : none;
        font-family  : "Spline Sans", sans-serif;
        font-size    : 14px;
        font-weight  : 600;
        cursor       : pointer;
        transition   : all 0.25s;
    }
    /* Tab aktif = hitam gelap (sesuai referensi) */
    .sc-fakta__tab.active           { background:#222222; color:#fff; }
    /* Tab non-aktif = biru */
    .sc-fakta__tab:not(.active)     { background:var(--primary-blue); color:#fff; }
    .sc-fakta__tab:not(.active):hover { background:var(--primary-dark); }

    /* Panel konten tab */
    .sc-fakta__pane        { display:none; }
    .sc-fakta__pane.active { display:block; }

    /* Daftar item proyek dalam tab */
    .sc-fakta__list {
        list-style           : none;
        padding              : 0;
        margin               : 0;
        display              : grid;
        grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
        gap                  : 10px;
    }
    .sc-fakta__list li {
        display      : flex;
        align-items  : center;
        gap          : 10px;
        font-size    : 15px;
        font-weight  : 500;
        color        : var(--text-black);
        font-family  : "Lato", sans-serif;
        padding      : 12px 16px;
        background   : var(--bg-light);
        border-radius: 8px;
        border-left  : 3px solid var(--primary-blue);
        transition   : all 0.2s;
    }
    .sc-fakta__list li:hover {
        background  : #e8f4ff;
        border-color: var(--primary-dark);
        transform   : translateX(4px);
    }
    .sc-fakta__list li::before {
        content      : '';
        width        : 6px;
        height       : 6px;
        background   : var(--primary-blue);
        border-radius: 50%;
        flex-shrink  : 0;
    }


    /* ══════════════════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════════════════ */
    @media (max-width: 991px) {
        /* Hero: satu kolom, sembunyikan foto di kanan */
        .sc-hero__grid    { grid-template-columns:1fr; padding:3rem 1.5rem 6rem; }
        .sc-hero__photo-col { display:none; }
    }
    @media (max-width: 576px) {
        /* CTA hero susun ke bawah di HP kecil */
        .sc-hero__cta     { flex-direction:column; align-items:flex-start; }
        /* Statistik 2 kolom di HP */
        .sc-fakta__stats  { grid-template-columns:1fr 1fr; }
    }
</style>


{{-- ============================================================
     [SECTION 1 — HERO]
     Foto tim kanan, teks + CTA kiri, gelombang bawah
     ============================================================ --}}
<section class="sc-hero" id="beranda">

    {{-- Elemen dekoratif latar (tidak interaktif) --}}
    <div class="sc-hero__circle-lg"></div>
    <div class="sc-hero__circle-sm"></div>
    <div class="sc-hero__dots"></div>
    <div class="sc-hero__geo sc-hero__geo--a"></div>
    <div class="sc-hero__geo sc-hero__geo--b"></div>
    <div class="sc-hero__geo sc-hero__geo--c"></div>
    <div class="sc-hero__geo sc-hero__geo--d"></div>

    {{-- Grid 2 kolom utama --}}
    <div class="sc-hero__grid">

        {{-- Kolom kiri: teks + tombol --}}
        <div data-aos="fade-up" data-aos-duration="800">

            {{-- Badge "Universitas Telkom" --}}
            <div class="sc-hero__badge">
                <span class="sc-hero__badge-dot"></span>
                Universitas Telkom
            </div>

            {{-- Judul: Center Of Excellence / Smart City --}}
            <h1 class="sc-hero__h1">
                Center Of Excellence<br>
                <span class="text-blue">Smart City</span>
            </h1>

            {{-- Garis biru pendek --}}
            <div class="sc-hero__divider"></div>

            {{-- Sub-judul (ganti lorem ipsum dengan teks asli jika sudah ada) --}}
            <div class="sc-hero__subtitle">Lorem ipsum dolor sit amet</div>

            {{-- Deskripsi singkat --}}
            <p class="sc-hero__desc">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Quisque vehicula mauris nec lectus pharetra, vel ornare enim
                facilisis. Proin vehicula urna sed quam efficitur venenatis.
            </p>

            {{-- Tombol CTA --}}
            <div class="sc-hero__cta">

                {{-- Tombol Proyek Kami --}}
                <a href="/project-user" class="sc-btn-proyek">
                    <span>Proyek Kami</span>
                    <span class="sc-btn-proyek__arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>

                {{-- Tombol WhatsApp --}}
                <a href="https://wa.me/628132325928" class="sc-btn-wa">
                    <div class="sc-btn-wa__icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <div class="sc-btn-wa__label">Hubungi Kami</div>
                        <div class="sc-btn-wa__number">+62 813-2325-928</div>
                    </div>
                </a>

            </div>
        </div>

        {{-- Kolom kanan: foto tim bulat + blob aksen --}}
        <div class="sc-hero__photo-col"
             data-aos="fade-left"
             data-aos-duration="900"
             data-aos-delay="150">

            {{-- Blob warna di belakang foto --}}
            <div class="sc-hero__blob"></div>

            {{--
                Foto tim — pakai fotoaboutus.jpg yang sudah ada di public/img/
                Bila gagal load → placeholder otomatis tampil via onerror
            --}}
            <img
                src="img/fotoaboutus.jpg"
                alt="Tim CoE Smart City Universitas Telkom"
                class="sc-hero__photo"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="sc-hero__photo-placeholder">
                <i class="bi bi-people-fill"></i>
                <span>Foto Tim CoE<br>Smart City</span>
            </div>

        </div>
    </div>

    {{--
        Gelombang bawah hero — transisi visual ke section Tentang.
        Lapisan atas berwarna var(--bg-light) = #f8f9fa
        agar menyambung mulus dengan background section Tentang.
    --}}
    <div class="sc-hero__wave">
        <svg viewBox="0 0 1440 90"
             xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none">
            {{-- Lapisan belakang: biru transparan tipis --}}
            <path d="M0,55 C360,95 720,15 1080,60 C1260,83 1380,48 1440,58 L1440,90 L0,90 Z"
                  fill="rgba(76,141,201,0.06)"/>
            {{-- Lapisan depan: warna section berikutnya (#f8f9fa) --}}
            <path d="M0,68 C300,100 600,34 900,68 C1050,85 1260,50 1440,64 L1440,90 L0,90 Z"
                  fill="#f8f9fa"/>
        </svg>
    </div>

</section>


{{-- ============================================================
     [SECTION 2 — TENTANG KAMI]
     Label, judul, deskripsi panjang, tombol, daftar aktifitas
     ============================================================ --}}
<section class="sc-tentang" id="tentang">
    <div class="container">
        <div class="row align-items-start g-5">

            {{-- Kolom kiri: seluruh teks --}}
            <div class="col-lg-6" data-aos="fade-up">

                {{-- Label "• TENTANG KAMI •" --}}
                <div class="sc-label">Tentang Kami</div>

                {{-- Judul --}}
                <h2 class="sc-tentang__heading">CoE Smart City</h2>

                {{-- Deskripsi — ganti lorem ipsum dengan teks asli --}}
                <p class="sc-tentang__desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                    ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>

                {{-- Tombol Pelajari Lebih Lanjut --}}
                <a href="/about-user" class="sc-btn-pelajari">
                    <span>Pelajari Lebih Lanjut</span>
                    <span class="sc-btn-pelajari__arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>

                {{-- Sub-label "• AKTIFITAS KAMI •" --}}
                <div class="sc-label" style="margin-top:2.5rem;">Aktifitas Kami</div>

                {{-- Aktifitas 1 --}}
                <div class="sc-akt__row" data-aos="fade-up" data-aos-delay="100">
                    <div class="sc-akt__icon">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div class="sc-akt__text">Pengembangan Lembaga dan SDM</div>
                </div>

                {{-- Aktifitas 2 --}}
                <div class="sc-akt__row" data-aos="fade-up" data-aos-delay="200">
                    <div class="sc-akt__icon">
                        <i class="bi bi-display"></i>
                    </div>
                    <div class="sc-akt__text">
                        Academic Excellence
                        <br><small>(Riset dan Publikasi)</small>
                    </div>
                </div>

                {{-- Aktifitas 3 --}}
                <div class="sc-akt__row" data-aos="fade-up" data-aos-delay="300">
                    <div class="sc-akt__icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="sc-akt__text">
                        Commercialization
                        <br><small>(Kontrak Kerjasama Komersialisasi Produk Hasil Riset)</small>
                    </div>
                </div>

            </div>

            {{-- Kolom kanan: kosong / bisa diisi foto atau ilustrasi --}}
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center"
                 data-aos="fade-up" data-aos-delay="100">
                {{-- Placeholder — tim ganti dengan gambar asli bila ada --}}
                <div style="
                    width:100%; max-width:400px; aspect-ratio:4/3;
                    background:linear-gradient(135deg,#e8f4ff,#c5dff5);
                    border-radius:20px; display:flex; align-items:center;
                    justify-content:center; flex-direction:column; gap:10px;
                    color:rgba(76,141,201,0.45);
                ">
                    <i class="bi bi-image" style="font-size:36px;"></i>
                    <span style="font-size:13px;font-family:'Lato',sans-serif;">
                        Foto / Ilustrasi
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================
     [SECTION 3 — FAKTA SMART CITY]
     Angka statistik (counter animasi saat scroll) + 3 tab proyek
     ============================================================ --}}
<section class="sc-fakta" id="fakta">
    <div class="container">

        {{-- Label + Judul tengah --}}
        <div class="text-center" data-aos="fade-up">
            <div class="sc-label" style="justify-content:center;">Fakta Smart City</div>
            <h2 class="sc-fakta__heading">Statistik Smart City</h2>
        </div>

        {{-- Grid angka statistik --}}
        {{-- Angka dianimasikan dari 0 → target saat section masuk layar --}}
        <div class="sc-fakta__stats" data-aos="fade-up" data-aos-delay="100">

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="59">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Anggota</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="14">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Proyek Nasional</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="63">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Publikasi</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="7">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Proyek Internasional</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="4">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Proyek Unggulan</div>
            </div>

        </div>

        {{-- Tab + konten proyek --}}
        <div data-aos="fade-up" data-aos-delay="150">

            {{-- Baris tombol tab --}}
            <div class="sc-fakta__tabbar">
                <button class="sc-fakta__tab active" data-pane="sc-tab-int">
                    Proyek Internasional
                </button>
                <button class="sc-fakta__tab" data-pane="sc-tab-nas">
                    Proyek Nasional
                </button>
                <button class="sc-fakta__tab" data-pane="sc-tab-ung">
                    Produk Unggulan
                </button>
            </div>

            {{-- Panel: Proyek Internasional (default aktif) --}}
            <div id="sc-tab-int" class="sc-fakta__pane active">
                <ul class="sc-fakta__list">
                    <li>Quantum MMU</li>
                    <li>Asean IVO (PATRIOT-41R-Net)</li>
                    <li>G20/T20</li>
                    <li>APT Wireless Group</li>
                    <li>AI-Agriculture</li>
                    <li>DSA</li>
                    <li>RHENIuM</li>
                    <li>PolarRaptor-IoT</li>
                </ul>
            </div>

            {{-- Panel: Proyek Nasional --}}
            <div id="sc-tab-nas" class="sc-fakta__pane">
                <ul class="sc-fakta__list">
                    <li>PATRIOT-NET</li>
                    <li>ADA-DIKTI-MCRBS</li>
                    <li>5G-MERDEKA</li>
                    <li>HARUMI 2021</li>
                    <li>HARUKA 2022</li>
                    <li>BRIN - Kereta Cepat 2021</li>
                    <li>BRIN - Roket 2021</li>
                    <li>BRIN - Kereta Cepat 2022</li>
                    <li>Master Plan TIK UNP</li>
                    <li>Deteksi Sinyal</li>
                    <li>5G-Point</li>
                    <li>Zebra Codes</li>
                    <li>Kereta Cepat-KCIC</li>
                    <li>T3LESDM-Net</li>
                    <li>ANTASENA</li>
                    <li>SEKAI</li>
                </ul>
            </div>

            {{-- Panel: Produk Unggulan --}}
            <div id="sc-tab-ung" class="sc-fakta__pane">
                <ul class="sc-fakta__list">
                    <li>Sistem Monitoring Kota Cerdas</li>
                    <li>Platform IoT Terintegrasi</li>
                    <li>AI-Based Traffic Management</li>
                    <li>SmartGrid Energy Solution</li>
                </ul>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================
     [SECTION 4 — ABOUT]
     Section asli dari tim sebelumnya — DIPERTAHANKAN utuh.
     Style .about-section, .feature-item, .about-image
     sudah ada di public/css/main.css
     ============================================================ --}}
<section class="about-section" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-up">
                <h2>Tentang Kami</h2>
                <p>CoE Smart city di Universitas Telkom adalah unit strategis untuk mempercepat riset, inovasi,
                    bisnis, dan layanan masyarakat, serta berkontribusi pada ilmu pengetahuan, teknologi, manajemen,
                    dan seni</p>
                <img src="img/fotoaboutus.jpg" alt="About Image" class="about-image" data-aos="fade-up">
            </div>
            <div class="col-lg-6">
                <div class="feature-item" data-aos="fade-up">
                    <h3>Riset</h3>
                    <p>CoE Smart City mengakselerasi penelitian di berbagai bidang ilmu untuk menghasilkan penemuan
                        dan inovasi baru yang bermanfaat bagi masyarakat dan industri.</p>
                </div>
                <div class="feature-item" data-aos="fade-up">
                    <h3>Inovasi</h3>
                    <p>CoE Smart City mendorong pengembangan teknologi dan solusi kreatif yang dapat
                        diimplementasikan untuk meningkatkan kualitas hidup manusia dan efektivitas.</p>
                </div>
                <div class="feature-item" data-aos="fade-up">
                    <h3>Bisnis</h3>
                    <p>CoE Smart City mengakselerasi penelitian di berbagai bidang ilmu untuk menghasilkan penemuan
                        dan inovasi baru yang bermanfaat bagi masyarakat dan industri.</p>
                </div>
                <div class="feature-item" data-aos="fade-up">
                    <h3>Layanan Masyarakat</h3>
                    <p>CoE Smart City berkontribusi melalui program dan inisiatif yang dirancang untuk memberdayakan
                        komunitas, meningkatkan kesejahteraan sosial, dan menyelesaikan masalah-masalah kawasan</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     [SECTION 5 — CARDS]
     4 kartu program — DIPERTAHANKAN utuh.
     Style .cards-section, .program-card, dll. di main.css
     ============================================================ --}}
<section class="cards-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="program-card">
                    <div class="card-image">
                        <img src="img/card1.jpg" alt="Smart City Program">
                    </div>
                    <div class="card-content blue-gradient">
                        <h3>Smart City Program</h3>
                        <p>Program ini bertujuan untuk mengintegrasikan berbagai metode guna meningkatkan kualitas
                            hidup di kota-kota modern.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="program-card">
                    <div class="card-image">
                        <img src="img/card2.jpg" alt="Innovation for Future">
                    </div>
                    <div class="card-content purple-gradient">
                        <h3>Innovation for Future</h3>
                        <p>Kami mengembangkan solusi inovatif untuk meningkatkan kualitas hidup melalui teknologi
                            dan sistem pintar di kota.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="program-card">
                    <div class="card-image">
                        <img src="img/card3.jpg" alt="Smart Infrastructure">
                    </div>
                    <div class="card-content cyan-gradient">
                        <h3>Smart Infrastructure</h3>
                        <p>Kami fokus pada pembangunan infrastruktur pintar yang menghubungkan berbagai elemen kota
                            untuk menciptakan solusi yang lebih efisien.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="program-card">
                    <div class="card-image">
                        <img src="img/card4.jpg" alt="Sustainable Cities">
                    </div>
                    <div class="card-content orange-gradient">
                        <h3>Sustainable Cities</h3>
                        <p>Inisiatif kami untuk menciptakan kota yang lebih berkelanjutan dengan pemanfaatan
                            teknologi hijau dan solusi ramah lingkungan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     [SECTION 6 — GALLERY]
     Carousel proyek — DIPERTAHANKAN utuh.
     Teks "Our Project" diubah ke Bahasa Indonesia.
     Style .gallery-section, .carousel-image-container di main.css
     ============================================================ --}}
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="gallery-title">
                Proyek <span class="text-primary"><a href="/project-user">Kami</a></span>
            </h2>
            <p class="gallery-subtitle">Dokumentasi proyek-proyek inovatif dalam pengembangan Smart City.</p>
        </div>
        <div class="gallery-carousel" data-aos="fade-up">
            <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="carousel-image-container">
                            <img src="img/card1.jpg" class="d-block w-100" alt="Project 1">
                            <div class="carousel-caption">
                                <h5>Smart City Research Lab</h5>
                                <p>Kolaborasi tim peneliti dalam mengembangkan solusi teknologi smart city
                                    untuk transportasi publik.</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-image-container">
                            <img src="img/card2.jpg" class="d-block w-100" alt="Project 2">
                            <div class="carousel-caption">
                                <h5>Innovation Workshop</h5>
                                <p>Workshop pengembangan sistem monitoring berbasis IoT untuk infrastruktur
                                    kota pintar.</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-image-container">
                            <img src="img/card3.jpg" class="d-block w-100" alt="Project 3">
                            <div class="carousel-caption">
                                <h5>Smart City Development</h5>
                                <p>Presentasi implementasi teknologi artificial intelligence dalam manajemen
                                    lalu lintas kota.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#galleryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#galleryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Berikutnya</span>
                </button>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     JAVASCRIPT — hanya untuk fitur BARU di file ini
     (counter angka + tab proyek)

     AOS, Bootstrap JS, dan scroll-top sudah diinisialisasi
     di link.blade.php — TIDAK perlu ditulis ulang di sini.
     ============================================================ --}}
<script>
    // ── Counter angka statistik ────────────────────────────────
    // Fungsi: animasikan angka dari 0 → target dalam ~1.8 detik
    function scAnimateCounter(el) {
        const target   = parseInt(el.dataset.target, 10);
        const duration = 1800;                     // ms
        const step     = target / (duration / 16); // increment per frame
        let   current  = 0;

        const timer = setInterval(function () {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current);
        }, 16);
    }

    // Jalankan counter hanya SEKALI saat section #fakta masuk layar
    var scCounterDone = false;
    var scFaktaEl     = document.querySelector('.sc-fakta');

    if (scFaktaEl) {
        var scObserver = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting && !scCounterDone) {
                scCounterDone = true;
                document.querySelectorAll('.js-counter').forEach(function (el) {
                    scAnimateCounter(el);
                });
            }
        }, { threshold: 0.25 });

        scObserver.observe(scFaktaEl);
    }

    // ── Tab proyek: ganti panel tanpa pindah halaman ───────────
    document.querySelectorAll('.sc-fakta__tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Reset semua
            document.querySelectorAll('.sc-fakta__tab').forEach(function (b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('.sc-fakta__pane').forEach(function (p) {
                p.classList.remove('active');
            });
            // Aktifkan yang diklik
            btn.classList.add('active');
            var targetPane = document.getElementById(btn.dataset.pane);
            if (targetPane) targetPane.classList.add('active');
        });
    });
</script>

{{-- resources/views/components/layout/home.blade.php --}}
{{-- Berisi: Hero + Tentang Kami + Fakta Smart City --}}
{{-- Section About (original) tetap dipertahankan di bawah --}}

{{-- <style>
/* ===================== HERO ===================== */
.hero {
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: #0a1628;
}
.hero-bg {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 70% 60% at 60% 40%, rgba(21,101,192,0.35) 0%, transparent 70%),
        radial-gradient(ellipse 50% 50% at 20% 70%, rgba(0,180,216,0.2) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 80% 80%, rgba(15,52,96,0.4) 0%, transparent 50%),
        linear-gradient(135deg, #050d1a 0%, #0a1f40 50%, #061224 100%);
    animation: bgShift 12s ease-in-out infinite alternate;
}
@keyframes bgShift {
    0%   { filter: hue-rotate(0deg) brightness(1); }
    100% { filter: hue-rotate(15deg) brightness(1.05); }
}
.hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(0,180,216,0.07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,180,216,0.07) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
}
.orb {
    position: absolute; border-radius: 50%; filter: blur(60px);
    animation: orbFloat linear infinite; pointer-events: none;
}
.orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(21,101,192,0.4) 0%, transparent 70%); top: -100px; right: 10%; animation-duration: 20s; }
.orb-2 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,180,216,0.3) 0%, transparent 70%); bottom: 10%; left: 5%; animation-duration: 15s; animation-delay: -7s; }
.orb-3 { width: 200px; height: 200px; background: radial-gradient(circle, rgba(66,165,245,0.25) 0%, transparent 70%); top: 40%; right: 30%; animation-duration: 18s; animation-delay: -3s; }
@keyframes orbFloat {
    0%,100% { transform: translate(0,0) scale(1); }
    25%      { transform: translate(20px,-30px) scale(1.05); }
    50%      { transform: translate(-10px,20px) scale(0.95); }
    75%      { transform: translate(30px,10px) scale(1.02); }
}
.particles { position: absolute; inset: 0; overflow: hidden; }
.particle {
    position: absolute; width: 3px; height: 3px;
    background: #00b4d8; border-radius: 50%; opacity: 0;
    animation: particleAnim linear infinite;
}
@keyframes particleAnim {
    0%   { opacity: 0; transform: translateY(0) scale(0); }
    10%  { opacity: 0.8; }
    90%  { opacity: 0.3; }
    100% { opacity: 0; transform: translateY(-100px) scale(1.5); }
}
.floating-icon {
    position: absolute; width: 48px; height: 48px;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(0,180,216,0.3);
    border-radius: 12px; display: flex; align-items: center; justify-content: center;
    color: #00b4d8; font-size: 20px; backdrop-filter: blur(8px);
    animation: iconFloat ease-in-out infinite;
}
.fi-1 { top: 20%; right: 8%; animation-duration: 6s; }
.fi-2 { top: 55%; right: 5%; animation-duration: 8s; animation-delay: -2s; }
.fi-3 { bottom: 25%; right: 15%; animation-duration: 7s; animation-delay: -4s; }
.fi-4 { top: 30%; left: 3%; animation-duration: 9s; animation-delay: -1s; }
@keyframes iconFloat {
    0%,100% { transform: translateY(0) rotate(0deg); }
    50%      { transform: translateY(-15px) rotate(5deg); }
}
.hero-container {
    position: relative; z-index: 10; width: 100%; max-width: 1200px;
    margin: 0 auto; padding: 0 2rem; padding-top: 80px;
}
.hero-inner {
    display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;
    align-items: center; min-height: calc(100vh - 80px); padding: 4rem 0;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(0,180,216,0.15); border: 1px solid rgba(0,180,216,0.4);
    color: #48cae4; font-size: 12px; font-weight: 600; letter-spacing: 2px;
    text-transform: uppercase; padding: 8px 16px; border-radius: 100px; margin-bottom: 1.5rem;
}
.hero-badge span { width: 6px; height: 6px; background: #00b4d8; border-radius: 50%; animation: pulseDot 2s infinite; }
@keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.5;transform:scale(0.8);} }
.hero-title {
    font-family: 'Syne', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800; color: white; line-height: 1.1; margin-bottom: 0.5rem;
}
.hero-title .ht-highlight {
    background: linear-gradient(135deg, #42a5f5, #00b4d8);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.hero-divider { width: 60px; height: 3px; background: linear-gradient(90deg, #1565c0, #00b4d8); border-radius: 2px; margin: 1.5rem 0; }
.hero-subtitle { font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; color: rgba(255,255,255,0.9); margin-bottom: 0.75rem; }
.hero-desc { font-size: 15px; color: rgba(255,255,255,0.6); line-height: 1.7; margin-bottom: 2.5rem; max-width: 480px; }
.hero-cta { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 10px;
    background: linear-gradient(135deg, #1565c0, #00b4d8); color: white; text-decoration: none;
    padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px;
    box-shadow: 0 8px 30px rgba(21,101,192,0.5); transition: all 0.3s; position: relative; overflow: hidden;
}
.btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(21,101,192,0.7); color: white; }
.btn-hero-primary .btn-icon {
    width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.btn-hero-wa { display: inline-flex; align-items: center; gap: 12px; color: white; text-decoration: none; transition: all 0.3s; }
.btn-hero-wa:hover { color: #48cae4; }
.wa-icon {
    width: 48px; height: 48px; background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.2); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; font-size: 20px; transition: all 0.3s;
}
.btn-hero-wa:hover .wa-icon { background: rgba(0,180,216,0.2); border-color: #00b4d8; }
.wa-label { font-size: 11px; color: rgba(255,255,255,0.5); }
.wa-number { font-weight: 700; font-size: 15px; }

/* Hero right */
.hero-visual { display: flex; flex-direction: column; gap: 1rem; align-items: flex-end; }
.hero-stat-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; width: 100%; }
.stat-card {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px; padding: 20px; backdrop-filter: blur(12px); transition: all 0.3s;
}
.stat-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(0,180,216,0.4); transform: translateY(-4px); }
.stat-card .stat-num {
    font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800;
    background: linear-gradient(135deg, white, #48cae4);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1;
}
.stat-card .stat-label { font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1.5px; margin-top: 4px; }
.stat-card .stat-icon { font-size: 20px; color: #00b4d8; margin-bottom: 10px; }
.hero-main-card {
    width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(0,180,216,0.2);
    border-radius: 20px; padding: 24px; backdrop-filter: blur(16px); position: relative; overflow: hidden;
}
.hero-main-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, #1565c0, #00b4d8, transparent);
}
.card-header-sc { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
.card-dot { width: 8px; height: 8px; border-radius: 50%; }
.cd-1 { background: #ff5f57; }
.cd-2 { background: #febc2e; }
.cd-3 { background: #28c840; }
.card-title-sc { font-size: 12px; color: rgba(255,255,255,0.4); font-weight: 500; margin-left: 8px; }
.activity-list { list-style: none; padding: 0; margin: 0; }
.activity-item {
    display: flex; align-items: center; gap: 12px; padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; color: rgba(255,255,255,0.7);
}
.activity-item:last-child { border: none; padding-bottom: 0; }
.act-dot { width: 8px; height: 8px; border-radius: 50%; background: #00b4d8; flex-shrink: 0; }
.act-dot.act-blue { background: #42a5f5; }
.act-dot.act-gold { background: #ffd166; }

/* Wave */
.hero-wave { position: absolute; bottom: -1px; left: 0; right: 0; line-height: 0; }
.hero-wave svg { display: block; }

/* ===================== TENTANG KAMI ===================== */
.tentang-section { background: #f0f6ff; padding: 100px 0; position: relative; overflow: hidden; }
.tentang-section::before {
    content: ''; position: absolute; top: 0; right: 0;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(21,101,192,0.06) 0%, transparent 70%);
}
.section-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;
    color: #1565c0; margin-bottom: 1.25rem;
}
.section-label::before, .section-label::after { content: '•'; color: #1565c0; }
.tentang-title { font-family: 'Syne', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: #0a1628; margin-bottom: 1.5rem; line-height: 1.1; }
.tentang-desc { font-size: 16px; line-height: 1.8; color: #334e7b; margin-bottom: 2rem; max-width: 560px; font-weight: 500; }
.btn-tentang {
    display: inline-flex; align-items: center; gap: 10px;
    background: linear-gradient(135deg, #0f3460, #1976d2);
    color: white; text-decoration: none; padding: 14px 28px; border-radius: 100px;
    font-weight: 700; font-size: 15px; box-shadow: 0 8px 25px rgba(21,101,192,0.35); transition: all 0.3s;
}
.btn-tentang:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(21,101,192,0.5); color: white; }
.btn-tentang .btn-arrow { width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; }
.aktifitas-label {
    font-size: 12px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;
    color: #1565c0; margin: 3rem 0 1.5rem; display: flex; align-items: center; gap: 8px;
}
.aktifitas-label::before, .aktifitas-label::after { content: '•'; }
.aktifitas-item { display: flex; align-items: center; gap: 20px; margin-bottom: 1.5rem; }
.akt-icon-box {
    width: 56px; height: 56px; border-radius: 16px;
    background: linear-gradient(135deg, #0f3460, #1976d2);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: white; flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(21,101,192,0.35);
}
.akt-text { font-size: 16px; font-weight: 600; color: #0a1628; line-height: 1.3; }
.tentang-visual { display: flex; align-items: center; justify-content: center; height: 100%; }
.tentang-img-box { position: relative; width: 100%; max-width: 460px; }
.tentang-img-main {
    width: 100%; aspect-ratio: 4/3;
    background: linear-gradient(135deg, #0d2148, #1565c0);
    border-radius: 24px; overflow: hidden;
    box-shadow: 0 20px 60px rgba(21,101,192,0.3);
    position: relative;
}
.tentang-img-main img { width: 100%; height: 100%; object-fit: cover; }
.img-placeholder {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 8px;
}
.img-placeholder i { font-size: 40px; color: rgba(255,255,255,0.3); }
.img-placeholder p { font-size: 12px; color: rgba(255,255,255,0.4); text-align: center; }
.tentang-badge-float {
    position: absolute; bottom: -20px; left: -20px;
    background: white; border-radius: 16px; padding: 16px 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 12px;
}
.tbf-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #1565c0, #00b4d8);
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    color: white; font-size: 18px;
}
.tbf-num { font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 800; color: #0a1628; line-height: 1; }
.tbf-label { font-size: 11px; color: #7a94b8; font-weight: 500; }

/* ===================== FAKTA ===================== */
.fakta-section { background: white; padding: 100px 0; position: relative; overflow: hidden; }
.fakta-bg {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 50% at 10% 50%, rgba(21,101,192,0.04) 0%, transparent 70%),
        radial-gradient(ellipse 50% 50% at 90% 50%, rgba(0,180,216,0.04) 0%, transparent 70%);
}
.fakta-title { font-family: 'Syne', sans-serif; font-size: clamp(1.8rem, 3.5vw, 2.8rem); font-weight: 800; color: #0a1628; text-align: center; margin-bottom: 1rem; }
.fakta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 2rem; margin: 3rem 0 4rem; }
.fakta-item { text-align: left; }
.fakta-num { font-family: 'Syne', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; color: #0a1628; line-height: 1; margin-bottom: 6px; }
.fakta-num .plus { color: #1565c0; }
.fakta-label { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #1565c0; display: flex; align-items: center; gap: 6px; }
.fakta-label::before { content: '•'; font-size: 16px; }
.fakta-tabs { display: flex; gap: 12px; margin-bottom: 2rem; flex-wrap: wrap; }
.tab-btn {
    padding: 12px 24px; border-radius: 100px; border: none;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.3s;
}
.tab-btn.active { background: linear-gradient(135deg, #0f3460, #1976d2); color: white; box-shadow: 0 6px 20px rgba(21,101,192,0.4); }
.tab-btn:not(.active) { background: #f0f6ff; color: #334e7b; }
.tab-btn:not(.active):hover { background: #e3f2fd; color: #0f3460; }
.fakta-content { display: none; }
.fakta-content.active { display: block; }
.fakta-list { list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
.fakta-list li {
    display: flex; align-items: center; gap: 12px;
    font-size: 15px; font-weight: 600; color: #0a1628;
    background: #f0f6ff; padding: 14px 18px; border-radius: 12px;
    border-left: 3px solid #1976d2; transition: all 0.2s;
}
.fakta-list li:hover { background: #e3f2fd; border-left-color: #00b4d8; transform: translateX(4px); }
.fakta-list li::before { content: ''; width: 6px; height: 6px; background: #1976d2; border-radius: 50%; flex-shrink: 0; }

/* Responsive */
@media (max-width: 991px) {
    .hero-inner { grid-template-columns: 1fr; gap: 2rem; padding: 3rem 0; }
    .hero-visual { display: none; }
    .floating-icon { display: none; }
}
@media (max-width: 576px) {
    .hero-cta { flex-direction: column; align-items: flex-start; }
    .fakta-grid { grid-template-columns: 1fr 1fr; }
    .hero-container { padding: 0 1rem; }
    .tentang-badge-float { bottom: -10px; left: -10px; }
}
</style>

<!-- ======================== HERO SECTION ======================== -->
<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="particles" id="heroParticles"></div>

    <div class="floating-icon fi-1"><i class="bi bi-cpu"></i></div>
    <div class="floating-icon fi-2"><i class="bi bi-wifi"></i></div>
    <div class="floating-icon fi-3"><i class="bi bi-building"></i></div>
    <div class="floating-icon fi-4"><i class="bi bi-graph-up"></i></div>

    <div class="hero-container">
        <div class="hero-inner">
            <!-- Left -->
            <div data-aos="fade-up" data-aos-duration="900">
                <div class="hero-badge">
                    <span></span> Universitas Telkom
                </div>
                <h1 class="hero-title">
                    Center Of Excellence<br>
                    <span class="ht-highlight">Smart City</span>
                </h1>
                <div class="hero-divider"></div>
                <div class="hero-subtitle">Teknologi Kota Masa Depan</div>
                <p class="hero-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mauris nec lectus pharetra, vel ornare enim facilisis. Proin vehicula urna sed quam efficitur.
                </p>
                <div class="hero-cta">
                    <a href="/project-user" class="btn-hero-primary">
                        <span class="btn-icon"><i class="bi bi-arrow-right"></i></span>
                        Proyek Kami
                    </a>
                    <a href="https://wa.me/628132325928" class="btn-hero-wa">
                        <div class="wa-icon"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <div class="wa-label">Hubungi Kami</div>
                            <div class="wa-number">+62 813-2325-928</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Right -->
            <div class="hero-visual" data-aos="fade-left" data-aos-duration="1100" data-aos-delay="200">
                <div class="hero-main-card" style="margin-bottom:1rem;">
                    <div class="card-header-sc">
                        <div class="card-dot cd-1"></div>
                        <div class="card-dot cd-2"></div>
                        <div class="card-dot cd-3"></div>
                        <span class="card-title-sc">Aktivitas Terkini</span>
                    </div>
                    <ul class="activity-list">
                        <li class="activity-item"><div class="act-dot"></div>Riset Sistem Kota Cerdas Berbasis IoT</li>
                        <li class="activity-item"><div class="act-dot act-blue"></div>Kolaborasi Inovasi Infrastruktur Digital</li>
                        <li class="activity-item"><div class="act-dot act-gold"></div>Publikasi Penelitian Internasional</li>
                    </ul>
                </div>
                <div class="hero-stat-cards">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-num" data-count="59">0</div>
                        <div class="stat-label">Anggota Aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-journal-richtext"></i></div>
                        <div class="stat-num" data-count="63">0</div>
                        <div class="stat-label">Publikasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-kanban-fill"></i></div>
                        <div class="stat-num" data-count="14">0</div>
                        <div class="stat-label">Proyek Nasional</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-globe2"></i></div>
                        <div class="stat-num" data-count="7">0</div>
                        <div class="stat-label">Proyek Int'l</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave bottom -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,80 C240,120 480,40 720,80 C960,120 1200,40 1440,80 L1440,120 L0,120 Z" fill="#f0f6ff"/>
        </svg>
    </div>
</section>

<!-- ======================== TENTANG KAMI ======================== -->
<section class="tentang-section" id="tentang">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="section-label">Tentang Kami</div>
                <h2 class="tentang-title">CoE Smart City</h2>
                <p class="tentang-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                </p>
                <a href="/about-user" class="btn-tentang">
                    Pelajari Lebih Lanjut
                    <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
                </a>
                <div class="aktifitas-label">Aktifitas Kami</div>
                <div class="aktifitas-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="akt-icon-box"><i class="bi bi-pie-chart-fill"></i></div>
                    <span class="akt-text">Pengembangan Lembaga dan SDM</span>
                </div>
                <div class="aktifitas-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="akt-icon-box"><i class="bi bi-display"></i></div>
                    <span class="akt-text">Academic Excellence<br><small style="font-weight:400;color:#7a94b8">(Riset dan Publikasi)</small></span>
                </div>
                <div class="aktifitas-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="akt-icon-box"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <span class="akt-text">Commercialization<br><small style="font-weight:400;color:#7a94b8">(Kontrak Kerjasama Komersialisasi Produk Hasil Riset)</small></span>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="tentang-visual">
                    <div class="tentang-img-box">
                        <div class="tentang-img-main">
                            {{-- Ganti src dengan foto asli jika sudah ada --}}
                            <img src="img/fotoaboutus.jpg" alt="CoE Smart City" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div class="img-placeholder" style="display:none;">
                                <i class="bi bi-image"></i>
                                <p>Foto CoE Smart City<br>Universitas Telkom</p>
                            </div>
                        </div>
                        <div class="tentang-badge-float">
                            <div class="tbf-icon"><i class="bi bi-award-fill"></i></div>
                            <div>
                                <div class="tbf-num">2019</div>
                                <div class="tbf-label">Tahun Berdiri</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================== FAKTA SMART CITY ======================== -->
<section class="fakta-section" id="fakta">
    <div class="fakta-bg"></div>
    <div class="container" style="position:relative;z-index:1;">
        <div class="text-center mb-2" data-aos="fade-up">
            <div class="section-label" style="justify-content:center;">Fakta Smart City</div>
            <h2 class="fakta-title">Statistik Smart City</h2>
        </div>
        <div class="fakta-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="fakta-item">
                <div class="fakta-num"><span class="counter" data-target="59">0</span><span class="plus">+</span></div>
                <div class="fakta-label">Jumlah Anggota</div>
            </div>
            <div class="fakta-item">
                <div class="fakta-num"><span class="counter" data-target="14">0</span><span class="plus">+</span></div>
                <div class="fakta-label">Jumlah Proyek Nasional</div>
            </div>
            <div class="fakta-item">
                <div class="fakta-num"><span class="counter" data-target="63">0</span><span class="plus">+</span></div>
                <div class="fakta-label">Jumlah Publikasi</div>
            </div>
            <div class="fakta-item">
                <div class="fakta-num"><span class="counter" data-target="7">0</span><span class="plus">+</span></div>
                <div class="fakta-label">Jumlah Proyek Internasional</div>
            </div>
            <div class="fakta-item">
                <div class="fakta-num"><span class="counter" data-target="4">0</span><span class="plus">+</span></div>
                <div class="fakta-label">Jumlah Proyek Unggulan</div>
            </div>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
            <div class="fakta-tabs">
                <button class="tab-btn active" data-tab="int">Proyek Internasional</button>
                <button class="tab-btn" data-tab="nas">Proyek Nasional</button>
                <button class="tab-btn" data-tab="ung">Produk Unggulan</button>
            </div>
            <div id="tab-int" class="fakta-content active">
                <ul class="fakta-list">
                    <li>Quantum MMU</li>
                    <li>Asean IVO (PATRIOT-41R-Net)</li>
                    <li>G20/T20</li>
                    <li>APT Wireless Group</li>
                    <li>AI-Agriculture</li>
                    <li>DSA</li>
                    <li>RHENIuM</li>
                    <li>PolarRaptor-IoT</li>
                </ul>
            </div>
            <div id="tab-nas" class="fakta-content">
                <ul class="fakta-list">
                    <li>PATRIOT-NET</li><li>ADA-DIKTI-MCRBS</li><li>5G-MERDEKA</li>
                    <li>HARUMI 2021</li><li>HARUKA 2022</li><li>BRIN - Kereta Cepat 2021</li>
                    <li>BRIN - Roket 2021</li><li>BRIN - Kereta Cepat 2022</li>
                    <li>Master Plan TIK UNP</li><li>Deteksi Sinyal</li><li>5G-Point</li>
                    <li>Zebra Codes</li><li>Kereta Cepat-KCIC</li><li>T3LESDM-Net</li>
                    <li>ANTASENA</li><li>SEKAI</li>
                </ul>
            </div>
            <div id="tab-ung" class="fakta-content">
                <ul class="fakta-list">
                    <li>Sistem Monitoring Kota Cerdas</li>
                    <li>Platform IoT Terintegrasi</li>
                    <li>AI-Based Traffic Management</li>
                    <li>SmartGrid Energy Solution</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ======================== ABOUT ORIGINAL (tetap dipertahankan) ======================== -->
<section class="about-section" id="about" style="background:#f0f6ff;padding:100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-up">
                <h2 style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:#0a1628;margin-bottom:1rem;">Tentang Kami</h2>
                <p style="color:#334e7b;line-height:1.8;">CoE Smart city di Universitas Telkom adalah unit strategis untuk mempercepat riset, inovasi, bisnis, dan layanan masyarakat, serta berkontribusi pada ilmu pengetahuan, teknologi, manajemen, dan seni.</p>
                <img src="img/fotoaboutus.jpg" alt="About Image" class="about-image mt-4" data-aos="fade-up" style="max-width:100%;border-radius:16px;">
            </div>
            <div class="col-lg-6">
                <div class="feature-item" data-aos="fade-up" style="margin-bottom:1.5rem;padding-left:1rem;border-left:3px solid #1565c0;">
                    <h3 style="font-weight:700;color:#0a1628;">Riset</h3>
                    <p style="color:#334e7b;font-size:14px;">CoE Smart City mengakselerasi penelitian di berbagai bidang ilmu untuk menghasilkan penemuan dan inovasi baru.</p>
                </div>
                <div class="feature-item" data-aos="fade-up" style="margin-bottom:1.5rem;padding-left:1rem;border-left:3px solid #1565c0;">
                    <h3 style="font-weight:700;color:#0a1628;">Inovasi</h3>
                    <p style="color:#334e7b;font-size:14px;">CoE Smart City mendorong pengembangan teknologi dan solusi kreatif untuk meningkatkan kualitas hidup manusia.</p>
                </div>
                <div class="feature-item" data-aos="fade-up" style="margin-bottom:1.5rem;padding-left:1rem;border-left:3px solid #1565c0;">
                    <h3 style="font-weight:700;color:#0a1628;">Bisnis</h3>
                    <p style="color:#334e7b;font-size:14px;">CoE Smart City mengakselerasi penelitian di berbagai bidang ilmu untuk menghasilkan inovasi bagi industri.</p>
                </div>
                <div class="feature-item" data-aos="fade-up" style="padding-left:1rem;border-left:3px solid #1565c0;">
                    <h3 style="font-weight:700;color:#0a1628;">Layanan Masyarakat</h3>
                    <p style="color:#334e7b;font-size:14px;">CoE Smart City berkontribusi melalui program yang dirancang untuk memberdayakan komunitas dan meningkatkan kesejahteraan sosial.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    /* Particles */
    const pc = document.getElementById('heroParticles');
    if (pc) {
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;animation-delay:${Math.random()*8}s;animation-duration:${4+Math.random()*6}s;width:${2+Math.random()*3}px;height:${2+Math.random()*3}px;${Math.random()>.5?'background:#1976d2':''}`;
            pc.appendChild(p);
        }
    }

    /* Hero stat counter (on load) */
    setTimeout(() => {
        document.querySelectorAll('.hero-stat-cards .stat-num[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count);
            let cur = 0;
            const step = target / (1500/16);
            const t = setInterval(() => {
                cur += step;
                if (cur >= target) { cur = target; clearInterval(t); }
                el.textContent = Math.floor(cur);
            }, 16);
        });
    }, 800);

    /* Fakta counter on scroll */
    let counted = false;
    const fakta = document.querySelector('.fakta-section');
    if (fakta) {
        const obs = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !counted) {
                counted = true;
                document.querySelectorAll('.fakta-section .counter').forEach(el => {
                    const target = parseInt(el.dataset.target);
                    let cur = 0;
                    const step = target / (1800/16);
                    const t = setInterval(() => {
                        cur += step;
                        if (cur >= target) { cur = target; clearInterval(t); }
                        el.textContent = Math.floor(cur);
                    }, 16);
                });
            }
        }, { threshold: 0.3 });
        obs.observe(fakta);
    }

    /* Tabs */
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.fakta-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            const target = document.getElementById('tab-' + btn.dataset.tab);
            if (target) target.classList.add('active');
        });
    });
});
</script> --}}