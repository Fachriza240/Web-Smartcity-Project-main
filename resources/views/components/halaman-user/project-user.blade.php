@props(['projects' => collect()])

<style>
    @keyframes projPulse {
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

    .proj-featured {
        padding: 90px 0 50px;
        background: #fff;
    }

    .proj-section-label {
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

    .proj-section-label::before,
    .proj-section-label::after {
        content: '•';
        font-size: 14px;
        color: var(--primary-blue);
    }

    .proj-featured__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .proj-featured__subtitle {
        font-size: 16px;
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 3rem;
        font-family: "Lato", sans-serif;
    }

    .proj-carousel-wrap {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(76, 141, 201, 0.15);
    }

    .proj-carousel-wrap .carousel-image-container {
        height: 450px;
        border-radius: 0;
    }

    .proj-carousel-wrap .carousel-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .proj-carousel-wrap .carousel-caption {
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.75));
        padding: 2rem 1.5rem;
        text-align: left;
    }

    .proj-carousel-wrap .carousel-caption h5 {
        color: #fff;
        font-family: "Spline Sans", sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .proj-carousel-wrap .carousel-caption p {
        color: rgba(255, 255, 255, 0.85);
        font-family: "Lato", sans-serif;
        font-size: 0.95rem;
    }

    .proj-carousel-placeholder {
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.25);
    }

    .proj-list {
        padding: 50px 0 90px;
        background: #fff;
    }

    .proj-list__heading {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-black);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
    }

    .proj-list__subtitle {
        font-size: 16px;
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 3rem;
        font-family: "Lato", sans-serif;
    }

    .proj-list__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    .proj-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .proj-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(76, 141, 201, 0.18);
    }

    .proj-card__img-wrap {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
    }

    .proj-card__img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .proj-card:hover .proj-card__img-wrap img {
        transform: scale(1.08);
    }

    .proj-card__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: rgba(76, 141, 201, 0.3);
    }

    .proj-card__badges {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .proj-card__badge {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(6px);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        font-family: "Spline Sans", sans-serif;
    }

    .proj-card__badge--kategori {
        color: var(--primary-blue);
    }

    .proj-card__badge--tahun {
        color: #666;
    }

    .proj-card__body {
        padding: 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .proj-card__title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.6rem;
        font-family: "Spline Sans", sans-serif;
    }

    .proj-card__desc {
        font-size: 14.5px;
        color: var(--text-gray);
        line-height: 1.7;
        font-family: "Lato", sans-serif;
        flex: 1;
    }

    .proj-card__partner {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .proj-card__partner i {
        color: var(--primary-blue);
    }

    .proj-card__doc-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 1rem;
        padding: 10px 20px;
        background: rgba(76, 141, 201, 0.08);
        color: var(--primary-blue);
        border: 1px solid rgba(76, 141, 201, 0.2);
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        font-family: "Spline Sans", sans-serif;
        transition: all 0.3s;
    }

    .proj-card__doc-btn:hover {
        background: var(--primary-blue);
        color: #fff;
        border-color: var(--primary-blue);
    }

    .proj-list__empty {
        text-align: center;
        padding: 60px 20px;
    }

    .proj-list__empty-icon {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 1rem;
    }

    .proj-list__empty-text {
        font-size: 1.1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    @media (max-width: 991px) {
        .proj-list__grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .proj-carousel-wrap .carousel-image-container,
        .proj-carousel-placeholder {
            height: 350px;
        }
    }

    @media (max-width: 576px) {
        .proj-list__grid {
            grid-template-columns: 1fr;
        }

        .proj-carousel-wrap .carousel-image-container,
        .proj-carousel-placeholder {
            height: 250px;
        }

        .proj-carousel-wrap .carousel-caption {
            padding: 1rem;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .proj-carousel-wrap .carousel-caption h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }

        .proj-carousel-wrap .carousel-caption p {
            display: none;
        }
    }
</style>

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Proyek Kami</span>
        <h1 class="sc-page-hero__title">Proyek Inovatif<br><span class="text-blue">Smart City</span></h1>
        <p class="sc-page-hero__lead">
            Dokumentasi proyek-proyek inovatif yang kami kembangkan dalam rangka mewujudkan ekosistem kota cerdas di Indonesia.
        </p>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

@if($projects->isNotEmpty())
    @php
        $featured = $projects->first(fn($p) => !empty($p->gallery_paths)) ?? $projects->first();
    @endphp

    @if($featured)
    <section class="proj-featured">
        <div class="container">
            <div data-aos="fade-up">
                <div class="proj-section-label sc-label-center">Sorotan</div>
                <h2 class="proj-featured__heading">Proyek <span class="text-blue">Unggulan</span></h2>
                <p class="proj-featured__subtitle">Proyek terbaru yang menjadi fokus utama pengembangan kami.</p>
            </div>

            <div class="proj-carousel-wrap" data-aos="fade-up">
                <div id="projCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php $isFirst = true; @endphp
                        @if(!empty($featured->gallery_paths))
                            @foreach($featured->gallery_paths as $img)
                                <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                    <div class="carousel-image-container">
                                        <img src="{{ asset('storage/' . $img) }}"
                                             alt="{{ $featured->judul }}">
                                        <div class="carousel-caption">
                                            <h5>{{ $featured->judul }}</h5>
                                            <p>{{ \Illuminate\Support\Str::limit($featured->deskripsi, 120) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @php $isFirst = false; @endphp
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="carousel-image-container">
                                    @if($featured->thumbnail_path)
                                        <img src="{{ asset('storage/' . $featured->thumbnail_path) }}"
                                             alt="{{ $featured->judul }}">
                                    @else
                                        <div class="proj-carousel-placeholder">
                                            <i class="bi bi-kanban"></i>
                                        </div>
                                    @endif
                                    <div class="carousel-caption">
                                        <h5>{{ $featured->judul }}</h5>
                                        <p>{{ \Illuminate\Support\Str::limit($featured->deskripsi, 120) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!empty($featured->gallery_paths) && count($featured->gallery_paths) > 1)
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#projCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#projCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Selanjutnya</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="proj-list">
        <div class="container">
            <div data-aos="fade-up">
                <div class="proj-section-label sc-label-center">Daftar Proyek</div>
                <h2 class="proj-list__heading">Semua <span class="text-blue">Proyek Kami</span></h2>
                <p class="proj-list__subtitle">Jelajahi seluruh proyek yang sedang dan telah kami kerjakan.</p>
            </div>

            <div class="proj-list__grid">
                @foreach($projects as $project)
                    <div class="proj-card" id="proyek-{{ $project->id }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="proj-card__img-wrap">
                            @if($project->thumbnail_path)
                                <img src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                     alt="{{ $project->judul }}">
                            @else
                                <div class="proj-card__img-placeholder">
                                    <i class="bi bi-kanban"></i>
                                </div>
                            @endif

                            <div class="proj-card__badges">
                                @if($project->kategori)
                                    <span class="proj-card__badge proj-card__badge--kategori">{{ $project->kategori }}</span>
                                @endif
                                @if($project->tahun)
                                    <span class="proj-card__badge proj-card__badge--tahun">{{ $project->tahun }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="proj-card__body">
                            <h3 class="proj-card__title">{{ $project->judul }}</h3>
                            <p class="proj-card__desc">
                                {{ \Illuminate\Support\Str::limit($project->deskripsi, 140) }}
                            </p>

                            @if($project->partner)
                                <div class="proj-card__partner">
                                    <i class="bi bi-building"></i>
                                    {{ $project->partner }}
                                </div>
                            @endif

                            @if($project->dokumen_path)
                                <a href="{{ asset('storage/' . $project->dokumen_path) }}"
                                   target="_blank"
                                   class="proj-card__doc-btn">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Lihat Dokumen
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@else
    <section class="proj-list proj-list--empty">
        <div class="container">
            <div class="proj-list__empty" data-aos="fade-up">
                <div class="proj-list__empty-icon">
                    <i class="bi bi-kanban"></i>
                </div>
                <p class="proj-list__empty-text">Belum ada proyek yang tersedia saat ini.</p>
            </div>
        </div>
    </section>
@endif
