<x-layout.link>
    <x-layout.navbar></x-layout.navbar>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if($news->thumbnail_path)
                        <img src="{{ asset('storage/'.$news->thumbnail_path) }}"
                             class="img-fluid rounded mb-4 w-100"
                             style="max-height:420px;object-fit:cover;"
                             alt="{{ $news->judul }}">
                    @endif

                    <div class="text-muted small mb-2">
                        {{ $news->published_at?->format('d F Y') }}
                        @if($news->kategori)
                            &nbsp;|&nbsp;
                            <span class="text-primary fw-semibold">{{ $news->kategori }}</span>
                        @endif
                    </div>

                    <h1 class="mb-4 fw-bold" style="line-height:1.3;">{{ $news->judul }}</h1>

                    <div class="news-body" style="font-size:1.05rem;line-height:1.8;">
                        {!! nl2br(e($news->konten)) !!}
                    </div>

                    <hr class="my-4">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-layout.footer></x-layout.footer>
</x-layout.link>
