@props(['lecturers' => collect(), 'staff' => collect(), 'interns' => collect()])

@php
    $isDosenArea = auth()->check() && auth()->user()->role === 'dosen' && auth()->user()->registration_status === \App\Models\User::STATUS_APPROVED;
    $bioRoute = $isDosenArea ? 'biografi.dosen' : 'biografi.user';

    $placeholderLecturers = [
        ['nama' => 'Lorem Ipsum, S.T., M.T.', 'jabatan' => 'Ketua Kelompok Riset Mobilitas Cerdas', 'bidang' => 'Sistem Transportasi Cerdas'],
        ['nama' => 'Dolor Sit Amet, S.Kom., M.Kom.', 'jabatan' => 'Peneliti Senior', 'bidang' => 'Internet of Things'],
        ['nama' => 'Consectetur Adipiscing, Ph.D.', 'jabatan' => 'Peneliti Senior', 'bidang' => 'Kecerdasan Buatan'],
        ['nama' => 'Sed Do Eiusmod, S.T., M.Sc.', 'jabatan' => 'Peneliti', 'bidang' => 'Energi dan Lingkungan'],
    ];
    $placeholderStaff = [
        ['nama' => 'Tempor Incididunt', 'jabatan' => 'Koordinator Operasional', 'bidang' => 'Manajemen Program'],
        ['nama' => 'Labore Et Dolore', 'jabatan' => 'Staf Administrasi', 'bidang' => 'Kerja Sama dan Kemitraan'],
        ['nama' => 'Magna Aliqua', 'jabatan' => 'Staf Teknis Laboratorium', 'bidang' => 'Infrastruktur IoT'],
        ['nama' => 'Ut Enim Minim', 'jabatan' => 'Staf Publikasi', 'bidang' => 'Komunikasi dan Media'],
    ];
    $placeholderInterns = [
        ['nama' => 'Quis Nostrud', 'jabatan' => 'Magang Pengembang Web', 'bidang' => 'Rekayasa Perangkat Lunak'],
        ['nama' => 'Exercitation Ullamco', 'jabatan' => 'Magang Analis Data', 'bidang' => 'Sains Data'],
        ['nama' => 'Laboris Nisi', 'jabatan' => 'Magang Penguji Sistem', 'bidang' => 'Penjaminan Mutu'],
        ['nama' => 'Aliquip Commodo', 'jabatan' => 'Magang Desain Konten', 'bidang' => 'Desain Komunikasi Visual'],
    ];

    $groups = [
        [
            'id' => 'tim-dosen',
            'label' => 'Dosen dan Peneliti',
            'icon' => 'bi-mortarboard',
            'members' => $lecturers->map(fn ($item) => [
                'nama' => $item->fullname,
                'jabatan' => $item->prodi ?: 'Dosen Peneliti',
                'bidang' => $item->bidang_penelitian ? \Illuminate\Support\Str::limit($item->bidang_penelitian, 60) : $item->fakultas,
                'foto' => $item->foto,
                'email' => $item->email,
                'url' => route($bioRoute, $item->id),
            ])->all(),
            'placeholder' => array_map(fn ($item) => $item + ['url' => route($bioRoute)], $placeholderLecturers),
        ],
        [
            'id' => 'tim-staf',
            'label' => 'Staf',
            'icon' => 'bi-briefcase',
            'members' => $staff->map(fn ($item) => [
                'nama' => $item->nama,
                'jabatan' => $item->jabatan,
                'bidang' => $item->bidang,
                'foto' => $item->foto_path,
                'email' => $item->email,
                'linkedin' => $item->linkedin,
                'instagram' => $item->instagram,
                'github' => $item->github,
            ])->all(),
            'placeholder' => $placeholderStaff,
        ],
        [
            'id' => 'tim-magang',
            'label' => 'Magang',
            'icon' => 'bi-person-workspace',
            'members' => $interns->map(fn ($item) => [
                'nama' => $item->nama,
                'jabatan' => $item->jabatan,
                'bidang' => $item->bidang,
                'foto' => $item->foto_path,
                'email' => $item->email,
                'linkedin' => $item->linkedin,
                'instagram' => $item->instagram,
                'github' => $item->github,
            ])->all(),
            'placeholder' => $placeholderInterns,
        ],
    ];
@endphp

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Tim Kami</span>
        <h1 class="sc-page-hero__title">Orang-orang di balik<br><span class="text-blue">CoE Smart City</span></h1>
        <p class="sc-page-hero__lead">
            Dosen, peneliti, staf, dan mahasiswa magang yang bekerja bersama untuk mengubah gagasan kota cerdas
            menjadi solusi yang bisa dirasakan warga.
        </p>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<section class="sc-section">
    <div class="container">
        <div class="sc-tabs" role="tablist" aria-label="Kelompok anggota tim" data-tab-group="tim">
            @foreach ($groups as $group)
                <button type="button" role="tab" class="sc-tab {{ $loop->first ? 'active' : '' }}" id="{{ $group['id'] }}-tab"
                    data-tab-target="{{ $group['id'] }}" aria-controls="{{ $group['id'] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">
                    <i class="bi {{ $group['icon'] }}" aria-hidden="true"></i>
                    {{ $group['label'] }}
                    <span class="sc-tab__count">{{ count($group['members']) ?: count($group['placeholder']) }}</span>
                </button>
            @endforeach
        </div>

        @foreach ($groups as $group)
            @php
                $isSample = empty($group['members']);
                $members = $isSample ? $group['placeholder'] : $group['members'];
            @endphp
            <div class="sc-team-panel" id="{{ $group['id'] }}" role="tabpanel" aria-labelledby="{{ $group['id'] }}-tab"
                data-tab-panel="tim" @unless ($loop->first) hidden @endunless>
                <div class="sc-team-grid">
                    @foreach ($members as $member)
                        @php
                            $url = $member['url'] ?? null;
                            $tag = $url ? 'a' : 'article';
                        @endphp
                        <{{ $tag }} class="sc-team-card {{ $isSample ? 'is-sample' : '' }}" @if ($url) href="{{ $url }}" @endif>
                            <div class="sc-team-card__photo">
                                @if (! empty($member['foto']))
                                    <img src="{{ asset('storage/'.$member['foto']) }}" alt="Foto {{ $member['nama'] }}" loading="lazy">
                                @else
                                    <span class="sc-team-card__initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($member['nama'], 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="sc-team-card__body">
                                <h2 class="sc-team-card__name">{{ $member['nama'] }}</h2>
                                <p class="sc-team-card__role">{{ $member['jabatan'] ?: '-' }}</p>
                                @if (! empty($member['bidang']))
                                    <p class="sc-team-card__field"><i class="bi bi-bookmark" aria-hidden="true"></i> {{ $member['bidang'] }}</p>
                                @endif
                                @if ($url)
                                    <span class="sc-team-card__more">Lihat biografi <i class="bi bi-arrow-right-short" aria-hidden="true"></i></span>
                                @elseif (! empty($member['email']) || ! empty($member['linkedin']) || ! empty($member['instagram']) || ! empty($member['github']))
                                    <div class="sc-team-card__links">
                                        @if (! empty($member['email']))
                                            <a href="mailto:{{ $member['email'] }}" aria-label="Email {{ $member['nama'] }}"><i class="bi bi-envelope" aria-hidden="true"></i></a>
                                        @endif
                                        @if (! empty($member['linkedin']))
                                            <a href="{{ $member['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn {{ $member['nama'] }}"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
                                        @endif
                                        @if (! empty($member['instagram']))
                                            <a href="https://instagram.com/{{ ltrim($member['instagram'], '@') }}" target="_blank" rel="noopener" aria-label="Instagram {{ $member['nama'] }}"><i class="bi bi-instagram" aria-hidden="true"></i></a>
                                        @endif
                                        @if (! empty($member['github']))
                                            <a href="https://github.com/{{ $member['github'] }}" target="_blank" rel="noopener" aria-label="GitHub {{ $member['nama'] }}"><i class="bi bi-github" aria-hidden="true"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </{{ $tag }}>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
