@props(['partners' => collect()])

<style>
    /* ── Root (sinkron main.css) ─────────────────── */
    :root {
        --primary-blue: #4c8dc9;
        --primary-dark: #3a7ab3;
        --text-black: #000000;
        --text-gray: #7b7b7b;
        --bg-white: #ffffff;
        --bg-light: #f4f8fc;
        --nav-height: 50px;
    }

    /* ══════════════════════════════════════════════
       [HERO SECTION MITRA]
       ══════════════════════════════════════════════ */
    .sc-mitra-hero {
        min-height: 50vh;
        background: linear-gradient(135deg, #fff 0%, var(--bg-light) 100%);
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden;
        padding-top: var(--nav-height);
    }

    /* Dekorasi lingkaran */
    .sc-mitra-hero__circle {
        position: absolute;
        top: -80px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle,
                rgba(76, 141, 201, 0.08) 0%,
                rgba(76, 141, 201, 0.03) 55%,
                transparent 75%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Pola titik */
    .sc-mitra-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 20% 30%,
                black 5%, transparent 70%);
        pointer-events: none;
    }

    .sc-mitra-hero__content {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 4rem 2rem;
        position: relative;
        z-index: 2;
    }

    /* Badge label */
    .sc-mitra-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(76, 141, 201, 0.1);
        border: 1px solid rgba(76, 141, 201, 0.35);
        color: var(--primary-blue);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 7px 18px;
        border-radius: 100px;
        margin-bottom: 1.25rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-mitra-badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: badgePulse 2s infinite;
    }

    @keyframes badgePulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .4;
            transform: scale(.7);
        }
    }

    .sc-mitra-hero__title {
        font-size: clamp(2.2rem, 4vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        line-height: 1.2;
        margin-bottom: 1rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-mitra-hero__title .text-blue {
        color: var(--primary-blue);
    }

    .sc-mitra-hero__subtitle {
        font-size: 17px;
        color: var(--text-gray);
        line-height: 1.7;
        margin-bottom: 0;
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       [PARTNERS SECTION]
       ══════════════════════════════════════════════ */
    .sc-partners-section {
        background: #fff;
        padding: 90px 0;
        position: relative;
    }

    .sc-partners-heading {
        text-align: center;
        margin-bottom: 3.5rem;
    }

    .sc-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--primary-blue);
        margin-bottom: 1rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-label::before,
    .sc-label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .sc-partners-title {
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-partners-desc {
        font-size: 17px;
        color: var(--text-gray);
        line-height: 1.8;
        max-width: 600px;
        margin: 0 auto;
        font-family: "Lato", sans-serif;
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
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .sc-partner-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(76, 141, 201, 0.15);
        border-color: rgba(76, 141, 201, 0.2);
    }

    .sc-partner-card:hover::before {
        transform: scaleX(1);
    }

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
        text-decoration: none;
    }

    .sc-partner-website i {
        font-size: 12px;
    }

    /* Empty state */
    .sc-partners-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-light);
        border-radius: 16px;
        border: 2px dashed rgba(76, 141, 201, 0.3);
    }

    .sc-partners-empty i {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.4);
        margin-bottom: 1rem;
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
    @media (max-width: 768px) {
        .sc-mitra-hero__content {
            padding: 3rem 1.5rem;
        }

        .sc-partners-section {
            padding: 60px 0;
        }

        .sc-partners-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .sc-partner-card {
            padding: 1.5rem;
        }
    }
</style>

{{-- ============================================================
     [HERO SECTION]
     ============================================================ --}}
<section class="sc-mitra-hero">
    <div class="sc-mitra-hero__circle"></div>
    <div class="sc-mitra-hero__dots"></div>

    <div class="container">
        <div class="sc-mitra-hero__content" data-aos="fade-up" data-aos-duration="800">
            <div class="sc-mitra-badge">
                <span class="sc-mitra-badge-dot"></span>
                Universitas Telkom
            </div>

            <h1 class="sc-mitra-hero__title">
                Mitra Strategis<br>
                <span class="text-blue">CoE Smart City</span>
            </h1>

            <p class="sc-mitra-hero__subtitle">
                Membangun kolaborasi strategis dengan berbagai institusi dan organisasi
                untuk mengembangkan inovasi teknologi kota cerdas yang berkelanjutan.
            </p>
        </div>
    </div>
</section>

{{-- ============================================================
     [PARTNERS SECTION]
     ============================================================ --}}
<section class="sc-partners-section">
    <div class="container">

        <div class="sc-partners-heading" data-aos="fade-up">
            <div class="sc-label">Mitra Kami</div>
            <h2 class="sc-partners-title">Jejaring <span style="color: var(--primary-blue)">Kemitraan</span></h2>
            <p class="sc-partners-desc">
                Bersama dengan mitra strategis, kami berkomitmen untuk mewujudkan
                visi smart city melalui penelitian, pengembangan, dan implementasi
                teknologi inovatif.
            </p>
        </div>

        @if ($partners->isNotEmpty())
            <div class="sc-partners-grid" data-aos="fade-up" data-aos-delay="200">
                @foreach ($partners as $index => $partner)
                    <div class="sc-partner-card" data-aos="zoom-in" data-aos-delay="{{ 100 + $index * 100 }}">
                        @if ($partner->logo_path)
                            <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->nama }}"
                                class="sc-partner-logo">
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
            {{-- Empty state jika belum ada partner --}}
            <div class="sc-partners-empty" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-people"></i>
                <h3>Belum Ada Mitra</h3>
                <p>Data mitra strategis akan ditampilkan di sini setelah ditambahkan melalui panel admin.</p>
            </div>
        @endif

    </div>
</section>


