@props(['programs' => collect()])

<!-- Program Section -->
<section class="program-section" id="program">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-up">
                <h2 class="program-title">Program from CoE</h2>
                <div class="program-subtitle" data-aos="fade-up">SmartCity</div>
            </div>
            <div class="col-lg-6" data-aos="fade-up">
                <div class="program-content">
                    <h3>Our Program</h3>
                    <p>Program ini bertujuan mengintegrasikan berbagai metode guna meningkatkan kualitas hidup
                        di kota-kota modern.</p>
                </div>
            </div>
        </div>

        @if($programs->isNotEmpty())
            <div class="row g-4 mt-4">
                @foreach($programs as $program)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                            @if($program->thumbnail_path)
                                <img src="{{ asset('storage/'.$program->thumbnail_path) }}"
                                     alt="{{ $program->judul }}"
                                     style="height:180px;object-fit:cover;width:100%;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light"
                                     style="height:180px;">
                                    <i class="bi bi-layers text-secondary" style="font-size:3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2">{{ $program->judul }}</h5>
                                <p class="text-muted small mb-0">
                                    {{ \Illuminate\Support\Str::limit($program->deskripsi, 120) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
