@props(['publications', 'categories', 'years'])

<section class="news2-section" data-aos="fade-up">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Scientific <span class="highlight">Publication</span></h2>
                <p class="section-subtitle">Publikasi ilmiah, laporan penelitian, dan karya akademik COE Smart City.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-10 mx-auto">
                <form action="{{ route('publications.index') }}" method="GET" class="news2-search-container">
                    <div class="news2-search-inner">
                        <input type="text" name="search" class="news2-search-input" value="{{ request('search') }}" placeholder="Cari judul, penulis, abstrak, DOI">
                        <div class="news2-dropdown-container">
                            <select name="kategori" class="news2-dropdown">
                                <option value="">Semua kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="news2-dropdown-container">
                            <select name="tahun" class="news2-dropdown">
                                <option value="">Semua tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" @selected((string) request('tahun') === (string) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="news2-search-btn">SEARCH</button>
                    </div>
                </form>
                <div class="news2-results-info">
                    Menampilkan {{ $publications->firstItem() ?? 0 }} sampai {{ $publications->lastItem() ?? 0 }} dari {{ $publications->total() }} publication
                </div>
            </div>
        </div>

        <div class="news2-items">
            @forelse($publications as $publication)
                <article class="news2-item" data-aos="fade-up">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="news2-image-container">
                                @if($publication->thumbnail_path)
                                    <img src="{{ asset('storage/' . $publication->thumbnail_path) }}" alt="{{ $publication->judul }}" class="news2-image">
                                @else
                                    <div class="news2-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-journal-text text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="news2-content">
                                <div class="news2-date">{{ $publication->tahun }} | <span class="news2-category">{{ $publication->kategori }}</span></div>
                                <h3 class="news2-title">
                                    <a href="{{ route('publications.show', $publication) }}">{{ $publication->judul }}</a>
                                </h3>
                                <p class="mb-2"><strong>Penulis:</strong> {{ $publication->penulis }}</p>
                                <p class="news2-excerpt">{{ \Illuminate\Support\Str::limit($publication->abstrak, 280) }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('publications.show', $publication) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                                    <a href="{{ route('publications.download', $publication) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-5">
                    <h4>Publication belum tersedia</h4>
                    <p class="mb-0">Data publication publish akan tampil di halaman ini.</p>
                </div>
            @endforelse
        </div>

        <div class="news2-pagination">
            {{ $publications->links() }}
        </div>
    </div>
</section>
