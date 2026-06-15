@php
    // Ambil 4 program terbaru yang publish, urutkan by urutan
    $cardPrograms = \App\Models\Program::published()
        ->orderBy('urutan')
        ->limit(4)
        ->get();

    // Warna gradient untuk 4 card berurutan
    $gradients = ['blue-gradient', 'purple-gradient', 'cyan-gradient', 'orange-gradient'];
@endphp

<!-- Card Section -->
<section class="cards-section">
    <div class="container">
        <div class="row g-4">

            @forelse($cardPrograms as $index => $program)
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="program-card">
                        <div class="card-image">
                            @if($program->thumbnail_path)
                                <img src="{{ asset('storage/' . $program->thumbnail_path) }}"
                                     alt="{{ $program->judul }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light"
                                     style="height:200px;">
                                    <i class="bi bi-layers text-secondary" style="font-size:3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-content {{ $gradients[$index % 4] }}">
                            <h3>{{ $program->judul }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($program->deskripsi, 120) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Fallback: tampilkan placeholder kalau belum ada data --}}
                @foreach(['Smart City Program', 'Innovation for Future', 'Smart Infrastructure', 'Sustainable Cities'] as $index => $title)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up">
                        <div class="program-card">
                            <div class="card-image">
                                <img src="img/card{{ $index + 1 }}.jpg" alt="{{ $title }}">
                            </div>
                            <div class="card-content {{ $gradients[$index] }}">
                                <h3>{{ $title }}</h3>
                                <p>Program unggulan COE Smart City Universitas Telkom dalam pengembangan teknologi kota cerdas.</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse

        </div>

        @if($cardPrograms->isNotEmpty())
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="{{ route('programs.frontend.index') }}" class="btn btn-outline-primary px-4">
                    Lihat Semua Program <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>
</section>
