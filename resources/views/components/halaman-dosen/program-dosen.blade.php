@props(['programs' => collect()])

<style>
    /* ══════════════════════════════════════════════
       [PROGRAM - PAGE HERO]
       ══════════════════════════════════════════════ */
    .prog-hero {
        position: relative;
        padding: 160px 0 100px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        overflow: hidden;
    }

    .prog-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 80% 30%, black 5%, transparent 75%);
        pointer-events: none;
    }

    .prog-hero::before {
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

    .prog-hero__content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .prog-hero__badge {
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

    .prog-hero__badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: progPulse 2s infinite;
    }

    @keyframes progPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.7); }
    }

    .prog-hero__title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .prog-hero__title .text-blue {
        color: var(--primary-blue);
    }

    .prog-hero__subtitle {
        font-size: 1.15rem;
        color: var(--text-gray);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    .prog-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .prog-hero__wave svg {
        display: block;
        width: 100%;
    }

    /* ══════════════════════════════════════════════
       [PROGRAM - PENGANTAR]
       ══════════════════════════════════════════════ */
    .prog-intro {
        padding: 90px 0 50px;
        background: #fff;
    }

    .prog-intro__grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .prog-intro__label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--primary-blue);
        margin-bottom: 1rem;
        font-family: "Spline Sans", sans-serif;
    }

    .prog-intro__label::before,
    .prog-intro__label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .prog-intro__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1.25rem;
        line-height: 1.2;
        font-family: "Spline Sans", sans-serif;
    }

    .prog-intro__text {
        font-size: 16px;
        line-height: 1.85;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    .prog-intro__stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .prog-intro__stat-card {
        background: var(--bg-light, #f4f8fc);
        border-radius: 16px;
        padding: 28px 24px;
        text-align: center;
        border: 1px solid rgba(76, 141, 201, 0.1);
        transition: all 0.3s;
    }

    .prog-intro__stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(76, 141, 201, 0.12);
        border-color: rgba(76, 141, 201, 0.25);
    }

    .prog-intro__stat-num {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-blue);
        font-family: "Spline Sans", sans-serif;
        line-height: 1;
        margin-bottom: 6px;
    }

    .prog-intro__stat-lbl {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ══════════════════════════════════════════════
       [PROGRAM - DAFTAR KARTU]
       ══════════════════════════════════════════════ */
    .prog-list {
        padding: 50px 0 90px;
        background: #fff;
    }

    .prog-list__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .prog-list__subtitle {
        font-size: 16px;
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 3rem;
        font-family: "Lato", sans-serif;
    }

    .prog-list__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    .prog-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
    }

    .prog-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(76, 141, 201, 0.18);
    }

    .prog-card__img-wrap {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
    }

    .prog-card__img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .prog-card:hover .prog-card__img-wrap img {
        transform: scale(1.08);
    }

    .prog-card__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: rgba(76, 141, 201, 0.3);
    }

    .prog-card__body {
        padding: 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .prog-card__title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.6rem;
        font-family: "Spline Sans", sans-serif;
    }

    .prog-card__desc {
        font-size: 14.5px;
        color: var(--text-gray);
        line-height: 1.7;
        font-family: "Lato", sans-serif;
        flex: 1;
    }

    /* Fallback jika tidak ada program */
    .prog-list__empty {
        text-align: center;
        padding: 60px 20px;
    }

    .prog-list__empty-icon {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 1rem;
    }

    .prog-list__empty-text {
        font-size: 1.1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .prog-intro__grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .prog-list__grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .prog-hero {
            padding: 140px 0 80px;
        }
    }

    @media (max-width: 576px) {
        .prog-list__grid {
            grid-template-columns: 1fr;
        }

        .prog-intro__stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .prog-hero {
            padding: 120px 0 60px;
        }
    }
</style>

<!-- ═══════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════ -->
<section class="prog-hero">
    <div class="prog-hero__dots"></div>

    <div class="container">
        <div class="prog-hero__content" data-aos="fade-up">
            <div class="prog-hero__badge">
                <span class="prog-hero__badge-dot"></span>
                Program Kami
            </div>
            <h1 class="prog-hero__title">
                Program dari CoE<br>
                <span class="text-blue">Smart City</span>
            </h1>
            <p class="prog-hero__subtitle">
                Berbagai program unggulan yang dirancang untuk mengintegrasikan teknologi, riset, dan layanan demi meningkatkan kualitas hidup di kota-kota modern.
            </p>
        </div>
    </div>

    <div class="prog-hero__wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════
     PENGANTAR PROGRAM
     ═══════════════════════════════════════ -->
<section class="prog-intro">
    <div class="container">
        <div class="prog-intro__grid">
            <!-- Kolom Teks -->
            <div data-aos="fade-right">
                <div class="prog-intro__label">Mengapa Program Kami</div>
                <h2 class="prog-intro__heading">
                    Solusi Inovatif untuk<br>Kota Masa Depan
                </h2>
                <p class="prog-intro__text">
                    Program CoE Smart City dirancang berdasarkan kebutuhan nyata masyarakat perkotaan. Melalui pendekatan interdisipliner, kami mengembangkan solusi yang mencakup transportasi cerdas, tata kelola digital, pengelolaan lingkungan berbasis data, dan pemberdayaan komunitas melalui teknologi.
                </p>
            </div>

            <!-- Kolom Statistik -->
            <div data-aos="fade-left">
                <div class="prog-intro__stats">
                    <div class="prog-intro__stat-card">
                        <div class="prog-intro__stat-num">{{ $programs->count() }}</div>
                        <div class="prog-intro__stat-lbl">Program Aktif</div>
                    </div>
                    <div class="prog-intro__stat-card">
                        <div class="prog-intro__stat-num">4</div>
                        <div class="prog-intro__stat-lbl">Pilar Utama</div>
                    </div>
                    <div class="prog-intro__stat-card">
                        <div class="prog-intro__stat-num">10+</div>
                        <div class="prog-intro__stat-lbl">Mitra Kerja</div>
                    </div>
                    <div class="prog-intro__stat-card">
                        <div class="prog-intro__stat-num">50+</div>
                        <div class="prog-intro__stat-lbl">Peneliti Terlibat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     DAFTAR PROGRAM
     ═══════════════════════════════════════ -->
<section class="prog-list">
    <div class="container">
        <div data-aos="fade-up">
            <div class="prog-intro__label" style="justify-content: center;">Daftar Program</div>
            <h2 class="prog-list__heading">Program <span style="color: var(--primary-blue);">Unggulan Kami</span></h2>
            <p class="prog-list__subtitle">Eksplorasi program-program yang sedang kami jalankan untuk membangun kota cerdas Indonesia.</p>
        </div>

        @if($programs->isNotEmpty())
            <div class="prog-list__grid">
                @foreach($programs as $program)
                    <div class="prog-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="prog-card__img-wrap">
                            @if($program->thumbnail_path)
                                <img src="{{ asset('storage/'.$program->thumbnail_path) }}"
                                     alt="{{ $program->judul }}">
                            @else
                                <div class="prog-card__img-placeholder">
                                    <i class="bi bi-layers"></i>
                                </div>
                            @endif
                        </div>
                        <div class="prog-card__body">
                            <h3 class="prog-card__title">{{ $program->judul }}</h3>
                            <p class="prog-card__desc">
                                {{ \Illuminate\Support\Str::limit($program->deskripsi, 150) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="prog-list__empty" data-aos="fade-up">
                <div class="prog-list__empty-icon">
                    <i class="bi bi-layers"></i>
                </div>
                <p class="prog-list__empty-text">Belum ada program yang tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>
