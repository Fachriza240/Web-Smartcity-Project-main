<x-layout.link>
    <x-layout.navbar></x-layout.navbar>

    <section class="news3">
        <div class="container">
            <div class="news-header">
                <div class="news2-date mb-3">{{ $publication->tahun }} | <span class="news2-category">{{ $publication->kategori }}</span></div>
                <h2>{{ $publication->judul }}</h2>
            </div>

            @if($publication->thumbnail_path)
                <div class="news-image">
                    <img src="{{ asset('storage/' . $publication->thumbnail_path) }}" alt="{{ $publication->judul }}">
                </div>
            @endif

            <div class="news-text">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <strong>Penulis</strong>
                        <p class="mb-0">{{ $publication->penulis }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Penerbit</strong>
                        <p class="mb-0">{{ $publication->penerbit ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>DOI</strong>
                        <p class="mb-0">{{ $publication->doi ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status</strong>
                        <p class="mb-0">{{ $publication->status }}</p>
                    </div>
                </div>

                <h4>Abstrak</h4>
                <p>{{ $publication->abstrak }}</p>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('publications.download', $publication) }}" class="btn btn-primary">
                        <i class="bi bi-download"></i>
                        Download PDF
                    </a>
                    <a href="{{ route('publications.index') }}" class="btn btn-outline-primary">Kembali</a>
                </div>
            </div>
        </div>
    </section>

    <x-layout.footer></x-layout.footer>
</x-layout.link>
