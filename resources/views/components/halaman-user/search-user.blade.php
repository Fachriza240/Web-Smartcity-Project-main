@props(['keyword' => '', 'results' => collect()])

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Pencarian</span>
        <h1 class="sc-page-hero__title">Cari konten <span class="text-blue">CoE Smart City</span></h1>
        <p class="sc-page-hero__lead">Temukan berita, publikasi, proyek, dan program kami dalam satu pencarian.</p>
        <form action="{{ route('search') }}" method="GET" class="sc-search-page__form" role="search">
            <input type="search" name="q" value="{{ $keyword }}" placeholder="Contoh: transportasi, IoT, energi" aria-label="Kata kunci pencarian" minlength="2" maxlength="100" required>
            <button type="submit" class="sc-btn sc-btn--primary"><i class="bi bi-search" aria-hidden="true"></i> Cari</button>
        </form>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<section class="sc-section">
    <div class="container sc-search-page">
        @if (mb_strlen($keyword) < 2)
            <div class="sc-empty">
                <i class="bi bi-search" aria-hidden="true"></i>
                <h2>Mulai dengan kata kunci</h2>
                <p>Ketik minimal dua karakter pada kolom pencarian di atas untuk melihat hasil.</p>
            </div>
        @elseif ($results->isEmpty())
            <div class="sc-empty">
                <i class="bi bi-emoji-neutral" aria-hidden="true"></i>
                <h2>Tidak ada hasil untuk "{{ $keyword }}"</h2>
                <p>Coba kata kunci lain yang lebih umum, atau periksa kembali ejaannya.</p>
            </div>
        @else
            <p class="sc-search-page__meta">
                Menampilkan <strong>{{ $results->count() }}</strong> hasil untuk <strong>"{{ $keyword }}"</strong>
            </p>
            @foreach ($results as $result)
                <a href="{{ $result['url'] }}" class="sc-result">
                    <span class="sc-result__icon"><i class="bi {{ $result['icon'] }}" aria-hidden="true"></i></span>
                    <span class="sc-result__body">
                        <span class="sc-result__type">{{ $result['type'] }}</span>
                        <h2 class="sc-result__title">{{ $result['title'] }}</h2>
                        <p class="sc-result__excerpt">{{ $result['excerpt'] }}</p>
                    </span>
                </a>
            @endforeach
        @endif
    </div>
</section>
