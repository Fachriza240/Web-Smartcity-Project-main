@props(['partners' => collect()])

@if($partners->isNotEmpty())
<section class="py-5 bg-light" id="partners">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2>Our <span class="text-primary">Partners</span></h2>
            <p class="text-muted">Mitra strategis COE Smart City dalam pengembangan teknologi kota cerdas.</p>
        </div>
        <div class="row g-4 justify-content-center" data-aos="fade-up">
            @foreach($partners as $partner)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:12px;">
                        @if($partner->logo_path)
                            <img src="{{ asset('storage/'.$partner->logo_path) }}"
                                 alt="{{ $partner->nama }}"
                                 style="height:60px;object-fit:contain;margin:0 auto 12px;" class="d-block">
                        @else
                            <div class="d-flex align-items-center justify-content-center mb-3" style="height:60px;">
                                <i class="bi bi-building text-primary" style="font-size:2rem;"></i>
                            </div>
                        @endif
                        <div class="small fw-semibold">{{ $partner->nama }}</div>
                        @if($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" class="small text-muted mt-1">
                                <i class="bi bi-link-45deg"></i> Website
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
