<x-layout.link>
    <x-layout.navbar-dosen></x-layout.navbar-dosen>
    <x-layout.home></x-layout.home>

    {{-- Quick access dosen --}}
    <section style="background:#f0f4f8;padding:40px 0;">
        <div class="container">
            <div style="text-align:center;margin-bottom:28px;">
                <h3 style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 6px;">
                    Selamat datang, {{ auth()->user()?->fullname ?? 'Dosen' }}!
                </h3>
                <p style="font-size:14px;color:#94a3b8;margin:0;">
                    Kelola konten Anda atau jelajahi halaman dosen.
                </p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;max-width:900px;margin:0 auto;">

                <a href="{{ route('profil.dosen') }}"
                   style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;
                          padding:22px 20px;text-decoration:none;text-align:center;
                          box-shadow:0 2px 8px rgba(0,0,0,.05);transition:all .2s;display:block;"
                   onmouseover="this.style.borderColor='#4c8dc9';this.style.boxShadow='0 4px 16px rgba(76,141,201,.15)'"
                   onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,.05)'">
                    <i class="bi bi-person-circle" style="font-size:2rem;color:#4c8dc9;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;font-weight:700;color:#0f172a;">Profil Saya</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Edit data diri</div>
                </a>

                <a href="{{ route('dosen.publikasi.index') }}"
                   style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;
                          padding:22px 20px;text-decoration:none;text-align:center;
                          box-shadow:0 2px 8px rgba(0,0,0,.05);transition:all .2s;display:block;"
                   onmouseover="this.style.borderColor='#4c8dc9';this.style.boxShadow='0 4px 16px rgba(76,141,201,.15)'"
                   onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,.05)'">
                    <i class="bi bi-journal-text" style="font-size:2rem;color:#0891b2;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;font-weight:700;color:#0f172a;">Publikasi Saya</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">
                        {{ \App\Models\Publication::where('user_id', auth()->id())->count() }} entri
                    </div>
                </a>

                <a href="{{ route('dosen.hki.index') }}"
                   style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;
                          padding:22px 20px;text-decoration:none;text-align:center;
                          box-shadow:0 2px 8px rgba(0,0,0,.05);transition:all .2s;display:block;"
                   onmouseover="this.style.borderColor='#4c8dc9';this.style.boxShadow='0 4px 16px rgba(76,141,201,.15)'"
                   onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,.05)'">
                    <i class="bi bi-award" style="font-size:2rem;color:#ca8a04;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;font-weight:700;color:#0f172a;">HKI Saya</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">
                        {{ \App\Models\Hki::where('user_id', auth()->id())->count() }} entri
                    </div>
                </a>

                <a href="{{ route('dosen.publikasi.create') }}"
                   style="background:#4c8dc9;border:1px solid #4c8dc9;border-radius:14px;
                          padding:22px 20px;text-decoration:none;text-align:center;
                          box-shadow:0 4px 14px rgba(76,141,201,.35);transition:all .2s;display:block;"
                   onmouseover="this.style.background='#3a7ab3';this.style.borderColor='#3a7ab3'"
                   onmouseout="this.style.background='#4c8dc9';this.style.borderColor='#4c8dc9'">
                    <i class="bi bi-plus-circle-fill" style="font-size:2rem;color:#fff;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;font-weight:700;color:#fff;">Tambah Publikasi</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.75);margin-top:3px;">Upload publikasi baru</div>
                </a>

                <a href="{{ route('dosen.hki.create') }}"
                   style="background:#22c55e;border:1px solid #22c55e;border-radius:14px;
                          padding:22px 20px;text-decoration:none;text-align:center;
                          box-shadow:0 4px 14px rgba(34,197,94,.3);transition:all .2s;display:block;"
                   onmouseover="this.style.background='#16a34a';this.style.borderColor='#16a34a'"
                   onmouseout="this.style.background='#22c55e';this.style.borderColor='#22c55e'">
                    <i class="bi bi-plus-circle-fill" style="font-size:2rem;color:#fff;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;font-weight:700;color:#fff;">Tambah HKI</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.75);margin-top:3px;">Daftarkan HKI baru</div>
                </a>

            </div>
        </div>
    </section>

    <x-layout.footer></x-layout.footer>
</x-layout.link>
