<style>
    /* ══════════════════════════════════════════════
       [TENTANG KAMI - PAGE HERO]
       ══════════════════════════════════════════════ */
    .about-hero {
        position: relative;
        padding: 160px 0 100px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        overflow: hidden;
    }

    .about-hero::before {
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

    .about-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: -80px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(76, 141, 201, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .about-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 80% 30%, black 5%, transparent 75%);
        pointer-events: none;
    }

    .about-hero__content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .about-hero__badge {
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

    .about-hero__badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: aboutPulse 2s infinite;
    }

    @keyframes aboutPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.7); }
    }

    .about-hero__title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .about-hero__title .text-blue {
        color: var(--primary-blue);
    }

    .about-hero__subtitle {
        font-size: 1.15rem;
        color: var(--text-gray);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    /* Gelombang bawah hero */
    .about-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .about-hero__wave svg {
        display: block;
        width: 100%;
    }

    /* ══════════════════════════════════════════════
       [TENTANG KAMI - DESKRIPSI SECTION]
       ══════════════════════════════════════════════ */
    .about-desc {
        padding: 90px 0;
        background: #fff;
    }

    .about-desc__grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .about-desc__img-wrap {
        position: relative;
    }

    .about-desc__img-wrap::before {
        content: '';
        position: absolute;
        top: -15px;
        left: -15px;
        width: 100%;
        height: 100%;
        border: 3px solid rgba(76, 141, 201, 0.2);
        border-radius: 20px;
        z-index: 0;
    }

    .about-desc__img {
        position: relative;
        z-index: 1;
        width: 100%;
        border-radius: 20px;
        object-fit: cover;
        box-shadow: 0 20px 60px rgba(76, 141, 201, 0.18);
    }

    .about-desc__floating-card {
        position: absolute;
        bottom: -20px;
        right: -20px;
        background: #fff;
        border-radius: 14px;
        padding: 16px 22px;
        box-shadow: 0 10px 35px rgba(76, 141, 201, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 2;
        border-left: 4px solid var(--primary-blue);
        animation: aboutCardFloat 5s ease-in-out infinite;
    }

    @keyframes aboutCardFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .about-desc__floating-icon {
        width: 44px;
        height: 44px;
        background: rgba(76, 141, 201, 0.12);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 20px;
        flex-shrink: 0;
    }

    .about-desc__floating-num {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
        line-height: 1;
    }

    .about-desc__floating-lbl {
        font-size: 12px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    .about-desc__label {
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

    .about-desc__label::before,
    .about-desc__label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .about-desc__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1.25rem;
        line-height: 1.2;
        font-family: "Spline Sans", sans-serif;
    }

    .about-desc__text {
        font-size: 16px;
        line-height: 1.85;
        color: var(--text-gray);
        margin-bottom: 2rem;
        font-family: "Lato", sans-serif;
    }

    .about-desc__highlights {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .about-desc__highlight-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        background: var(--bg-light, #f4f8fc);
        border-radius: 12px;
        border-left: 3px solid var(--primary-blue);
        transition: all 0.3s;
    }

    .about-desc__highlight-item:hover {
        background: #e4f0fb;
        transform: translateX(6px);
    }

    .about-desc__highlight-icon {
        width: 42px;
        height: 42px;
        background: var(--primary-blue);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(76, 141, 201, 0.3);
    }

    .about-desc__highlight-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
    }

    /* ══════════════════════════════════════════════
       [PILAR UTAMA - CARDS]
       ══════════════════════════════════════════════ */
    .about-pillars {
        padding: 90px 0;
        background: var(--bg-light, #f4f8fc);
    }

    .about-pillars__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .about-pillars__subtitle {
        font-size: 16px;
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 3.5rem;
        font-family: "Lato", sans-serif;
    }

    .about-pillars__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .about-pillar-card {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    .about-pillar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-blue);
        transform: scaleX(0);
        transition: transform 0.4s;
    }

    .about-pillar-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(76, 141, 201, 0.18);
    }

    .about-pillar-card:hover::before {
        transform: scaleX(1);
    }

    .about-pillar-card__icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 1.5rem;
        transition: all 0.4s;
    }

    .about-pillar-card:hover .about-pillar-card__icon {
        transform: scale(1.1) rotate(5deg);
    }

    .about-pillar-card__icon--riset {
        background: rgba(76, 141, 201, 0.12);
        color: var(--primary-blue);
    }

    .about-pillar-card__icon--inovasi {
        background: rgba(115, 103, 240, 0.12);
        color: #7367f0;
    }

    .about-pillar-card__icon--bisnis {
        background: rgba(255, 183, 94, 0.15);
        color: #ed8f03;
    }

    .about-pillar-card__icon--layanan {
        background: rgba(0, 180, 219, 0.12);
        color: #00b4db;
    }

    .about-pillar-card__title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.75rem;
        font-family: "Spline Sans", sans-serif;
    }

    .about-pillar-card__text {
        font-size: 14.5px;
        color: var(--text-gray);
        line-height: 1.7;
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       [VISI & MISI]
       ══════════════════════════════════════════════ */
    .about-visimisi {
        padding: 90px 0;
        background: #fff;
    }

    .about-visimisi__grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
    }

    .about-visimisi__card {
        padding: 3rem;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .about-visimisi__card--visi {
        background: linear-gradient(135deg, var(--primary-blue) 0%, #2b6da8 100%);
        color: #fff;
    }

    .about-visimisi__card--misi {
        background: var(--bg-light, #f4f8fc);
        border: 2px solid rgba(76, 141, 201, 0.15);
    }

    .about-visimisi__card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        pointer-events: none;
    }

    .about-visimisi__card--visi::before {
        background: rgba(255, 255, 255, 0.08);
    }

    .about-visimisi__card--misi::before {
        background: rgba(76, 141, 201, 0.06);
    }

    .about-visimisi__icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1.5rem;
    }

    .about-visimisi__card--visi .about-visimisi__icon {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .about-visimisi__card--misi .about-visimisi__icon {
        background: rgba(76, 141, 201, 0.12);
        color: var(--primary-blue);
    }

    .about-visimisi__title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        font-family: "Spline Sans", sans-serif;
    }

    .about-visimisi__card--visi .about-visimisi__title {
        color: #fff;
    }

    .about-visimisi__card--misi .about-visimisi__title {
        color: var(--text-black);
    }

    .about-visimisi__text {
        font-size: 15.5px;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    .about-visimisi__card--visi .about-visimisi__text {
        color: rgba(255, 255, 255, 0.9);
    }

    .about-visimisi__card--misi .about-visimisi__text {
        color: var(--text-gray);
    }

    .about-visimisi__list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .about-visimisi__list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        font-size: 15px;
        line-height: 1.6;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    .about-visimisi__list li i {
        color: var(--primary-blue);
        font-size: 16px;
        margin-top: 4px;
        flex-shrink: 0;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .about-desc__grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .about-pillars__grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .about-visimisi__grid {
            grid-template-columns: 1fr;
        }

        .about-hero {
            padding: 140px 0 80px;
        }
    }

    @media (max-width: 576px) {
        .about-pillars__grid {
            grid-template-columns: 1fr;
        }

        .about-hero {
            padding: 120px 0 60px;
        }

        .about-desc__floating-card {
            right: 10px;
            bottom: -15px;
        }

        .about-visimisi__card {
            padding: 2rem;
        }
    }
</style>

<!-- ═══════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════ -->
<section class="about-hero">
    <div class="about-hero__dots"></div>

    <div class="container">
        <div class="about-hero__content" data-aos="fade-up">
            <div class="about-hero__badge">
                <span class="about-hero__badge-dot"></span>
                Tentang Kami
            </div>
            <h1 class="about-hero__title">
                Center of Excellence<br>
                <span class="text-blue">Smart City</span>
            </h1>
            <p class="about-hero__subtitle">
                Unit strategis Universitas Telkom untuk mempercepat riset, inovasi, bisnis, dan layanan masyarakat di bidang ilmu pengetahuan, teknologi, manajemen, dan seni.
            </p>
        </div>
    </div>

    <div class="about-hero__wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════
     DESKRIPSI TENTANG COE
     ═══════════════════════════════════════ -->
<section class="about-desc">
    <div class="container">
        <div class="about-desc__grid">
            <!-- Kolom Foto -->
            <div class="about-desc__img-wrap" data-aos="fade-right">
                <img src="img/fotoaboutus.jpg" alt="Tim CoE Smart City" class="about-desc__img"
                     onerror="this.style.display='none'">

                <div class="about-desc__floating-card">
                    <div class="about-desc__floating-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <div class="about-desc__floating-num">10+</div>
                        <div class="about-desc__floating-lbl">Tahun Pengalaman</div>
                    </div>
                </div>
            </div>

            <!-- Kolom Teks -->
            <div data-aos="fade-left">
                <div class="about-desc__label">Siapa Kami</div>
                <h2 class="about-desc__heading">
                    Pusat Unggulan Riset &amp;<br>Inovasi Kota Cerdas
                </h2>
                <p class="about-desc__text">
                    CoE Smart City di Universitas Telkom merupakan pusat unggulan yang berfokus pada pengembangan solusi kota cerdas melalui pendekatan interdisipliner. Kami mengintegrasikan teknologi informasi, kecerdasan buatan, Internet of Things (IoT), dan analisis data besar untuk menciptakan ekosistem perkotaan yang lebih efisien, berkelanjutan, dan inklusif.
                </p>

                <div class="about-desc__highlights">
                    <div class="about-desc__highlight-item">
                        <div class="about-desc__highlight-icon">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <span class="about-desc__highlight-text">Penelitian Terapan Berbasis Data</span>
                    </div>
                    <div class="about-desc__highlight-item">
                        <div class="about-desc__highlight-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <span class="about-desc__highlight-text">Kolaborasi Industri &amp; Pemerintah</span>
                    </div>
                    <div class="about-desc__highlight-item">
                        <div class="about-desc__highlight-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="about-desc__highlight-text">Pengembangan SDM Unggul</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     4 PILAR UTAMA
     ═══════════════════════════════════════ -->
<section class="about-pillars">
    <div class="container">
        <div data-aos="fade-up">
            <div class="about-desc__label" style="justify-content: center;">Pilar Utama</div>
            <h2 class="about-pillars__heading">Empat Pilar <span style="color: var(--primary-blue);">Unggulan Kami</span></h2>
            <p class="about-pillars__subtitle">Fondasi yang menopang seluruh aktivitas dan pencapaian CoE Smart City.</p>
        </div>

        <div class="about-pillars__grid">
            <!-- Riset -->
            <div class="about-pillar-card" data-aos="fade-up" data-aos-delay="0">
                <div class="about-pillar-card__icon about-pillar-card__icon--riset">
                    <i class="fas fa-flask"></i>
                </div>
                <h3 class="about-pillar-card__title">Riset</h3>
                <p class="about-pillar-card__text">
                    Mengakselerasi penelitian di berbagai bidang ilmu untuk menghasilkan penemuan dan inovasi baru yang bermanfaat bagi masyarakat dan industri.
                </p>
            </div>

            <!-- Inovasi -->
            <div class="about-pillar-card" data-aos="fade-up" data-aos-delay="100">
                <div class="about-pillar-card__icon about-pillar-card__icon--inovasi">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 class="about-pillar-card__title">Inovasi</h3>
                <p class="about-pillar-card__text">
                    Mendorong pengembangan teknologi dan solusi kreatif yang dapat diimplementasikan untuk meningkatkan kualitas hidup dan efektivitas kota.
                </p>
            </div>

            <!-- Bisnis -->
            <div class="about-pillar-card" data-aos="fade-up" data-aos-delay="200">
                <div class="about-pillar-card__icon about-pillar-card__icon--bisnis">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="about-pillar-card__title">Bisnis</h3>
                <p class="about-pillar-card__text">
                    Mentransformasi hasil riset menjadi solusi bisnis yang berkelanjutan melalui kemitraan strategis dengan industri dan pemerintah daerah.
                </p>
            </div>

            <!-- Layanan Masyarakat -->
            <div class="about-pillar-card" data-aos="fade-up" data-aos-delay="300">
                <div class="about-pillar-card__icon about-pillar-card__icon--layanan">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3 class="about-pillar-card__title">Layanan Masyarakat</h3>
                <p class="about-pillar-card__text">
                    Berkontribusi melalui program dan inisiatif yang memberdayakan komunitas, meningkatkan kesejahteraan sosial, dan menyelesaikan masalah kawasan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     VISI & MISI
     ═══════════════════════════════════════ -->
<section class="about-visimisi">
    <div class="container">
        <div data-aos="fade-up" style="text-align: center; margin-bottom: 3rem;">
            <div class="about-desc__label" style="justify-content: center;">Arah &amp; Tujuan</div>
            <h2 class="about-pillars__heading">Visi &amp; <span style="color: var(--primary-blue);">Misi Kami</span></h2>
        </div>

        <div class="about-visimisi__grid">
            <!-- Visi -->
            <div class="about-visimisi__card about-visimisi__card--visi" data-aos="fade-right">
                <div class="about-visimisi__icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="about-visimisi__title">Visi</h3>
                <p class="about-visimisi__text">
                    Menjadi pusat unggulan terdepan di Asia Tenggara dalam riset dan inovasi kota cerdas yang berkelanjutan, berdaya saing global, serta berdampak nyata bagi masyarakat dan ekosistem perkotaan Indonesia.
                </p>
            </div>

            <!-- Misi -->
            <div class="about-visimisi__card about-visimisi__card--misi" data-aos="fade-left">
                <div class="about-visimisi__icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="about-visimisi__title">Misi</h3>
                <ul class="about-visimisi__list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Melaksanakan penelitian berkualitas tinggi yang relevan dengan kebutuhan kota cerdas di Indonesia.
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Mengembangkan teknologi inovatif berbasis IoT, AI, dan Big Data untuk solusi perkotaan.
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Membangun kemitraan strategis dengan pemerintah, industri, dan komunitas masyarakat.
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Mencetak sumber daya manusia unggul yang siap berkontribusi di era digital.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>