<section style="padding:100px 0 80px;background:#f8fafc;">
    <div class="container">
        <div style="text-align:center;margin-bottom:48px;">
            <span style="display:inline-block;background:rgba(76,141,201,.1);color:#4c8dc9;
                         font-size:12px;font-weight:700;letter-spacing:2px;padding:6px 16px;
                         border-radius:20px;margin-bottom:16px;">KONTAK KAMI</span>
            <h1 style="font-size:32px;font-weight:800;color:#0f172a;margin:0 0 12px;">
                Hubungi Kami
            </h1>
            <p style="color:#64748b;max-width:520px;margin:0 auto;">
                Ada pertanyaan, kerja sama, atau masukan seputar Smart City? Silakan hubungi kami melalui kontak di bawah ini.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;max-width:900px;margin:0 auto;">

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 24px;text-align:center;">
                <i class="fas fa-map-marker-alt" style="font-size:28px;color:#4c8dc9;margin-bottom:12px;"></i>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 8px;">Alamat</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">
                    Fakultas Ilmu Terapan Lt. 1, Telkom University,<br>
                    Jl. Telekomunikasi No.1, Sukapura, Kec. Dayeuhkolot,<br>
                    Kabupaten Bandung, Jawa Barat 40257
                </p>
            </div>

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 24px;text-align:center;">
                <i class="fas fa-envelope" style="font-size:28px;color:#4c8dc9;margin-bottom:12px;"></i>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 8px;">Email</h3>
                <a href="mailto:innegartina@gmail.com" style="color:#64748b;font-size:14px;text-decoration:none;">
                    innegartina@gmail.com
                </a>
            </div>

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 24px;text-align:center;">
                <i class="fas fa-phone-alt" style="font-size:28px;color:#4c8dc9;margin-bottom:12px;"></i>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 8px;">Telepon / WhatsApp</h3>
                <a href="https://wa.me/6281392329432" target="_blank" style="color:#64748b;font-size:14px;text-decoration:none;">
                    0813-9232-9432 (Admin CoE)
                </a>
            </div>

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 24px;text-align:center;">
                <i class="fas fa-share-alt" style="font-size:28px;color:#4c8dc9;margin-bottom:12px;"></i>
                <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 12px;">Media Sosial</h3>
                <div style="display:flex;justify-content:center;gap:14px;">
                    <a href="https://www.instagram.com/telu.smartcity?stkn=MTlyejA4b201N2NrcQ=="
                       target="_blank" style="color:#4c8dc9;font-size:18px;"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@telu.smartcity?_r=1&_t=ZS-99iTts1toUn"
                       target="_blank" style="color:#4c8dc9;font-size:18px;"><i class="fab fa-tiktok"></i></a>
                    {{-- Link YouTube belum tersedia, ikon tetap ditampilkan --}}
                    <a href="#" style="color:#4c8dc9;font-size:18px;"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

        </div>

        {{-- Notifikasi sukses/gagal --}}
        @if(session('success'))
            <div style="max-width:700px;margin:32px auto 0;background:#dcfce7;color:#16a34a;
                        border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-size:14px;">
                <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Form Kirim Pesan --}}
        <div style="max-width:700px;margin:48px auto 0;background:#fff;border:1px solid #e2e8f0;
                    border-radius:14px;padding:32px 28px;">
            <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 6px;">Kirim Pesan</h2>
            <p style="color:#64748b;font-size:14px;margin:0 0 24px;">
                Punya pertanyaan atau ingin berdiskusi kerja sama? Isi formulir di bawah ini.
            </p>

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div style="margin-bottom:18px;">
                    <label for="nama" style="display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap Anda"
                           style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('nama') ? '#f87171' : '#d0dff0' }};
                                  border-radius:8px;font-size:14px;outline:none;">
                    @error('nama')
                        <div style="color:#dc2626;font-size:12.5px;margin-top:5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:18px;">
                    <label for="email" style="display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('email') ? '#f87171' : '#d0dff0' }};
                                  border-radius:8px;font-size:14px;outline:none;">
                    @error('email')
                        <div style="color:#dc2626;font-size:12.5px;margin-top:5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:18px;">
                    <label for="subjek" style="display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">
                        Subjek
                    </label>
                    <input type="text" id="subjek" name="subjek" value="{{ old('subjek') }}"
                           placeholder="Topik pesan Anda"
                           style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('subjek') ? '#f87171' : '#d0dff0' }};
                                  border-radius:8px;font-size:14px;outline:none;">
                    @error('subjek')
                        <div style="color:#dc2626;font-size:12.5px;margin-top:5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom:24px;">
                    <label for="pesan" style="display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">
                        Pesan
                    </label>
                    <textarea id="pesan" name="pesan" rows="5"
                              placeholder="Tulis pesan Anda di sini..."
                              style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('pesan') ? '#f87171' : '#d0dff0' }};
                                     border-radius:8px;font-size:14px;outline:none;resize:vertical;">{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <div style="color:#dc2626;font-size:12.5px;margin-top:5px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                        style="background:#4c8dc9;color:#fff;border:none;border-radius:8px;
                               padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;">
                    <i class="fas fa-paper-plane" style="margin-right:8px;"></i>Kirim Pesan
                </button>
            </form>
        </div>

        <div style="max-width:900px;margin:48px auto 0;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d308.19127022138946!2d107.6324650888854!3d-6.972871838821734!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9adee34340b%3A0x9e4b78b59e959180!2sUnit%20Laboratorium%20Fakultas%20Ilmu%20Terapan%2C%20Universitas%20Telkom!5e1!3m2!1sid!2sid!4v1789373187360!5m2!1sid!2sid"
                width="100%" height="320" style="border:0;display:block;"
                allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</section>