@props(['news', 'categories', 'years'])

<!-- News Section Style 2 -->
<section id="news2" class="py-5" data-aos="fade-up">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">News <span class="highlight">Updates</span></h2>
                <p class="section-subtitle">Temukan berita terbaru seputar riset dan inovasi COE Smart City.</p>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <form action="{{ route('news.index') }}" method="GET" class="news2-search-container">
                    <div class="news2-search-inner">
                        <input type="text" name="search" class="news2-search-input"
                               value="{{ request('search') }}" placeholder="Cari berita...">
                        <div class="news2-dropdown-container">
                            <select name="kategori" class="news2-dropdown">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(request('kategori') === $cat)>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="news2-dropdown-container">
                            <select name="tahun" class="news2-dropdown">
                                <option value="">-- Semua Tahun --</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" @selected((string)request('tahun') === (string)$year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="news2-search-btn">SEARCH</button>
                    </div>
                </form>
                <div class="news2-results-info">
                    Menampilkan {{ $news->firstItem() ?? 0 }} - {{ $news->lastItem() ?? 0 }} dari {{ $news->total() }} berita
                </div>
            </div>
        </div>

        <!-- News Items -->
        <div class="news2-items">
            @forelse($news as $item)
                <article class="news2-item" data-aos="fade-up">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="news2-image-container">
                                @if($item->thumbnail_path)
                                    <img src="{{ asset('storage/'.$item->thumbnail_path) }}"
                                         alt="{{ $item->judul }}" class="news2-image">
                                @else
                                    <div class="news2-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-newspaper text-primary" style="font-size:3rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="news2-content">
                                <div class="news2-date">
                                    {{ strtoupper($item->published_at?->translatedFormat('l d F Y') ?? '-') }}
                                    @if($item->kategori)
                                        | <span class="news2-category">{{ strtoupper($item->kategori) }}</span>
                                    @endif
                                </div>
                                <h3 class="news2-title">
                                    <a href="{{ route('news.show', $item) }}">{{ $item->judul }}</a>
                                </h3>
                                <p class="news2-excerpt">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 280) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-newspaper text-muted" style="font-size:3rem;"></i>
                    <h4 class="mt-3">Belum ada berita</h4>
                    <p class="text-muted mb-0">Berita yang dipublikasikan akan tampil di sini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="news2-pagination mt-4">
            {{ $news->links() }}
        </div>
    </div>
</section>
