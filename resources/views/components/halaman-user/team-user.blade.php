@props(['staff' => collect(), 'interns' => collect()])

<style>
    /* ══════════════════════════════════════════════
       [TIM - PAGE HERO]
       ══════════════════════════════════════════════ */
    .team-hero {
        position: relative;
        padding: 160px 0 100px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        overflow: hidden;
    }

    .team-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 80% 30%, black 5%, transparent 75%);
        pointer-events: none;
    }

    .team-hero::before {
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

    .team-hero__content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .team-hero__badge {
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

    .team-hero__badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: teamPulse 2s infinite;
    }

    @keyframes teamPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.7); }
    }

    .team-hero__title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .team-hero__title .text-blue {
        color: var(--primary-blue);
    }

    .team-hero__subtitle {
        font-size: 1.15rem;
        color: var(--text-gray);
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    .team-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .team-hero__wave svg {
        display: block;
        width: 100%;
    }

    /* ══════════════════════════════════════════════
       [TIM - TAB FILTER]
       ══════════════════════════════════════════════ */
    .team-content {
        padding: 70px 0 90px;
        background: #fff;
    }

    .team-tabs {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 3rem;
        flex-wrap: wrap;
    }

    .team-tab {
        padding: 12px 32px;
        border-radius: 30px;
        border: 2px solid rgba(76, 141, 201, 0.2);
        background: transparent;
        font-family: "Spline Sans", sans-serif;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        color: var(--text-black);
        letter-spacing: 0.5px;
    }

    .team-tab:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .team-tab.active {
        background: var(--primary-blue);
        color: #fff;
        border-color: var(--primary-blue);
        box-shadow: 0 6px 20px rgba(76, 141, 201, 0.35);
    }

    /* ══════════════════════════════════════════════
       [TIM - KARTU ANGGOTA]
       ══════════════════════════════════════════════ */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .team-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-align: center;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(76, 141, 201, 0.18);
    }

    .team-card__photo-wrap {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
    }

    .team-card__photo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .team-card:hover .team-card__photo-wrap img {
        transform: scale(1.06);
    }

    .team-card__photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: rgba(76, 141, 201, 0.25);
    }

    .team-card__body {
        padding: 1.5rem;
    }

    .team-card__name {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 4px;
        font-family: "Spline Sans", sans-serif;
    }

    .team-card__position {
        font-size: 13px;
        color: var(--primary-blue);
        font-weight: 600;
        font-family: "Lato", sans-serif;
        margin-bottom: 10px;
    }

    .team-card__contact {
        font-size: 12.5px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        margin-bottom: 4px;
        word-break: break-all;
    }

    .team-card__contact a {
        color: var(--text-gray);
        text-decoration: none;
        transition: color 0.3s;
    }

    .team-card__contact a:hover {
        color: var(--primary-blue);
    }

    .team-card__socials {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .team-card__social-link {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .team-card__social-link--linkedin {
        background: rgba(10, 102, 194, 0.1);
        color: #0a66c2;
    }

    .team-card__social-link--linkedin:hover {
        background: #0a66c2;
        color: #fff;
    }

    .team-card__social-link--instagram {
        background: rgba(225, 48, 108, 0.1);
        color: #e1306c;
    }

    .team-card__social-link--instagram:hover {
        background: #e1306c;
        color: #fff;
    }

    .team-card__social-link--github {
        background: rgba(36, 41, 47, 0.1);
        color: #24292f;
    }

    .team-card__social-link--github:hover {
        background: #24292f;
        color: #fff;
    }

    /* Empty */
    .team-empty {
        text-align: center;
        padding: 50px 20px;
    }

    .team-empty__icon {
        font-size: 3.5rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 1rem;
    }

    .team-empty__text {
        font-size: 1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .team-hero {
            padding: 140px 0 80px;
        }

        .team-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .team-hero {
            padding: 120px 0 60px;
        }

        .team-grid {
            grid-template-columns: 1fr;
            max-width: 360px;
            margin: 0 auto;
        }
    }
</style>

<!-- ═══════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════ -->
<section class="team-hero">
    <div class="team-hero__dots"></div>

    <div class="container">
        <div class="team-hero__content" data-aos="fade-up">
            <div class="team-hero__badge">
                <span class="team-hero__badge-dot"></span>
                Tim Kami
            </div>
            <h1 class="team-hero__title">
                Tim Profesional<br>
                <span class="text-blue">Smart City</span>
            </h1>
            <p class="team-hero__subtitle">
                Kami adalah tim operasional yang profesional dan berkomitmen, berfokus pada pengelolaan dan implementasi teknologi untuk mewujudkan visi kota cerdas yang efisien dan inovatif.
            </p>
        </div>
    </div>

    <div class="team-hero__wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════
     DAFTAR TIM
     ═══════════════════════════════════════ -->
<section class="team-content">
    <div class="container">
        <!-- Tab Filter -->
        <div class="team-tabs" data-aos="fade-up">
            <button class="team-tab active" data-target="team-staff-section">Staf</button>
            <button class="team-tab" data-target="team-interns-section">Magang</button>
        </div>

        <!-- Staf Section -->
        <div id="team-staff-section" class="team-section">
            @if($staff->isNotEmpty())
                <div class="team-grid">
                    @foreach($staff as $member)
                        <div class="team-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="team-card__photo-wrap">
                                @if($member->foto_path)
                                    <img src="{{ asset('storage/'.$member->foto_path) }}" alt="{{ $member->nama }}">
                                @else
                                    <div class="team-card__photo-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="team-card__body">
                                <div class="team-card__name">{{ $member->nama }}</div>
                                <div class="team-card__position">{{ $member->jabatan ?? '-' }}</div>

                                @if($member->email)
                                    <div class="team-card__contact">
                                        <a href="mailto:{{ $member->email }}">
                                            <i class="bi bi-envelope me-1"></i>{{ $member->email }}
                                        </a>
                                    </div>
                                @endif

                                @if($member->telepon)
                                    <div class="team-card__contact">
                                        <i class="bi bi-telephone me-1"></i>{{ $member->telepon }}
                                    </div>
                                @endif

                                @if($member->linkedin || $member->instagram || $member->github)
                                    <div class="team-card__socials">
                                        @if($member->linkedin)
                                            <a href="{{ $member->linkedin }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--linkedin">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        @endif
                                        @if($member->instagram)
                                            <a href="https://instagram.com/{{ ltrim($member->instagram, '@') }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--instagram">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        @endif
                                        @if($member->github)
                                            <a href="https://github.com/{{ $member->github }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--github">
                                                <i class="bi bi-github"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="team-empty">
                    <div class="team-empty__icon"><i class="bi bi-people"></i></div>
                    <p class="team-empty__text">Belum ada data staf.</p>
                </div>
            @endif
        </div>

        <!-- Magang Section -->
        <div id="team-interns-section" class="team-section" style="display: none;">
            @if($interns->isNotEmpty())
                <div class="team-grid">
                    @foreach($interns as $member)
                        <div class="team-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="team-card__photo-wrap">
                                @if($member->foto_path)
                                    <img src="{{ asset('storage/'.$member->foto_path) }}" alt="{{ $member->nama }}">
                                @else
                                    <div class="team-card__photo-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="team-card__body">
                                <div class="team-card__name">{{ $member->nama }}</div>
                                <div class="team-card__position">{{ $member->jabatan ?? '-' }}</div>

                                @if($member->email)
                                    <div class="team-card__contact">
                                        <a href="mailto:{{ $member->email }}">
                                            <i class="bi bi-envelope me-1"></i>{{ $member->email }}
                                        </a>
                                    </div>
                                @endif

                                @if($member->telepon)
                                    <div class="team-card__contact">
                                        <i class="bi bi-telephone me-1"></i>{{ $member->telepon }}
                                    </div>
                                @endif

                                @if($member->linkedin || $member->instagram || $member->github)
                                    <div class="team-card__socials">
                                        @if($member->linkedin)
                                            <a href="{{ $member->linkedin }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--linkedin">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        @endif
                                        @if($member->instagram)
                                            <a href="https://instagram.com/{{ ltrim($member->instagram, '@') }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--instagram">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        @endif
                                        @if($member->github)
                                            <a href="https://github.com/{{ $member->github }}" target="_blank"
                                               class="team-card__social-link team-card__social-link--github">
                                                <i class="bi bi-github"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="team-empty">
                    <div class="team-empty__icon"><i class="bi bi-people"></i></div>
                    <p class="team-empty__text">Belum ada data magang.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('.team-tab');
        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.team-section').forEach(s => s.style.display = 'none');
                document.getElementById(this.getAttribute('data-target')).style.display = 'block';
                if (typeof AOS !== 'undefined') setTimeout(() => AOS.refresh(), 10);
            });
        });
    });
</script>
