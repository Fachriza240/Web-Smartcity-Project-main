@props(['news', 'categories', 'years'])

<style>
    @keyframes newsPulse {
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

    .news-search {
        padding: 60px 0 30px;
        background: #fff;
    }

    .news-search__bar {
        max-width: 850px;
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

    .news-search__bar:focus-within {
        border-color: var(--primary-blue);
        box-shadow: 0 4px 20px rgba(76, 141, 201, 0.12);
    }

    .news-search__input {
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

    .news-search__input::placeholder {
        color: #aaa;
    }

    .news-search__select {
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

    .news-search__select:focus {
        border-color: var(--primary-blue);
    }

    .news-search__btn {
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

    .news-search__btn:hover {
        background: #3a7ab3;
        transform: translateY(-1px);
    }

    .news-search__info {
        text-align: center;
        margin-top: 1rem;
        font-size: 13px;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    .news-list {
        padding: 30px 0 90px;
        background: #fff;
    }

    .news-article {
        display: flex;
        gap: 28px;
        padding: 28px;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .news-article:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(76, 141, 201, 0.12);
        border-color: rgba(76, 141, 201, 0.15);
    }

    .news-article__img-wrap {
        flex-shrink: 0;
        width: 220px;
        height: 160px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #e4f0fb, #d0e3f5);
    }

    .news-article__img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .news-article:hover .news-article__img-wrap img {
        transform: scale(1.06);
    }

    .news-article__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(76, 141, 201, 0.3);
    }

    .news-article__body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .news-article__meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .news-article__date {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-gray);
        font-family: "Spline Sans", sans-serif;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .news-article__category {
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

    .news-article__title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 0.5rem;
        font-family: "Spline Sans", sans-serif;
        line-height: 1.35;
    }

    .news-article__title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }

    .news-article__title a:hover {
        color: var(--primary-blue);
    }

    .news-article__excerpt {
        font-size: 14.5px;
        color: var(--text-gray);
        line-height: 1.7;
        font-family: "Lato", sans-serif;
        margin: 0;
    }

    .news-pagination {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    .news-pagination .pagination {
        gap: 4px;
    }

    .news-pagination .page-link {
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 14px;
        font-family: "Spline Sans", sans-serif;
        border: 1px solid rgba(76, 141, 201, 0.15);
        color: var(--text-black);
        transition: all 0.3s;
    }

    .news-pagination .page-link:hover {
        background: rgba(76, 141, 201, 0.1);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .news-pagination .page-item.active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: #fff;
    }

    .news-list__empty {
        text-align: center;
        padding: 60px 20px;
    }

    .news-list__empty-icon {
        font-size: 4rem;
        color: rgba(76, 141, 201, 0.2);
        margin-bottom: 1rem;
    }

    .news-list__empty-text {
        font-size: 1.1rem;
        color: var(--text-gray);
        font-family: "Lato", sans-serif;
    }

    @media (max-width: 768px) {
        .news-article {
            flex-direction: column;
            gap: 16px;
        }

        .news-article__img-wrap {
            width: 100%;
            height: 200px;
        }

        .news-search__bar {
            flex-direction: column;
        }

        .news-search__input,
        .news-search__select,
        .news-search__btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .news-article {
            padding: 18px;
        }

        .news-article__img-wrap {
            height: 180px;
        }
    }
</style>

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Berita Terkini</span>
        <h1 class="sc-page-hero__title">Berita &amp; Informasi<br><span class="text-blue">Smart City</span></h1>
        <p class="sc-page-hero__lead">
            Temukan berita terbaru seputar riset, inovasi, dan kegiatan CoE Smart City Universitas Telkom.
        </p>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<section class="news-search">
    <div class="container">
        <form action="{{ route('news.index') }}" method="GET" data-aos="fade-up">
            <div class="news-search__bar">
                <i class="bi bi-search sc-filter-icon" aria-hidden="true"></i>
                <input type="text" name="search" class="news-search__input"
                       value="{{ request('search') }}" placeholder="Cari berita...">
                <select name="kategori" class="news-search__select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('kategori') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="news-search__select">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected((string)request('tahun') === (string)$year)>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="news-search__btn">Cari</button>
            </div>
        </form>
        <div class="news-search__info">
            Menampilkan {{ $news->firstItem() ?? 0 }} sampai {{ $news->lastItem() ?? 0 }} dari {{ $news->total() }} berita
        </div>
    </div>
</section>

<section class="news-list">
    <div class="container">
        @forelse($news as $item)
            <article class="news-article" data-aos="fade-up">
                <div class="news-article__img-wrap">
                    @if($item->thumbnail_path)
                        <img src="{{ asset('storage/'.$item->thumbnail_path) }}"
                             alt="{{ $item->judul }}">
                    @else
                        <div class="news-article__img-placeholder">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    @endif
                </div>

                <div class="news-article__body">
                    <div class="news-article__meta">
                        <span class="news-article__date">
                            {{ $item->published_at?->translatedFormat('l, d F Y') ?? '-' }}
                        </span>
                        @if($item->kategori)
                            <span class="news-article__category">{{ $item->kategori }}</span>
                        @endif
                    </div>
                    <h3 class="news-article__title">
                        <a href="{{ route('news.show', $item) }}">{{ $item->judul }}</a>
                    </h3>
                    <p class="news-article__excerpt">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 250) }}
                    </p>
                </div>
            </article>
        @empty
            <div class="news-list__empty" data-aos="fade-up">
                <div class="news-list__empty-icon">
                    <i class="bi bi-newspaper"></i>
                </div>
                <p class="news-list__empty-text">Belum ada berita yang dipublikasikan saat ini.</p>
            </div>
        @endforelse

        <div class="news-pagination">
            {{ $news->links() }}
        </div>
    </div>
</section>
