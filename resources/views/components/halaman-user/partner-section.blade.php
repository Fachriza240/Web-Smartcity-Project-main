@props(['partners' => collect()])

<style>
    /* ══════════════════════════════════════════════
       [MITRA - SECTION (dipakai di Tentang Kami)]
       ══════════════════════════════════════════════ */
    .mitra-section {
        padding: 90px 0;
        background: var(--bg-light, #f4f8fc);
    }

    .mitra-section__label {
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
        justify-content: center;
    }

    .mitra-section__label::before,
    .mitra-section__label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .mitra-section__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .mitra-section__subtitle {
        font-size: 16px;
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 3rem;
        font-family: "Lato", sans-serif;
    }

    .mitra-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
        justify-items: center;
    }

    .mitra-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        width: 100%;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .mitra-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 40px rgba(76, 141, 201, 0.14);
        border-color: rgba(76, 141, 201, 0.15);
    }

    .mitra-card__logo-wrap {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .mitra-card__logo-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .mitra-card__logo-placeholder {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(76, 141, 201, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: rgba(76, 141, 201, 0.3);
    }

    .mitra-card__name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-black);
        font-family: "Spline Sans", sans-serif;
        margin-bottom: 6px;
    }

    .mitra-card__link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: var(--primary-blue);
        text-decoration: none;
        font-family: "Lato", sans-serif;
        transition: all 0.3s;
    }

    .mitra-card__link:hover {
        color: #3a7ab3;
        text-decoration: underline;
    }

    /* Empty */
    .mitra-empty {
        text-align: center;
        padding: 40px 20px;
    }

    .mitra-empty__icon {
        font-size: 3rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 0.75rem;
    }

    .mitra-empty__text {
        font-size: 1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 576px) {
        .mitra-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

@if($partners->isNotEmpty())
<section class="mitra-section" id="mitra">
    <div class="container">
        <div data-aos="fade-up">
            <div class="mitra-section__label">Kemitraan</div>
            <h2 class="mitra-section__heading">Mitra <span style="color: var(--primary-blue);">Strategis Kami</span></h2>
            <p class="mitra-section__subtitle">Kolaborasi dengan berbagai institusi dan industri untuk mewujudkan ekosistem kota cerdas.</p>
        </div>

        <div class="mitra-grid" data-aos="fade-up">
            @foreach($partners as $partner)
                <div class="mitra-card">
                    <div class="mitra-card__logo-wrap">
                        @if($partner->logo_path)
                            <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="{{ $partner->nama }}">
                        @else
                            <div class="mitra-card__logo-placeholder">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif
                    </div>
                    <div class="mitra-card__name">{{ $partner->nama }}</div>
                    @if($partner->website)
                        <a href="{{ $partner->website }}" target="_blank" class="mitra-card__link">
                            <i class="bi bi-link-45deg"></i> Kunjungi
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
