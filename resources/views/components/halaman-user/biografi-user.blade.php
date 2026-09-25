@props(['lecturer' => null, 'publications' => collect(), 'hkis' => collect()])

@php
    $isDosenArea = auth()->check() && auth()->user()->role === 'dosen' && auth()->user()->registration_status === \App\Models\User::STATUS_APPROVED;
    $teamUrl = url($isDosenArea ? '/team-dosen' : '/team-user');
    $isSample = $lecturer === null;

    if ($isSample) {
        $profile = [
            'nama' => 'Lorem Ipsum, S.T., M.T.',
            'jabatan' => 'Ketua Kelompok Riset Mobilitas Cerdas',
            'unit' => 'Fakultas Ilmu Terapan, Telkom University',
            'email' => null,
            'foto' => null,
            'bio' => [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, sehingga setiap riset yang dijalankan selalu berangkat dari persoalan nyata di lingkungan perkotaan.',
                'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            ],
            'bidang' => ['Sistem Transportasi Cerdas', 'Internet of Things', 'Analitik Data Perkotaan', 'Kebijakan Kota Cerdas'],
        ];
        $samplePublications = [
            ['tahun' => '2025', 'judul' => 'Lorem Ipsum: Model Prediksi Kepadatan Lalu Lintas Berbasis Sensor Terdistribusi', 'meta' => 'Jurnal Dolor Sit Amet, Vol. 12'],
            ['tahun' => '2024', 'judul' => 'Consectetur Adipiscing dalam Tata Kelola Data Kota yang Terbuka dan Aman', 'meta' => 'Prosiding Konferensi Sed Do Eiusmod'],
            ['tahun' => '2023', 'judul' => 'Tempor Incididunt: Evaluasi Layanan Publik Digital di Kawasan Metropolitan', 'meta' => 'Jurnal Magna Aliqua, Vol. 8'],
        ];
        $sampleHkis = [
            ['jenis' => 'Hak Cipta', 'judul' => 'Aplikasi Lorem Ipsum untuk Pemantauan Kualitas Udara', 'meta' => 'No. EC0000000000'],
        ];
    } else {
        $profile = [
            'nama' => $lecturer->fullname,
            'jabatan' => $lecturer->prodi ?: 'Dosen Peneliti',
            'unit' => trim(($lecturer->fakultas ? $lecturer->fakultas.', ' : '').'Telkom University'),
            'email' => $lecturer->email,
            'foto' => $lecturer->foto,
            'bio' => collect(preg_split('/\R+/', (string) $lecturer->bio))->map(fn ($p) => trim($p))->filter()->values()->all(),
            'bidang' => collect(preg_split('/[,;\n]+/', (string) $lecturer->bidang_penelitian))->map(fn ($p) => trim($p))->filter()->values()->all(),
        ];
    }
@endphp

<section class="sc-bio-hero">
    <div class="container">
        <a href="{{ $teamUrl }}" class="sc-back-link"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Tim</a>
        <div class="sc-bio-hero__grid">
            <div class="sc-bio-hero__photo">
                @if ($profile['foto'])
                    <img src="{{ asset('storage/'.$profile['foto']) }}" alt="Foto {{ $profile['nama'] }}">
                @else
                    <span aria-hidden="true">{{ mb_strtoupper(mb_substr($profile['nama'], 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <h1 class="sc-bio-hero__name">{{ $profile['nama'] }}</h1>
                <p class="sc-bio-hero__role">{{ $profile['jabatan'] }}</p>
                <p class="sc-bio-hero__unit"><i class="bi bi-building" aria-hidden="true"></i> {{ $profile['unit'] }}</p>
                @if ($profile['email'])
                    <a href="mailto:{{ $profile['email'] }}" class="sc-btn sc-btn--light sc-bio-hero__mail">
                        <i class="bi bi-envelope" aria-hidden="true"></i> {{ $profile['email'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="sc-section sc-bio">
    <div class="container">
        <div class="sc-bio__grid">
            <aside class="sc-bio__aside">
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Bidang Penelitian</h2>
                    @if (! empty($profile['bidang']))
                        <ul class="sc-chips">
                            @foreach ($profile['bidang'] as $bidang)
                                <li>{{ $bidang }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="sc-panel__muted">Bidang penelitian belum diisi.</p>
                    @endif
                </div>
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Ringkasan</h2>
                    <dl class="sc-bio__stats">
                        <div>
                            <dt>Publikasi</dt>
                            <dd>{{ $isSample ? count($samplePublications) : $publications->count() }}</dd>
                        </div>
                        <div>
                            <dt>HKI</dt>
                            <dd>{{ $isSample ? count($sampleHkis) : $hkis->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>

            <div class="sc-bio__main">
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Tentang</h2>
                    @forelse ($profile['bio'] as $paragraph)
                        <p class="sc-bio__text">{{ $paragraph }}</p>
                    @empty
                        <p class="sc-panel__muted">Biografi belum diisi.</p>
                    @endforelse
                </div>

                <div class="sc-panel">
                    <h2 class="sc-panel__title">Publikasi</h2>
                    @if ($isSample)
                        <ul class="sc-list">
                            @foreach ($samplePublications as $item)
                                <li class="sc-list__item">
                                    <span class="sc-list__year">{{ $item['tahun'] }}</span>
                                    <div>
                                        <h3 class="sc-list__title">{{ $item['judul'] }}</h3>
                                        <p class="sc-list__meta">{{ $item['meta'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @elseif ($publications->isNotEmpty())
                        <ul class="sc-list">
                            @foreach ($publications as $item)
                                <li class="sc-list__item">
                                    <span class="sc-list__year">{{ $item->tahun }}</span>
                                    <div>
                                        <h3 class="sc-list__title"><a href="{{ route('publications.show', $item) }}">{{ $item->judul }}</a></h3>
                                        <p class="sc-list__meta">{{ $item->kategori }}{{ $item->penerbit ? ', '.$item->penerbit : '' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="sc-panel__muted">Belum ada publikasi yang dipublikasikan.</p>
                    @endif
                </div>

                <div class="sc-panel">
                    <h2 class="sc-panel__title">Hak Kekayaan Intelektual</h2>
                    @if ($isSample)
                        <ul class="sc-list">
                            @foreach ($sampleHkis as $item)
                                <li class="sc-list__item">
                                    <span class="sc-list__year">{{ $item['jenis'] }}</span>
                                    <div>
                                        <h3 class="sc-list__title">{{ $item['judul'] }}</h3>
                                        <p class="sc-list__meta">{{ $item['meta'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @elseif ($hkis->isNotEmpty())
                        <ul class="sc-list">
                            @foreach ($hkis as $item)
                                <li class="sc-list__item">
                                    <span class="sc-list__year">{{ $item->jenis_sertifikat }}</span>
                                    <div>
                                        <h3 class="sc-list__title">{{ $item->judul_sertifikat }}</h3>
                                        <p class="sc-list__meta">No. {{ $item->nomor_sertifikat }}{{ $item->tgl_terbit ? ', terbit '.$item->tgl_terbit->translatedFormat('d F Y') : '' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="sc-panel__muted">Belum ada HKI yang dipublikasikan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
