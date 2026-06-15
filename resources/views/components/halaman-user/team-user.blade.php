@props(['staff' => collect(), 'interns' => collect()])

<!-- Lecturer Section -->
<section class="lecturer-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our <span>Team</span></h2>
            <p class="section-subtitle">Kami adalah tim operasional yang profesional dan berkomitmen, berfokus pada
                pengelolaan dan implementasi teknologi untuk mewujudkan visi kota cerdas yang efisien dan inovatif</p>
        </div>

        <!-- Filter Tabs -->
        <div class="search-filters mb-4">
            <div class="filter-tabs">
                <button class="filter-tab active" data-target="staff-section">STAFF</button>
                <button class="filter-tab" data-target="interns-section">INTERNS</button>
            </div>
        </div>

        <!-- Staff Section -->
        <div id="staff-section" class="table-section">
            <div class="lecturer-table-header">
                <div class="row align-items-center py-3 border-bottom">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">Nama Belakang</div>
                    <div class="col-md-2">Nama Depan</div>
                    <div class="col-md-2">Telepon</div>
                    <div class="col-md-2">Email</div>
                    <div class="col-md-3">Jabatan</div>
                </div>
            </div>
            <div class="lecturer-listing">
                @forelse($staff as $member)
                    @php
                        $parts    = explode(' ', trim($member->nama));
                        $lastname  = array_pop($parts);
                        $firstname = implode(' ', $parts) ?: '-';
                    @endphp
                    <div class="row lecturer-row align-items-center py-4 border-bottom" data-aos="fade-up">
                        <div class="col-md-1">
                            <div class="lecturer-image">
                                @if($member->foto_path)
                                    <img src="{{ asset('storage/'.$member->foto_path) }}" alt="{{ $member->nama }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width:48px;height:48px;border-radius:50%;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2"><div class="lecturer-lastname">{{ $lastname }}</div></div>
                        <div class="col-md-2"><div class="lecturer-firstname">{{ $firstname }}</div></div>
                        <div class="col-md-2"><div class="lecturer-phone">{{ $member->telepon ?? '-' }}</div></div>
                        <div class="col-md-2">
                            @if($member->email)
                                <a href="mailto:{{ $member->email }}" class="lecturer-email">{{ $member->email }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="lecturer-position">{{ $member->jabatan }}</div>
                            @if($member->linkedin || $member->instagram || $member->github)
                                <div class="d-flex gap-2 mt-1">
                                    @if($member->linkedin)
                                        <a href="{{ $member->linkedin }}" target="_blank" class="text-primary small">
                                            <i class="bi bi-linkedin"></i>
                                        </a>
                                    @endif
                                    @if($member->instagram)
                                        <a href="https://instagram.com/{{ ltrim($member->instagram,'@') }}" target="_blank" class="text-danger small">
                                            <i class="bi bi-instagram"></i>
                                        </a>
                                    @endif
                                    @if($member->github)
                                        <a href="https://github.com/{{ $member->github }}" target="_blank" class="text-dark small">
                                            <i class="bi bi-github"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-muted">Belum ada data staff.</div>
                @endforelse
            </div>
        </div>

        <!-- Interns Section -->
        <div id="interns-section" class="table-section" style="display:none;">
            <div class="lecturer-table-header">
                <div class="row align-items-center py-3 border-bottom">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">Nama Belakang</div>
                    <div class="col-md-2">Nama Depan</div>
                    <div class="col-md-2">Telepon</div>
                    <div class="col-md-2">Email</div>
                    <div class="col-md-3">Jabatan</div>
                </div>
            </div>
            <div class="lecturer-listing">
                @forelse($interns as $member)
                    @php
                        $parts    = explode(' ', trim($member->nama));
                        $lastname  = array_pop($parts);
                        $firstname = implode(' ', $parts) ?: '-';
                    @endphp
                    <div class="row lecturer-row align-items-center py-4 border-bottom" data-aos="fade-up">
                        <div class="col-md-1">
                            <div class="lecturer-image">
                                @if($member->foto_path)
                                    <img src="{{ asset('storage/'.$member->foto_path) }}" alt="{{ $member->nama }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width:48px;height:48px;border-radius:50%;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2"><div class="lecturer-lastname">{{ $lastname }}</div></div>
                        <div class="col-md-2"><div class="lecturer-firstname">{{ $firstname }}</div></div>
                        <div class="col-md-2"><div class="lecturer-phone">{{ $member->telepon ?? '-' }}</div></div>
                        <div class="col-md-2">
                            @if($member->email)
                                <a href="mailto:{{ $member->email }}" class="lecturer-email">{{ $member->email }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="lecturer-position">{{ $member->jabatan }}</div>
                            @if($member->linkedin || $member->instagram || $member->github)
                                <div class="d-flex gap-2 mt-1">
                                    @if($member->linkedin)
                                        <a href="{{ $member->linkedin }}" target="_blank" class="text-primary small">
                                            <i class="bi bi-linkedin"></i>
                                        </a>
                                    @endif
                                    @if($member->instagram)
                                        <a href="https://instagram.com/{{ ltrim($member->instagram,'@') }}" target="_blank" class="text-danger small">
                                            <i class="bi bi-instagram"></i>
                                        </a>
                                    @endif
                                    @if($member->github)
                                        <a href="https://github.com/{{ $member->github }}" target="_blank" class="text-dark small">
                                            <i class="bi bi-github"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-muted">Belum ada data intern.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init();
        const tabButtons = document.querySelectorAll('.filter-tab');
        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.table-section').forEach(s => s.style.display = 'none');
                document.getElementById(this.getAttribute('data-target')).style.display = 'block';
                setTimeout(() => AOS.refresh(), 10);
            });
        });
        if (tabButtons.length > 0) tabButtons[0].click();
    });
</script>
