@php
    $isDosenArea = auth()->check() && auth()->user()->role === 'dosen' && auth()->user()->registration_status === \App\Models\User::STATUS_APPROVED;
    $projectUrl = url($isDosenArea ? '/project-dosen' : '/project-user');
    $aboutUrl = url($isDosenArea ? '/about-dosen' : '/about-user');
@endphp
<style>
    .sc-hero {
        min-height: 80vh;
        background: #fff;
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden;
        padding-top: var(--nav-height);
    }

    .sc-hero__circle-lg {
        position: absolute;
        top: -100px;
        right: -120px;
        width: 650px;
        height: 650px;
        background: radial-gradient(circle,
                rgba(76, 141, 201, 0.13) 0%,
                rgba(76, 141, 201, 0.04) 55%,
                transparent 75%);
        border-radius: 50%;
        pointer-events: none;
    }

    .sc-hero__circle-sm {
        position: absolute;
        bottom: 40px;
        left: -90px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle,
                rgba(76, 141, 201, 0.08) 0%,
                transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .sc-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.16) 1.5px, transparent 1.5px);
        background-size: 34px 34px;
        mask-image: radial-gradient(ellipse 48% 62% at 84% 24%,
                black 5%, transparent 80%);
        pointer-events: none;
    }

    .sc-hero__geo {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        animation: geoFloat ease-in-out infinite;
    }

    .sc-hero__geo--a {
        width: 18px;
        height: 18px;
        background: var(--primary-blue);
        opacity: .20;
        top: 20%;
        left: 6%;
        animation-duration: 5s;
    }

    .sc-hero__geo--b {
        width: 10px;
        height: 10px;
        background: var(--primary-blue);
        opacity: .14;
        top: 64%;
        left: 13%;
        animation-duration: 7s;
        animation-delay: -2s;
    }

    .sc-hero__geo--c {
        width: 13px;
        height: 13px;
        background: var(--primary-blue);
        opacity: .16;
        top: 40%;
        right: 7%;
        animation-duration: 6s;
        animation-delay: -3s;
    }

    .sc-hero__geo--d {
        width: 8px;
        height: 8px;
        background: #a8d0f0;
        opacity: .28;
        top: 76%;
        right: 21%;
        animation-duration: 9s;
        animation-delay: -1s;
    }

    .sc-hero__geo--e {
        width: 22px;
        height: 22px;
        background: var(--primary-blue);
        opacity: .08;
        top: 55%;
        left: 40%;
        animation-duration: 11s;
        animation-delay: -5s;
    }

    @keyframes geoFloat {
        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-18px) rotate(20deg);
        }
    }

    .sc-hero__grid {
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 2.5rem;
        align-items: center;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem 2rem 4rem;
        position: relative;
        z-index: 2;
    }

    .sc-hero__badge {
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

    .sc-hero__badge-dot {
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

    .sc-hero__h1 {
        font-size: clamp(2.4rem, 4.8vw, 3.8rem);
        font-weight: 700;
        color: var(--text-black);
        line-height: 1.1;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-hero__h1 .text-blue {
        color: var(--primary-blue);
    }

    .sc-hero__divider {
        width: 50px;
        height: 3px;
        background: var(--primary-blue);
        border-radius: 2px;
        margin: 1.25rem 0;
    }

    .sc-hero__subtitle {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.6rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-hero__desc {
        font-size: 16px;
        color: var(--text-gray);
        line-height: 1.78;
        margin-bottom: 2.25rem;
        max-width: 480px;
        font-family: "Lato", sans-serif;
    }

    .sc-hero__cta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .sc-btn-proyek {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-blue);
        color: #fff;
        text-decoration: none;
        padding: 14px 28px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 15px;
        font-family: "Spline Sans", sans-serif;
        transition: all 0.3s;
        box-shadow: 0 6px 20px rgba(76, 141, 201, 0.38);
    }

    .sc-btn-proyek:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(76, 141, 201, 0.48);
        color: #fff;
    }

    .sc-btn-proyek__arrow {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .sc-btn-wa {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--text-black);
        transition: color 0.3s;
    }

    .sc-btn-wa:hover {
        color: var(--primary-blue);
    }

    .sc-btn-wa__icon {
        width: 52px;
        height: 52px;
        background: #eef5fc;
        border: 2px solid rgba(76, 141, 201, 0.22);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--primary-blue);
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .sc-btn-wa:hover .sc-btn-wa__icon {
        background: var(--primary-blue);
        color: #fff;
        border-color: var(--primary-blue);
    }

    .sc-btn-wa__label {
        font-size: 12px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        line-height: 1;
    }

    .sc-btn-wa__number {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
    }

    .sc-hero__photo-col {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sc-hero__blob {
        position: absolute;
        right: -15px;
        top: 5%;
        width: 90%;
        height: 88%;
        background: linear-gradient(135deg, var(--primary-blue) 0%, #1a4f80 100%);
        border-radius: 50% 40% 52% 40%;
        z-index: 0;
        opacity: 0.11;
        animation: blobMorph 10s ease-in-out infinite alternate;
    }

    @keyframes blobMorph {
        0% {
            border-radius: 50% 40% 52% 40%;
        }

        100% {
            border-radius: 40% 55% 42% 56%;
        }
    }

    .sc-hero__ring {
        position: absolute;
        width: 95%;
        height: 95%;
        border: 2px dashed rgba(76, 141, 201, 0.18);
        border-radius: 50%;
        z-index: 0;
        animation: ringRotate 30s linear infinite;
    }

    @keyframes ringRotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .sc-hero__photo {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 520px;
        aspect-ratio: 1;
        border-radius: 50%;
        object-fit: cover;
        object-position: center top;
        box-shadow:
            0 24px 70px rgba(76, 141, 201, 0.22),
            0 0 0 10px rgba(76, 141, 201, 0.08),
            0 0 0 20px rgba(76, 141, 201, 0.04);
        display: block;
    }

    .sc-hero__photo-placeholder {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 520px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: linear-gradient(135deg, #e4f0fb, #c2d9f0);
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 10px;
        border: 3px solid rgba(76, 141, 201, 0.15);
        box-shadow: 0 20px 60px rgba(76, 141, 201, 0.12);
    }

    .sc-hero__photo-placeholder i {
        font-size: 56px;
        color: rgba(76, 141, 201, 0.35);
    }

    .sc-hero__photo-placeholder span {
        font-size: 13px;
        color: rgba(76, 141, 201, 0.45);
        text-align: center;
        font-family: "Lato", sans-serif;
    }

    .sc-hero__info-card {
        position: absolute;
        bottom: 8%;
        left: -5%;
        background: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 8px 30px rgba(76, 141, 201, 0.18);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 2;
        animation: cardFloat 5s ease-in-out infinite;
        border-left: 4px solid var(--primary-blue);
    }

    .sc-hero__info-card-2 {
        position: absolute;
        top: 8%;
        right: -5%;
        background: #fff;
        border-radius: 14px;
        padding: 12px 16px;
        box-shadow: 0 8px 30px rgba(76, 141, 201, 0.18);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 2;
        animation: cardFloat 6s ease-in-out infinite;
        animation-delay: -3s;
        border-left: 4px solid var(--primary-blue);
    }

    @keyframes cardFloat {
        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    .info-card__icon {
        width: 38px;
        height: 38px;
        background: rgba(76, 141, 201, 0.12);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 17px;
        flex-shrink: 0;
    }

    .info-card__num {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
        line-height: 1;
    }

    .info-card__lbl {
        font-size: 11px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        line-height: 1.3;
    }

    .sc-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .sc-hero__wave svg {
        display: block;
    }

    .sc-tentang {
        background: var(--bg-light);
        padding: 90px 0;
        position: relative;
    }

    .sc-label {
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

    .sc-label::before,
    .sc-label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .sc-tentang__heading {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1.25rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-tentang__desc {
        font-size: 17px;
        line-height: 1.85;
        color: var(--text-gray);
        margin-bottom: 2rem;
        font-family: "Lato", sans-serif;
        max-width: 700px;
    }

    .sc-btn-pelajari {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-blue);
        color: #fff;
        text-decoration: none;
        padding: 14px 28px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 15px;
        font-family: "Spline Sans", sans-serif;
        box-shadow: 0 6px 20px rgba(76, 141, 201, 0.32);
        transition: all 0.3s;
    }

    .sc-btn-pelajari:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        color: #fff;
    }

    .sc-btn-pelajari__arrow {
        width: 28px;
        height: 28px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .sc-akt__row {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 1.4rem;
    }

    .sc-akt__icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(76, 141, 201, 0.32);
    }

    .sc-akt__text {
        font-size: 17px;
        font-weight: 600;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
        line-height: 1.4;
    }

    .sc-akt__text small {
        font-family: "Lato", sans-serif;
        font-weight: 400;
        color: var(--text-gray);
        font-size: 14px;
    }

    .sc-fakta {
        background: #fff;
        padding: 90px 0;
    }

    .sc-fakta__heading {
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-fakta__stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 2.5rem 2rem;
        margin: 2.75rem 0 3.5rem;
    }

    .sc-fakta__num {
        font-size: clamp(2.8rem, 5.5vw, 4.2rem);
        font-weight: 700;
        color: var(--text-black);
        line-height: 1;
        margin-bottom: 8px;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-fakta__num .plus {
        color: var(--primary-blue);
    }

    .sc-fakta__lbl {
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: "Spline Sans", sans-serif;
    }

    .sc-fakta__lbl::before {
        content: '•';
        font-size: 16px;
        color: var(--primary-blue);
    }

    .sc-fakta__tabbar {
        display: flex;
        gap: 12px;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }

    .sc-fakta__tab {
        padding: 12px 24px;
        border-radius: 30px;
        border: none;
        font-family: "Spline Sans", sans-serif;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s;
    }

    .sc-fakta__tab.active {
        background: #222;
        color: #fff;
    }

    .sc-fakta__tab:not(.active) {
        background: var(--primary-blue);
        color: #fff;
    }

    .sc-fakta__tab:not(.active):hover {
        background: var(--primary-dark);
    }

    .sc-fakta__pane {
        display: none;
    }

    .sc-fakta__pane.active {
        display: block;
    }

    .sc-fakta__list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
        gap: 10px;
    }

    .sc-fakta__list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 500;
        color: var(--text-black);
        font-family: "Lato", sans-serif;
        padding: 13px 17px;
        background: var(--bg-light);
        border-radius: 8px;
        border-left: 3px solid var(--primary-blue);
        transition: all 0.2s;
    }

    .sc-fakta__list li:hover {
        background: #ddeef9;
        border-color: var(--primary-dark);
        transform: translateX(4px);
    }

    .sc-fakta__list li::before {
        content: '';
        width: 6px;
        height: 6px;
        background: var(--primary-blue);
        border-radius: 50%;
        flex-shrink: 0;
    }

    @media (max-width: 991px) {
        .sc-hero__grid {
            grid-template-columns: 1fr;
            padding: 3rem 1.5rem 6rem;
        }

        .sc-hero__photo-col {
            display: none;
        }

        .sc-hero__desc {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .sc-hero__cta {
            flex-direction: column;
            align-items: flex-start;
        }

        .sc-fakta__stats {
            grid-template-columns: 1fr 1fr;
        }
    }

    .sc-label--spaced {
        margin-top: 2.75rem;
    }

    .sc-label--center {
        justify-content: center;
    }
</style>

<section class="sc-hero" id="beranda">

    <div class="sc-hero__circle-lg"></div>
    <div class="sc-hero__circle-sm"></div>
    <div class="sc-hero__dots"></div>
    <div class="sc-hero__geo sc-hero__geo--a"></div>
    <div class="sc-hero__geo sc-hero__geo--b"></div>
    <div class="sc-hero__geo sc-hero__geo--c"></div>
    <div class="sc-hero__geo sc-hero__geo--d"></div>
    <div class="sc-hero__geo sc-hero__geo--e"></div>

    <div class="sc-hero__grid">

        <div data-aos="fade-up" data-aos-duration="800">

            <div class="sc-hero__badge">
                <span class="sc-hero__badge-dot"></span>
                Universitas Telkom
            </div>

            <h1 class="sc-hero__h1">
                Center of Excellence<br>
                <span class="text-blue">Smart City</span>
            </h1>

            <div class="sc-hero__divider"></div>

            <div class="sc-hero__subtitle">Riset, inovasi, dan kolaborasi untuk kota yang lebih cerdas</div>

            <p class="sc-hero__desc">
                Pusat unggulan Universitas Telkom yang mempertemukan akademisi, industri,
                dan pemerintah untuk merancang solusi kota cerdas berbasis data,
                Internet of Things, dan kecerdasan buatan.
            </p>

            <div class="sc-hero__cta">
                <a href="{{ $projectUrl }}" class="sc-btn-proyek">
                    <span>Proyek Kami</span>
                    <span class="sc-btn-proyek__arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
                <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" class="sc-btn-wa" target="_blank" rel="noopener">
                    <div class="sc-btn-wa__icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <div class="sc-btn-wa__label">Hubungi Kami</div>
                        <div class="sc-btn-wa__number">{{ config('smartcity.phone_display') }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="sc-hero__photo-col" data-aos="fade-left" data-aos-duration="900" data-aos-delay="150">

            <div class="sc-hero__blob"></div>
            <div class="sc-hero__ring"></div>

            <img src="{{ asset('img/fotosukses.png') }}" alt="Tim CoE Smart City Universitas Telkom" class="sc-hero__photo" width="560" height="560"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="sc-hero__photo-placeholder">
                <i class="bi bi-people-fill"></i>
                <span>Foto Tim CoE<br>Smart City</span>
            </div>

            <div class="sc-hero__info-card">
                <div class="info-card__icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="info-card__num">{{ \App\Models\Team::published()->count() ?: '59' }}+</div>
                    <div class="info-card__lbl">Anggota Aktif</div>
                </div>
            </div>

            <div class="sc-hero__info-card-2">
                <div class="info-card__icon"><i class="bi bi-journal-richtext"></i></div>
                <div>
                    <div class="info-card__num">{{ \App\Models\Publication::where('status','Publish')->count() ?: '63' }}+</div>
                    <div class="info-card__lbl">Publikasi</div>
                </div>
            </div>

        </div>
    </div>

    <div class="sc-hero__wave">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,55 C360,95 720,15 1080,60 C1260,83 1380,48 1440,58 L1440,90 L0,90 Z"
                fill="rgba(76,141,201,0.06)" />
            <path d="M0,68 C300,100 600,34 900,68 C1050,85 1260,50 1440,64 L1440,90 L0,90 Z" fill="#f4f8fc" />
        </svg>
    </div>

</section>

<section class="sc-tentang" id="tentang">
    <div class="container">

        <div class="row" data-aos="fade-up">
            <div class="col-12">

                <div class="sc-label">Tentang Kami</div>
                <h2 class="sc-tentang__heading">CoE Smart City</h2>
                <p class="sc-tentang__desc">
                    CoE Smart City adalah unit strategis Universitas Telkom yang mempercepat lahirnya riset,
                    inovasi, dan layanan masyarakat di bidang kota cerdas. Kami mengubah hasil penelitian
                    menjadi produk, rekomendasi kebijakan, dan pendampingan yang bisa langsung dimanfaatkan
                    oleh pemerintah daerah, industri, dan warga.
                </p>

                <a href="{{ $aboutUrl }}" class="sc-btn-pelajari">
                    <span>Pelajari Lebih Lanjut</span>
                    <span class="sc-btn-pelajari__arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>

                <div class="sc-label sc-label--spaced">Aktivitas Kami</div>

            </div>
        </div>

        <div class="row g-4 mt-1">

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="sc-akt__row">
                    <div class="sc-akt__icon"><i class="bi bi-pie-chart-fill"></i></div>
                    <div class="sc-akt__text">
                        Pengembangan Lembaga dan SDM
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="sc-akt__row">
                    <div class="sc-akt__icon"><i class="bi bi-display"></i></div>
                    <div class="sc-akt__text">
                        Keunggulan Akademik
                        <br><small>Riset dan publikasi ilmiah</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="sc-akt__row">
                    <div class="sc-akt__icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <div class="sc-akt__text">
                        Komersialisasi
                        <br><small>Kerja sama pemanfaatan produk hasil riset</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="sc-fakta" id="fakta">
    <div class="container">

        <div class="text-center" data-aos="fade-up">
            <div class="sc-label sc-label--center">Fakta Smart City</div>
            <h2 class="sc-fakta__heading">Statistik Smart City</h2>
        </div>

        @php
            $statAnggota    = \App\Models\Team::published()->count();
            $statProject    = \App\Models\Project::published()->count();
            $statPublication = \App\Models\Publication::where('status','Publish')->count();
            $statProgram    = \App\Models\Program::published()->count();
            $statMitra      = \App\Models\Partner::published()->count();
        @endphp
        <div class="sc-fakta__stats" data-aos="fade-up" data-aos-delay="100">

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="{{ max($statAnggota, 59) }}">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Anggota</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="{{ max($statProject, 14) }}">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Proyek</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="{{ max($statPublication, 63) }}">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Publikasi</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="{{ max($statProgram, 7) }}">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Program</div>
            </div>

            <div>
                <div class="sc-fakta__num">
                    <span class="js-counter" data-target="{{ max($statMitra, 4) }}">0</span><span class="plus">+</span>
                </div>
                <div class="sc-fakta__lbl">Jumlah Mitra</div>
            </div>

        </div>

        <div data-aos="fade-up" data-aos-delay="150">

            <div class="sc-fakta__tabbar">
                <button class="sc-fakta__tab active" data-pane="sc-tab-int">Proyek Internasional</button>
                <button class="sc-fakta__tab" data-pane="sc-tab-nas">Proyek Nasional</button>
                <button class="sc-fakta__tab" data-pane="sc-tab-ung">Produk Unggulan</button>
            </div>

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

            <div id="sc-tab-ung" class="sc-fakta__pane">
                <ul class="sc-fakta__list">
                    <li>Sistem Monitoring Kota Cerdas</li>
                    <li>Platform IoT Terintegrasi</li>
                    <li>Manajemen Lalu Lintas Berbasis AI</li>
                    <li>Solusi Energi Smart Grid</li>
                </ul>
            </div>

        </div>
    </div>
</section>

<section class="about-section" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-up">
                <h2>Tentang Kami</h2>
                <p>CoE Smart City di Universitas Telkom adalah unit strategis untuk mempercepat riset, inovasi,
                    bisnis, dan layanan masyarakat, serta berkontribusi pada ilmu pengetahuan, teknologi, manajemen,
                    dan seni.</p>
                <img src="{{ asset('img/fotoaboutus.jpg') }}" alt="Kegiatan tim CoE Smart City" class="about-image" loading="lazy" data-aos="fade-up">
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
                        diimplementasikan untuk meningkatkan kualitas hidup dan efektivitas layanan kota.</p>
                </div>
                <div class="feature-item" data-aos="fade-up">
                    <h3>Bisnis</h3>
                    <p>CoE Smart City menjembatani hasil riset dengan kebutuhan pasar melalui kemitraan industri,
                        lisensi teknologi, dan komersialisasi produk inovasi yang berkelanjutan.</p>
                </div>
                <div class="feature-item" data-aos="fade-up">
                    <h3>Layanan Masyarakat</h3>
                    <p>CoE Smart City berkontribusi melalui program dan inisiatif yang dirancang untuk memberdayakan
                        komunitas, meningkatkan kesejahteraan sosial, dan menyelesaikan masalah-masalah kawasan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $homePrograms = \App\Models\Program::published()->orderBy('urutan')->limit(4)->get();
    $homeGradients = ['blue-gradient', 'purple-gradient', 'cyan-gradient', 'orange-gradient'];
    $homeFallbacks = [
        ['img' => 'card1.jpg', 'title' => 'Program Kota Cerdas', 'desc' => 'Mengintegrasikan data, teknologi, dan tata kelola untuk meningkatkan kualitas hidup di kota-kota modern.'],
        ['img' => 'card2.jpg', 'title' => 'Inovasi Masa Depan', 'desc' => 'Mengembangkan solusi inovatif berbasis sistem pintar yang siap diterapkan untuk kebutuhan kota.'],
        ['img' => 'card3.jpg', 'title' => 'Infrastruktur Cerdas', 'desc' => 'Membangun infrastruktur terhubung yang membuat layanan kota lebih efisien, aman, dan mudah dipantau.'],
        ['img' => 'card4.jpg', 'title' => 'Kota Berkelanjutan', 'desc' => 'Memanfaatkan teknologi hijau dan data lingkungan untuk mewujudkan kota yang ramah dan berkelanjutan.'],
    ];
@endphp
<section class="cards-section">
    <div class="container">
        <div class="row g-4">
            @if($homePrograms->isNotEmpty())
                @foreach($homePrograms as $i => $prog)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up">
                        <div class="program-card">
                            <div class="card-image">
                                @if($prog->thumbnail_path)
                                    <img src="{{ asset('storage/'.$prog->thumbnail_path) }}" alt="{{ $prog->judul }}" loading="lazy">
                                @else
                                    <div class="card-image__placeholder"><i class="bi bi-layers" aria-hidden="true"></i></div>
                                @endif
                            </div>
                            <div class="card-content {{ $homeGradients[$i % 4] }}">
                                <h3>{{ $prog->judul }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($prog->deskripsi, 100) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @foreach($homeFallbacks as $i => $fb)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up">
                        <div class="program-card">
                            <div class="card-image">
                                <img src="{{ asset('img/'.$fb['img']) }}" alt="{{ $fb['title'] }}" loading="lazy">
                            </div>
                            <div class="card-content {{ $homeGradients[$i] }}">
                                <h3>{{ $fb['title'] }}</h3>
                                <p>{{ $fb['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

@php
    $homeProjects = \App\Models\Project::published()->orderByDesc('tahun')->limit(5)->get();
@endphp
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="gallery-title">
                Proyek <a href="{{ $projectUrl }}">Kami</a>
            </h2>
            <p class="gallery-subtitle">Dokumentasi proyek-proyek inovatif dalam pengembangan Smart City.</p>
        </div>
        <div class="gallery-carousel" data-aos="fade-up">
            <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @if($homeProjects->isNotEmpty())
                        @foreach($homeProjects as $idx => $proj)
                            @php
                                $imgSrc = null;
                                if (!empty($proj->gallery_paths) && count($proj->gallery_paths) > 0) {
                                    $imgSrc = asset('storage/' . $proj->gallery_paths[0]);
                                } elseif ($proj->thumbnail_path) {
                                    $imgSrc = asset('storage/' . $proj->thumbnail_path);
                                }
                            @endphp
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                <div class="carousel-image-container">
                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}" class="d-block w-100" alt="{{ $proj->judul }}" loading="lazy">
                                    @else
                                        <div class="card-image__placeholder"><i class="bi bi-kanban" aria-hidden="true"></i></div>
                                    @endif
                                    <div class="carousel-caption">
                                        <h3>{{ $proj->judul }}</h3>
                                        <p>{{ \Illuminate\Support\Str::limit($proj->deskripsi, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active">
                            <div class="carousel-image-container">
                                <img src="{{ asset('img/card1.jpg') }}" class="d-block w-100" alt="Dokumentasi proyek CoE Smart City" loading="lazy">
                                <div class="carousel-caption">
                                    <h3>Laboratorium Riset Kota Cerdas</h3>
                                    <p>Kolaborasi tim peneliti dalam mengembangkan solusi teknologi kota cerdas.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-image-container">
                                <img src="{{ asset('img/card2.jpg') }}" class="d-block w-100" alt="Dokumentasi proyek CoE Smart City" loading="lazy">
                                <div class="carousel-caption">
                                    <h3>Lokakarya Inovasi</h3>
                                    <p>Lokakarya pengembangan sistem pemantauan berbasis IoT bersama mitra.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-image-container">
                                <img src="{{ asset('img/card3.jpg') }}" class="d-block w-100" alt="Dokumentasi proyek CoE Smart City" loading="lazy">
                                <div class="carousel-caption">
                                    <h3>Pengembangan Kota Cerdas</h3>
                                    <p>Implementasi teknologi AI dalam manajemen lalu lintas kota.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if($homeProjects->count() > 1 || $homeProjects->isEmpty())
                    <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Proyek sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Proyek berikutnya</span>
                    </button>
                    <div class="carousel-indicators">
                        @if($homeProjects->isNotEmpty())
                            @foreach($homeProjects as $idx => $proj)
                                <button type="button" data-bs-target="#galleryCarousel"
                                        data-bs-slide-to="{{ $idx }}"
                                        class="{{ $idx === 0 ? 'active' : '' }}" aria-label="Tampilkan proyek {{ $idx + 1 }}"></button>
                            @endforeach
                        @else
                            <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="0" class="active" aria-label="Tampilkan proyek 1"></button>
                            <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="1" aria-label="Tampilkan proyek 2"></button>
                            <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="2" aria-label="Tampilkan proyek 3"></button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
    function scRunCounter(el) {
            var target = parseInt(el.dataset.target, 10);
            var duration = 1800;
            var step = target / (duration / 16);
            var current = 0;
            var timer = setInterval(function() {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = Math.floor(current);
            }, 16);
        }

        var scCounterDone = false;
        var scFaktaEl = document.querySelector('.sc-fakta');
        if (scFaktaEl) {
            new IntersectionObserver(function(entries) {
                if (entries[0].isIntersecting && !scCounterDone) {
                    scCounterDone = true;
                    document.querySelectorAll('.js-counter').forEach(function(el) {
                        scRunCounter(el);
                    });
                }
            }, {
                threshold: 0.25
            }).observe(scFaktaEl);
        }

        document.querySelectorAll('.sc-fakta__tab').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.sc-fakta__tab').forEach(function(b) {
                    b.classList.remove('active');
                });
                document.querySelectorAll('.sc-fakta__pane').forEach(function(p) {
                    p.classList.remove('active');
                });
                btn.classList.add('active');
                var pane = document.getElementById(btn.dataset.pane);
                if (pane) pane.classList.add('active');
            });
        });
</script>
