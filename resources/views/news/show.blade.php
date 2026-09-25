@php
    $isDosenArea = auth()->check() && auth()->user()->role === 'dosen' && auth()->user()->registration_status === \App\Models\User::STATUS_APPROVED;
    $backUrl = url($isDosenArea ? '/news-dosen' : '/news-user');
@endphp

<x-layout.link :title="$news->judul">
    <x-layout.navbar />
    <main id="konten-utama" class="sc-article">
        <div class="container">
            <nav class="sc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ $backUrl }}">Berita</a>
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ \Illuminate\Support\Str::limit($news->judul, 60) }}</span>
            </nav>

            <article class="sc-article__body">
                <header class="sc-article__header">
                    <div class="sc-article__meta">
                        @if ($news->published_at)
                            <span><i class="bi bi-calendar3" aria-hidden="true"></i> {{ $news->published_at->translatedFormat('l, d F Y') }}</span>
                        @endif
                        @if ($news->kategori)
                            <span class="sc-article__tag">{{ $news->kategori }}</span>
                        @endif
                    </div>
                    <h1 class="sc-article__title">{{ $news->judul }}</h1>
                </header>

                @if ($news->thumbnail_path)
                    <figure class="sc-article__cover">
                        <img src="{{ asset('storage/'.$news->thumbnail_path) }}" alt="{{ $news->judul }}">
                    </figure>
                @endif

                <div class="sc-article__content">
                    {!! nl2br(e($news->konten)) !!}
                </div>

                <footer class="sc-article__footer">
                    <a href="{{ $backUrl }}" class="sc-btn sc-btn--outline">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Berita
                    </a>
                </footer>
            </article>

            @if ($related->isNotEmpty())
                <section class="sc-related" aria-labelledby="berita-lain">
                    <h2 id="berita-lain" class="sc-related__title">Berita lainnya</h2>
                    <div class="sc-related__grid">
                        @foreach ($related as $item)
                            <a href="{{ route('news.show', $item) }}" class="sc-related__card">
                                <div class="sc-related__img">
                                    @if ($item->thumbnail_path)
                                        <img src="{{ asset('storage/'.$item->thumbnail_path) }}" alt="" loading="lazy">
                                    @else
                                        <i class="bi bi-newspaper" aria-hidden="true"></i>
                                    @endif
                                </div>
                                <div class="sc-related__body">
                                    <span class="sc-related__date">{{ $item->published_at?->translatedFormat('d F Y') }}</span>
                                    <h3>{{ $item->judul }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
    <x-layout.footer />
</x-layout.link>
