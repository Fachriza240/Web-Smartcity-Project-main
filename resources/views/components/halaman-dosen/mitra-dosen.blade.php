@props(['partners' => collect()])

<style>
    /* ══════════════════════════════════════════════
       [MITRA - PAGE HERO] — Konsisten dengan halaman lain
       ══════════════════════════════════════════════ */
    .mitra-hero {
        position: relative;
        padding: 160px 0 100px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        overflow: hidden;
    }

    .mitra-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 80% 30%, black 5%, transparent 75%);
        pointer-events: none;
    }

    .mitra-hero::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(76, 141, 201, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .mitra-hero__content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .mitra-hero__badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(76, 141, 201, 0.1);
        border: 1px solid rgba(76, 141, 201, 0.3);
        color: var(--primary-blue);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        padding: 8px 20px;
        border-radius: 100px;
        margin-bottom: 1.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .mitra-hero__badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: mitraPulse 2s infinite;
    }

    @keyframes mitraPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .4; transform: scale(.7); }
    }

    .mitra-hero__title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .mitra-hero__title .text-blue { color: var(--primary-blue); }

    .mitra-hero__subtitle {
        font-size: 1.15rem;
        color: var(--text-gray);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    .mitra-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .mitra-hero__wave svg { display: block; width: 100%; }

    /* ══════════════════════════════════════════════
       [MITRA - PARTNERS SECTION]
       ══════════════════════════════════════════════ */
    .sc-partners-section {
        background: #fff;
        padding: 90px 0;
        position: relative;
    }

    /* Grid partners */
    .sc-partners-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 0;
    }

    .sc-partner-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 30px rgba(76, 141, 201, 0.08);
        border: 1px solid rgba(76, 141, 201, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .sc-partner-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, #3a7ab3 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .sc-partner-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(76, 141, 201, 0.15);
        border-color: rgba(76, 141, 201, 0.2);
    }

    .sc-partner-card:hover::before { transform: scaleX(1); }

    .sc-partner-logo {
        width: 100%;
        max-width: 120px;
        height: 80px;
        object-fit: contain;
        margin: 0 auto 1.5rem;
        display: block;
        border-radius: 8px;
    }

    .sc-partner-logo-placeholder {
        width: 120px;
        height: 80px;
        background: rgba(76, 141, 201, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 2px dashed rgba(76, 141, 201, 0.3);
    }

    .sc-partner-logo-placeholder i {
        font-size: 2.5rem;
        color: rgba(76, 141, 201, 0.5);
    }

    .sc-partner-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.75rem;
        font-family: "Spline Sans", sans-serif;
        line-height: 1.3;
    }

    .sc-partner-website {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--primary-blue);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        font-family: "Lato", sans-serif;
        border-radius: 20px;
        padding: 6px 16px;
        background: rgba(76, 141, 201, 0.08);
        transition: all 0.2s ease;
    }

    .sc-partner-website:hover {
        color: #fff;
        background: var(--primary-blue);
    }

    .sc-partner-website i { font-size: 12px; }

    /* Empty state */
    .sc-partners-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-light, #f4f8fc);
        border-radius: 16px;
        border: 2px dashed rgba(76, 141, 201, 0.3);
    }

    .sc-partners-empty i {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.4);
        margin-bottom: 1rem;
        display: block;
    }

    .sc-partners-empty h3 {
        font-size: 1.5rem;
        color: var(--text-black);
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-partners-empty p {
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .mitra-hero { padding: 140px 0 80px; }
    }

    @media (max-width: 768px) {
        .mitra-hero { padding: 120px 0 60px; }

        .sc-partners-section { padding: 60px 0; }

        .sc-partners-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .sc-partner-card { padding: 1.5rem; }
    }
</style>

<!-- ═══════════════════════════════════════
     HERO BANNER — Konsisten dengan halaman lain
     ═══════════════════════════════════════ -->
<section class="mitra-hero">
    <div class="mitra-hero__dots"></div>

    <div class="container">
        <div class="mitra-hero__content" data-aos="fade-up">
            <div class="mitra-hero__badge">
                <span class="mitra-hero__badge-dot"></span>
                Kemitraan
            </div>
            <h1 class="mitra-hero__title">
                Mitra Strategis<br>
                <span class="text-blue">CoE Smart City</span>
            </h1>
            <p class="mitra-hero__subtitle">
                Membangun kolaborasi strategis dengan berbagai institusi dan organisasi
                untuk mengembangkan inovasi teknologi kota cerdas yang berkelanjutan.
            </p>
        </div>
    </div>

    <div class="mitra-hero__wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════
     DAFTAR MITRA
     ═══════════════════════════════════════ -->
<section class="sc-partners-section">
    <div class="container">

        @if ($partners->isNotEmpty())
            <div class="sc-partners-grid" data-aos="fade-up">
                @foreach ($partners as $index => $partner)
                    <div class="sc-partner-card" data-aos="zoom-in" data-aos-delay="{{ 80 + $index * 80 }}">
                        @if ($partner->logo_path)
                            <img src="{{ asset('storage/' . $partner->logo_path) }}"
                                 alt="{{ $partner->nama }}" class="sc-partner-logo">
                        @else
                            <div class="sc-partner-logo-placeholder">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif

                        <h3 class="sc-partner-name">{{ $partner->nama }}</h3>

                        @if ($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer"
                               class="sc-partner-website">
                                <i class="bi bi-globe"></i>
                                Kunjungi Website
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="sc-partners-empty" data-aos="fade-up">
                <i class="bi bi-people"></i>
                <h3>Belum Ada Mitra</h3>
                <p>Data mitra strategis akan ditampilkan di sini setelah ditambahkan melalui panel admin.</p>
            </div>
        @endif

    </div>
</section>
