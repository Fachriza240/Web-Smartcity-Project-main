<x-layout.link :title="$publication->judul">
    <x-layout.navbar />
    <main id="konten-utama" class="sc-article">
        <div class="container">
            <nav class="sc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('publications.index') }}">Publikasi</a>
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ \Illuminate\Support\Str::limit($publication->judul, 60) }}</span>
            </nav>

            <article class="sc-article__body sc-article__body--wide">
                <div class="sc-pub">
                    <div class="sc-pub__main">
                        <header class="sc-article__header">
                            <div class="sc-article__meta">
                                <span><i class="bi bi-calendar3" aria-hidden="true"></i> {{ $publication->tahun }}</span>
                                <span class="sc-article__tag">{{ $publication->kategori }}</span>
                            </div>
                            <h1 class="sc-article__title">{{ $publication->judul }}</h1>
                            <p class="sc-pub__authors"><i class="bi bi-people" aria-hidden="true"></i> {{ $publication->penulis }}</p>
                        </header>

                        <h2 class="sc-pub__heading">Abstrak</h2>
                        <div class="sc-article__content">
                            {!! nl2br(e($publication->abstrak)) !!}
                        </div>
                    </div>

                    <aside class="sc-pub__aside">
                        @if ($publication->thumbnail_path)
                            <img src="{{ asset('storage/'.$publication->thumbnail_path) }}" alt="Sampul {{ $publication->judul }}" class="sc-pub__thumb">
                        @endif
                        <dl class="sc-pub__facts">
                            <div>
                                <dt>Penerbit</dt>
                                <dd>{{ $publication->penerbit ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt>DOI</dt>
                                <dd>{{ $publication->doi ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt>Kategori</dt>
                                <dd>{{ $publication->kategori }}</dd>
                            </div>
                            <div>
                                <dt>Tahun</dt>
                                <dd>{{ $publication->tahun }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('publications.download', $publication) }}" class="sc-btn sc-btn--primary sc-pub__download">
                            <i class="bi bi-download" aria-hidden="true"></i> Unduh PDF
                        </a>
                        <a href="{{ route('publications.index') }}" class="sc-btn sc-btn--outline sc-pub__download">
                            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
                        </a>
                    </aside>
                </div>
            </article>
        </div>
    </main>
    <x-layout.footer />
</x-layout.link>
