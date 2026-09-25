@props(['partners' => collect()])

<style>
    @keyframes mitraPulse {
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

    .sc-partners-section {
        background: #fff;
        padding: 90px 0;
        position: relative;
    }

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
    }

    .sc-partner-website i {
        font-size: 12px;
    }

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

    @media (max-width: 768px) {
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

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Kemitraan</span>
        <h1 class="sc-page-hero__title">Mitra Strategis<br><span class="text-blue">CoE Smart City</span></h1>
        <p class="sc-page-hero__lead">
            Membangun kolaborasi strategis dengan berbagai institusi dan organisasi
                untuk mengembangkan inovasi teknologi kota cerdas yang berkelanjutan.
        </p>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

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
