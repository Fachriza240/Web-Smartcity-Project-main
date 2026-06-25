@props(['publications', 'categories', 'years'])

<style>
    /* ══════════════════════════════════════════════
       [PUBLIKASI - PAGE HERO]
       ══════════════════════════════════════════════ */
    .pub-hero {
        position: relative;
        padding: 160px 0 100px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        overflow: hidden;
    }

    .pub-hero__dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(76, 141, 201, 0.12) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 40% 50% at 80% 30%, black 5%, transparent 75%);
        pointer-events: none;
    }

    .pub-hero::before {
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

    .pub-hero__content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .pub-hero__badge {
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

    .pub-hero__badge-dot {
        width: 7px;
        height: 7px;
        background: var(--primary-blue);
        border-radius: 50%;
        animation: pubPulse 2s infinite;
    }

    @keyframes pubPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.7); }
    }

    .pub-hero__title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 1rem;
        line-height: 1.15;
        font-family: "Spline Sans", sans-serif;
    }

    .pub-hero__title .text-blue {
        color: var(--primary-blue);
    }

    .pub-hero__subtitle {
        font-size: 1.15rem;
        color: var(--text-gray);
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.8;
        font-family: "Lato", sans-serif;
    }

    .pub-hero__wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        line-height: 0;
        z-index: 3;
    }

    .pub-hero__wave svg {
        display: block;
        width: 100%;
    }

    /* ══════════════════════════════════════════════
       [PUBLIKASI - PENCARIAN]
       ══════════════════════════════════════════════ */
    .pub-search {
        padding: 60px 0 30px;
        background: #fff;
    }

    .pub-search__bar {
        max-width: 900px;
        margin: 0 auto;
        background: var(--bg-light, #f4f8fc);
        border-radius: 16px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        border: 1px solid rgba(76, 141, 201, 0.1);
        transition: all 0.3s;
    }

    .pub-search__bar:focus-within {
        border-color: var(--primary-blue);
        box-shadow: 0 4px 20px rgba(76, 141, 201, 0.12);
    }

    .pub-search__input {
        flex: 1;
        min-width: 180px;
        border: none;
        background: transparent;
        padding: 10px 14px;
        font-size: 15px;
        font-family: "Lato", sans-serif;
        color: var(--text-black);
        outline: none;
    }

    .pub-search__input::placeholder {
        color: #aaa;
    }

    .pub-search__select {
        border: 1px solid rgba(76, 141, 201, 0.15);
        background: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-family: "Lato", sans-serif;
        color: var(--text-gray);
        cursor: pointer;
        outline: none;
        min-width: 160px;
        transition: border-color 0.3s;
    }

    .pub-search__select:focus {
        border-color: var(--primary-blue);
    }

    .pub-search__btn {
        background: var(--primary-blue);
        color: #fff;
        border: none;
        padding: 10px 28px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        font-family: "Spline Sans", sans-serif;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .pub-search__btn:hover {
        background: #3a7ab3;
        transform: translateY(-1px);
    }

    .pub-search__info {
        text-align: center;
        margin-top: 1rem;
        font-size: 13px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       [PUBLIKASI - DAFTAR KARTU]
       ══════════════════════════════════════════════ */
    .pub-list {
        padding: 30px 0 90px;
        background: #fff;
    }

    .pub-article {
        display: flex;
        gap: 28px;
        padding: 28px;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .pub-article:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(76, 141, 201, 0.12);
        border-color: rgba(76, 141, 201, 0.15);
    }

    .pub-article__img-wrap {
        flex-shrink: 0;
        width: 180px;
        height: 220px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
    }

    .pub-article__img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .pub-article:hover .pub-article__img-wrap img {
        transform: scale(1.06);
    }

    .pub-article__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 8px;
        font-size: 2.5rem;
        color: rgba(76, 141, 201, 0.3);
    }

    .pub-article__body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .pub-article__meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .pub-article__year {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-gray);
        font-family: "Spline Sans", sans-serif;
        letter-spacing: 0.5px;
    }

    .pub-article__category {
        background: rgba(76, 141, 201, 0.1);
        color: var(--primary-blue);
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        font-family: "Spline Sans", sans-serif;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .pub-article__title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
        line-height: 1.4;
    }

    .pub-article__title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }

    .pub-article__title a:hover {
        color: var(--primary-blue);
    }

    .pub-article__author {
        font-size: 14px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
        margin-bottom: 0.5rem;
    }

    .pub-article__author strong {
        color: var(--text-black);
        font-weight: 600;
    }

    .pub-article__abstract {
        font-size: 14px;
        color: var(--text-gray);
        line-height: 1.7;
        font-family: "Lato", sans-serif;
        margin-bottom: 1rem;
    }

    .pub-article__actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pub-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        font-family: "Spline Sans", sans-serif;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
    }

    .pub-btn--detail {
        background: rgba(76, 141, 201, 0.08);
        color: var(--primary-blue);
        border: 1px solid rgba(76, 141, 201, 0.2);
    }

    .pub-btn--detail:hover {
        background: var(--primary-blue);
        color: #fff;
        border-color: var(--primary-blue);
    }

    .pub-btn--download {
        background: var(--primary-blue);
        color: #fff;
        border: 1px solid var(--primary-blue);
    }

    .pub-btn--download:hover {
        background: #3a7ab3;
        color: #fff;
        transform: translateY(-1px);
    }

    /* Pagination */
    .pub-pagination {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    .pub-pagination .pagination {
        gap: 4px;
    }

    .pub-pagination .page-link {
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 14px;
        font-family: "Spline Sans", sans-serif;
        border: 1px solid rgba(76, 141, 201, 0.15);
        color: var(--text-black);
        transition: all 0.3s;
    }

    .pub-pagination .page-link:hover {
        background: rgba(76, 141, 201, 0.1);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .pub-pagination .page-item.active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: #fff;
    }

    /* Empty */
    .pub-list__empty {
        text-align: center;
        padding: 60px 20px;
    }

    .pub-list__empty-icon {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 1rem;
    }

    .pub-list__empty-text {
        font-size: 1.1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .pub-hero {
            padding: 140px 0 80px;
        }
    }

    @media (max-width: 768px) {
        .pub-article {
            flex-direction: column;
            gap: 16px;
        }

        .pub-article__img-wrap {
            width: 100%;
            height: 200px;
        }

        .pub-search__bar {
            flex-direction: column;
        }

        .pub-search__input,
        .pub-search__select,
        .pub-search__btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .pub-hero {
            padding: 120px 0 60px;
        }

        .pub-article {
            padding: 18px;
        }

        .pub-article__img-wrap {
            height: 180px;
        }
    }
</style>

<!-- ═══════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════ -->
<section class="pub-hero">
    <div class="pub-hero__dots"></div>

    <div class="container">
        <div class="pub-hero__content" data-aos="fade-up">
            <div class="pub-hero__badge">
                <span class="pub-hero__badge-dot"></span>
                Publikasi Ilmiah
            </div>
            <h1 class="pub-hero__title">
                Publikasi &amp; Karya<br>
                <span class="text-blue">Akademik</span>
            </h1>
            <p class="pub-hero__subtitle">
                Kumpulan publikasi ilmiah, laporan penelitian, dan karya akademik yang dihasilkan oleh tim peneliti CoE Smart City Universitas Telkom.
            </p>
        </div>
    </div>

    <div class="pub-hero__wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════
     PENCARIAN & FILTER
     ═══════════════════════════════════════ -->
<section class="pub-search">
    <div class="container">
        <form action="{{ route('publications.index') }}" method="GET" data-aos="fade-up">
            <div class="pub-search__bar">
                <i class="bi bi-search" style="color: var(--primary-blue); font-size: 18px; margin-left: 8px;"></i>
                <input type="text" name="search" class="pub-search__input"
                       value="{{ request('search') }}" placeholder="Cari judul, penulis, abstrak, atau DOI...">
                <select name="kategori" class="pub-search__select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="pub-search__select">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected((string)request('tahun') === (string)$year)>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="pub-search__btn">Cari</button>
            </div>
        </form>
        <div class="pub-search__info">
            Menampilkan {{ $publications->firstItem() ?? 0 }} – {{ $publications->lastItem() ?? 0 }} dari {{ $publications->total() }} publikasi
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     DAFTAR PUBLIKASI
     ═══════════════════════════════════════ -->
<section class="pub-list">
    <div class="container">
        @forelse($publications as $publication)
            <article class="pub-article" data-aos="fade-up">
                <div class="pub-article__img-wrap">
                    @if($publication->thumbnail_path)
                        <img src="{{ asset('storage/' . $publication->thumbnail_path) }}"
                             alt="{{ $publication->judul }}">
                    @else
                        <div class="pub-article__img-placeholder">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    @endif
                </div>

                <div class="pub-article__body">
                    <div class="pub-article__meta">
                        <span class="pub-article__year">{{ $publication->tahun }}</span>
                        <span class="pub-article__category">{{ $publication->kategori }}</span>
                    </div>

                    <h3 class="pub-article__title">
                        <a href="{{ route('publications.show', $publication) }}">{{ $publication->judul }}</a>
                    </h3>

                    <p class="pub-article__author">
                        <strong>Penulis:</strong> {{ $publication->penulis }}
                    </p>

                    <p class="pub-article__abstract">
                        {{ \Illuminate\Support\Str::limit($publication->abstrak, 250) }}
                    </p>

                    <div class="pub-article__actions">
                        <a href="{{ route('publications.show', $publication) }}" class="pub-btn pub-btn--detail">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('publications.download', $publication) }}" class="pub-btn pub-btn--download">
                            <i class="bi bi-download"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="pub-list__empty" data-aos="fade-up">
                <div class="pub-list__empty-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
                <p class="pub-list__empty-text">Belum ada publikasi yang tersedia saat ini.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="pub-pagination">
            {{ $publications->links() }}
        </div>
    </div>
</section>
