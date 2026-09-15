<x-layout.link>
    <x-layout.navbar></x-layout.navbar>

    <style>
        .search-hero {
            padding: 160px 0 60px;
            background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 50%, #f4f8fc 100%);
        }

        .search-hero h1 {
            font-family: "Spline Sans", sans-serif;
            font-weight: 700;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            color: #1e293b;
        }

        .search-hero .search-form {
            max-width: 560px;
            margin-top: 1.5rem;
        }

        .search-hero .search-form .form-control {
            border-radius: 30px 0 0 30px;
            padding: 12px 22px;
            border: 1.5px solid #d0dff0;
        }

        .search-hero .search-form .btn {
            border-radius: 0 30px 30px 0;
            padding: 12px 26px;
            background: #4c8dc9;
            border-color: #4c8dc9;
            color: #fff;
            font-weight: 600;
        }

        .search-section {
            padding: 50px 0 90px;
        }

        .search-category-title {
            font-family: "Spline Sans", sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-category-title i {
            color: #4c8dc9;
        }

        .search-result-card {
            display: block;
            background: #fff;
            border: 1px solid #e4edf8;
            border-radius: 12px;
            padding: 16px 18px;
            text-decoration: none;
            color: inherit;
            height: 100%;
            transition: box-shadow .2s, transform .2s, border-color .2s;
        }

        .search-result-card:hover {
            box-shadow: 0 10px 26px rgba(27, 63, 114, .12);
            transform: translateY(-2px);
            border-color: #c8ddf4;
            color: inherit;
        }

        .search-result-card .title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .search-result-card .meta {
            font-size: 12.5px;
            color: #4c8dc9;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 6px;
        }

        .search-result-card .desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.6;
        }

        .search-empty {
            text-align: center;
            padding: 60px 20px;
        }

        .search-empty i {
            font-size: 3.5rem;
            color: #c8ddf4;
            margin-bottom: 1rem;
        }
    </style>

    <!-- ===== HERO + SEARCH FORM ===== -->
    <section class="search-hero">
        <div class="container">
            <h1>
                @if($keyword !== '')
                    Hasil pencarian untuk "<span style="color:#4c8dc9">{{ $keyword }}</span>"
                @else
                    Cari Sesuatu
                @endif
            </h1>
            <p class="text-muted mb-0">
                @if($keyword !== '')
                    Ditemukan {{ $totalResults }} hasil di Berita, Publikasi, Program, Proyek, Tim, dan Mitra.
                @else
                    Masukkan kata kunci untuk mencari berita, publikasi, program, proyek, tim, dan mitra kami.
                @endif
            </p>

            <form action="{{ route('search.index') }}" method="GET" class="search-form d-flex">
                <input type="text" name="q" value="{{ $keyword }}" class="form-control"
                    placeholder="Ketik kata kunci pencarian..." autofocus>
                <button type="submit" class="btn"><i class="fas fa-search me-1"></i> Cari</button>
            </form>
        </div>
    </section>

    <!-- ===== RESULTS ===== -->
    <section class="search-section">
        <div class="container">

            @if($keyword === '')
                <div class="search-empty">
                    <i class="fas fa-search"></i>
                    <p class="text-muted">Silakan ketik kata kunci pada kotak pencarian di atas.</p>
                </div>
            @elseif($totalResults === 0)
                <div class="search-empty">
                    <i class="fas fa-folder-open"></i>
                    <p class="text-muted">Tidak ada hasil ditemukan untuk "<strong>{{ $keyword }}</strong>".<br>
                        Coba gunakan kata kunci lain.</p>
                </div>
            @else

                {{-- BERITA --}}
                @if($news->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-newspaper"></i> Berita
                            ({{ $news->count() }})</div>
                        <div class="row g-3">
                            @foreach($news as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('news.show', $item->slug) }}" class="search-result-card">
                                        <div class="meta">{{ $item->kategori }}</div>
                                        <div class="title">{{ $item->judul }}</div>
                                        <div class="desc">{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 90) }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PUBLIKASI --}}
                @if($publications->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-book"></i> Publikasi
                            ({{ $publications->count() }})</div>
                        <div class="row g-3">
                            @foreach($publications as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('publications.show', $item->id) }}" class="search-result-card">
                                        <div class="meta">{{ $item->kategori }} &middot; {{ $item->tahun }}</div>
                                        <div class="title">{{ $item->judul }}</div>
                                        <div class="desc">{{ $item->penulis }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PROGRAM --}}
                @if($programs->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-layer-group"></i> Program
                            ({{ $programs->count() }})</div>
                        <div class="row g-3">
                            @foreach($programs as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('programs.frontend.index') }}" class="search-result-card">
                                        <div class="title">{{ $item->judul }}</div>
                                        <div class="desc">{{ \Illuminate\Support\Str::limit($item->deskripsi, 90) }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PROYEK --}}
                @if($projects->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-diagram-project"></i> Proyek
                            ({{ $projects->count() }})</div>
                        <div class="row g-3">
                            @foreach($projects as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('projects.frontend.index') }}" class="search-result-card">
                                        <div class="meta">{{ $item->kategori }} &middot; {{ $item->tahun }}</div>
                                        <div class="title">{{ $item->judul }}</div>
                                        <div class="desc">{{ \Illuminate\Support\Str::limit($item->deskripsi, 90) }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- TIM --}}
                @if($teams->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-users"></i> Tim
                            ({{ $teams->count() }})</div>
                        <div class="row g-3">
                            @foreach($teams as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('teams.frontend.index') }}" class="search-result-card">
                                        <div class="meta">{{ $item->jabatan }}</div>
                                        <div class="title">{{ $item->nama }}</div>
                                        <div class="desc">{{ $item->bidang }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- MITRA --}}
                @if($partners->isNotEmpty())
                    <div class="mb-5">
                        <div class="search-category-title"><i class="fas fa-handshake"></i> Mitra
                            ({{ $partners->count() }})</div>
                        <div class="row g-3">
                            @foreach($partners as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('partners.user') }}" class="search-result-card">
                                        <div class="title">{{ $item->nama }}</div>
                                        <div class="desc">{{ \Illuminate\Support\Str::limit($item->deskripsi, 90) }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif
        </div>
    </section>

    <x-layout.footer></x-layout.footer>
</x-layout.link>