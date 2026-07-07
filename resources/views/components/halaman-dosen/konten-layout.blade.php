@props(['title' => 'Konten Saya', 'active' => ''])

<x-layout.link>
<x-layout.navbar-dosen></x-layout.navbar-dosen>

<div style="background:#f0f4f8; min-height:80vh; padding: 36px 0 48px;">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0 0 4px;">
                    Konten Saya
                </h2>
                <p style="font-size:13px;color:#94a3b8;margin:0;">
                    Kelola publikasi dan HKI atas nama Anda.
                </p>
            </div>
            <a href="{{ route('profil.dosen') }}"
               style="font-size:13px;font-weight:600;color:#4c8dc9;text-decoration:none;display:flex;align-items:center;gap:5px;">
                <i class="bi bi-person-circle"></i> Lihat Profil
            </a>
        </div>

        {{-- Tab navigation --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;
                    box-shadow:0 2px 8px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;">
            <div style="display:flex;border-bottom:2px solid #f1f5f9;">
                <a href="{{ route('dosen.publikasi.index') }}"
                   style="padding:14px 22px;font-size:14px;font-weight:700;text-decoration:none;
                          border-bottom: 3px solid {{ $active === 'publikasi' ? '#4c8dc9' : 'transparent' }};
                          color: {{ $active === 'publikasi' ? '#4c8dc9' : '#64748b' }};
                          margin-bottom:-2px;transition:color .2s;">
                    <i class="bi bi-journal-text me-1"></i> Publikasi
                </a>
                <a href="{{ route('dosen.hki.index') }}"
                   style="padding:14px 22px;font-size:14px;font-weight:700;text-decoration:none;
                          border-bottom: 3px solid {{ $active === 'hki' ? '#4c8dc9' : 'transparent' }};
                          color: {{ $active === 'hki' ? '#4c8dc9' : '#64748b' }};
                          margin-bottom:-2px;transition:color .2s;">
                    <i class="bi bi-award me-1"></i> HKI
                </a>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;
                        border-radius:10px;padding:12px 18px;font-size:13px;font-weight:500;
                        display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Content --}}
        {{ $slot }}

    </div>
</div>

<x-layout.footer></x-layout.footer>
</x-layout.link>
