@props(['projects' => collect()])

<!-- Project Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="gallery-title">Our <span class="text-primary">Project</span></h2>
            <p class="gallery-subtitle">Dokumentasi proyek-proyek inovatif dalam pengembangan Smart City.</p>
        </div>

        @if($projects->isNotEmpty())
            {{-- Carousel dari gallery foto project pertama yang punya gallery --}}
            @php
                $featured = $projects->first(fn($p) => !empty($p->gallery_paths)) ?? $projects->first();
            @endphp

            @if($featured)
                <div class="gallery-carousel mb-5" data-aos="fade-up">
                    <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @php $isFirst = true; @endphp
                            @if(!empty($featured->gallery_paths))
                                @foreach($featured->gallery_paths as $img)
                                    <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                        <div class="carousel-image-container">
                                            <img src="{{ asset('storage/' . $img) }}"
                                                 class="d-block w-100" alt="{{ $featured->judul }}">
                                            <div class="carousel-caption">
                                                <h5>{{ $featured->judul }}</h5>
                                                <p>{{ \Illuminate\Support\Str::limit($featured->deskripsi, 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @php $isFirst = false; @endphp
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <div class="carousel-image-container">
                                        @if($featured->thumbnail_path)
                                            <img src="{{ asset('storage/' . $featured->thumbnail_path) }}"
                                                 class="d-block w-100" alt="{{ $featured->judul }}">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                 style="height: 400px;">
                                                <i class="bi bi-kanban text-secondary" style="font-size: 4rem;"></i>
                                            </div>
                                        @endif
                                        <div class="carousel-caption">
                                            <h5>{{ $featured->judul }}</h5>
                                            <p>{{ \Illuminate\Support\Str::limit($featured->deskripsi, 100) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(!empty($featured->gallery_paths) && count($featured->gallery_paths) > 1)
                            <button class="carousel-control-prev" type="button"
                                    data-bs-target="#galleryCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#galleryCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="carousel-indicators">
                                @foreach($featured->gallery_paths as $i => $img)
                                    <button type="button" data-bs-target="#galleryCarousel"
                                            data-bs-slide-to="{{ $i }}"
                                            class="{{ $i === 0 ? 'active' : '' }}"
                                            @if($i === 0) aria-current="true" @endif
                                            aria-label="Slide {{ $i + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Daftar semua project --}}
            <div class="row g-4" data-aos="fade-up">
                @foreach($projects as $project)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0"
                             style="border-radius: 12px; overflow: hidden;">
                            {{-- Thumbnail --}}
                            <div style="height: 200px; overflow: hidden; background: #f0f4f8;">
                                @if($project->thumbnail_path)
                                    <img src="{{ asset('storage/' . $project->thumbnail_path) }}"
                                         alt="{{ $project->judul }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="bi bi-kanban text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                {{-- Meta --}}
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @if($project->kategori)
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">
                                            {{ $project->kategori }}
                                        </span>
                                    @endif
                                    @if($project->tahun)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">
                                            {{ $project->tahun }}
                                        </span>
                                    @endif
                                </div>

                                <h5 class="card-title fw-bold mb-2">{{ $project->judul }}</h5>

                                <p class="card-text text-muted small flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit($project->deskripsi, 120) }}
                                </p>

                                @if($project->partner)
                                    <div class="small text-muted mt-2">
                                        <i class="bi bi-building me-1"></i> {{ $project->partner }}
                                    </div>
                                @endif

                                {{-- Dokumen --}}
                                @if($project->dokumen_path)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $project->dokumen_path) }}"
                                           target="_blank"
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="bi bi-kanban text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Belum ada project</h4>
                <p class="text-muted mb-0">Data project yang dipublikasikan akan tampil di sini.</p>
            </div>
        @endif
    </div>
</section>
