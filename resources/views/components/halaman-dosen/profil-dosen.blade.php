@props(['user', 'publications' => collect(), 'hkis' => collect()])

@php
    $bio = collect(preg_split('/\R+/', (string) $user->bio))->map(fn ($p) => trim($p))->filter();
    $fields = collect(preg_split('/[,;\n]+/', (string) $user->bidang_penelitian))->map(fn ($p) => trim($p))->filter();
    $editOpen = $errors->any();
@endphp

<section class="sc-bio-hero">
    <div class="container">
        <div class="sc-bio-hero__grid">
            <div class="sc-bio-hero__photo">
                @if ($user->foto)
                    <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto {{ $user->fullname }}">
                @else
                    <span aria-hidden="true">{{ mb_strtoupper(mb_substr($user->fullname, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <h1 class="sc-bio-hero__name">{{ $user->fullname }}</h1>
                <p class="sc-bio-hero__role">{{ $user->prodi ?: 'Program studi belum diisi' }}</p>
                <p class="sc-bio-hero__unit">
                    <i class="bi bi-building" aria-hidden="true"></i>
                    {{ $user->fakultas ? $user->fakultas.', Telkom University' : 'Fakultas belum diisi' }}
                </p>
                <div class="prf-hero__actions">
                    <button type="button" class="sc-btn sc-btn--light" id="btnStartEdit" data-open="#profilEdit" @if ($editOpen) hidden @endif>
                        <i class="bi bi-pencil-fill" aria-hidden="true"></i> Edit Profil
                    </button>
                    <a href="{{ route('biografi.dosen', $user->id) }}" class="sc-btn sc-btn--outline-light">
                        <i class="bi bi-eye" aria-hidden="true"></i> Lihat Halaman Publik
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sc-section sc-bio">
    <div class="container">
        <div class="sc-panel prf-edit" id="profilEdit" @unless ($editOpen) hidden @endunless>
            <h2 class="sc-panel__title">Edit Profil</h2>

            @if ($errors->any())
                <div class="dsn-alert" role="alert">
                    <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                    <span>Periksa kembali isian yang ditandai merah.</span>
                </div>
            @endif

            <form action="{{ route('profil.dosen.update') }}" method="POST" enctype="multipart/form-data" id="profForm" novalidate data-validate>
                @csrf
                @method('PUT')

                <div class="prf-edit__grid">
                    <div class="prf-edit__photo">
                        <div class="prf-avatar" data-preview-box>
                            @if ($user->foto)
                                <img src="{{ asset('storage/'.$user->foto) }}" alt="Pratinjau foto profil" data-preview-img>
                            @else
                                <span data-preview-initial>{{ mb_strtoupper(mb_substr($user->fullname, 0, 1)) }}</span>
                            @endif
                        </div>
                        <label for="fotoInput" class="sc-btn sc-btn--ghost sc-btn--sm">
                            <i class="bi bi-camera" aria-hidden="true"></i> Ganti Foto
                        </label>
                        <input type="file" name="foto" id="fotoInput" class="visually-hidden" accept="image/jpeg,image/png,image/webp" data-preview-input>
                        <small class="form-text text-center">JPG, PNG, atau WEBP, maksimal 4 MB.</small>
                        <div class="field-error">@error('foto'){{ $message }}@enderror</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6" data-field>
                            <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="fullname" name="fullname" class="form-control @error('fullname') is-invalid @enderror"
                                value="{{ old('fullname', $user->fullname) }}" autocomplete="name"
                                data-label="Nama lengkap" data-rules="required|min:3|max:100|name">
                            <div class="field-error" data-error-for="fullname">@error('fullname'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-md-6" data-field>
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" autocomplete="email"
                                data-label="Email" data-rules="required|email|max:100">
                            <div class="field-error" data-error-for="email">@error('email'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-md-4" data-field>
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" id="nip" name="nip" inputmode="numeric" class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai"
                                data-label="NIP" data-rules="digits:4,30">
                            <div class="field-error" data-error-for="nip">@error('nip'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-md-4" data-field>
                            <label for="prodi" class="form-label">Program Studi</label>
                            <input type="text" id="prodi" name="prodi" class="form-control @error('prodi') is-invalid @enderror"
                                value="{{ old('prodi', $user->prodi) }}" placeholder="Contoh: Sistem Informasi"
                                data-label="Program studi" data-rules="min:2|max:100|safe">
                            <div class="field-error" data-error-for="prodi">@error('prodi'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-md-4" data-field>
                            <label for="fakultas" class="form-label">Fakultas</label>
                            <input type="text" id="fakultas" name="fakultas" class="form-control @error('fakultas') is-invalid @enderror"
                                value="{{ old('fakultas', $user->fakultas) }}" placeholder="Contoh: Fakultas Ilmu Terapan"
                                data-label="Fakultas" data-rules="min:2|max:100|safe">
                            <div class="field-error" data-error-for="fakultas">@error('fakultas'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-12" data-field>
                            <label for="bidang_penelitian" class="form-label">Bidang Penelitian</label>
                            <input type="text" id="bidang_penelitian" name="bidang_penelitian" class="form-control @error('bidang_penelitian') is-invalid @enderror"
                                value="{{ old('bidang_penelitian', $user->bidang_penelitian) }}" placeholder="Contoh: Internet of Things, Kecerdasan Buatan"
                                data-label="Bidang penelitian" data-rules="max:500|safe">
                            <div class="form-text">Pisahkan beberapa bidang dengan koma.</div>
                            <div class="field-error" data-error-for="bidang_penelitian">@error('bidang_penelitian'){{ $message }}@enderror</div>
                        </div>
                        <div class="col-12" data-field>
                            <label for="bio" class="form-label">Tentang Saya</label>
                            <textarea id="bio" name="bio" rows="5" class="form-control @error('bio') is-invalid @enderror"
                                placeholder="Ceritakan latar belakang, pengalaman, dan fokus riset Anda"
                                data-label="Biografi" data-rules="max:2000|safe">{{ old('bio', $user->bio) }}</textarea>
                            <div class="field-error" data-error-for="bio">@error('bio'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>

                <div class="dsn-form-actions">
                    <button type="submit" class="sc-btn sc-btn--primary">
                        <i class="bi bi-save-fill" aria-hidden="true"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profil.dosen') }}" class="sc-btn sc-btn--ghost">Batal</a>
                </div>
            </form>
        </div>

        <div class="sc-bio__grid">
            <aside class="sc-bio__aside">
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Informasi Akun</h2>
                    <dl class="prf-info">
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt>NIP</dt>
                            <dd>{{ $user->nip ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt>Peran</dt>
                            <dd><span class="dsn-pill">Dosen</span></dd>
                        </div>
                    </dl>
                </div>
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Bidang Penelitian</h2>
                    @if ($fields->isNotEmpty())
                        <ul class="sc-chips">
                            @foreach ($fields as $field)
                                <li>{{ $field }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="sc-panel__muted">Belum diisi. Klik Edit Profil untuk menambahkan.</p>
                    @endif
                </div>
            </aside>

            <div class="sc-bio__main">
                <div class="sc-panel">
                    <h2 class="sc-panel__title">Tentang Saya</h2>
                    @forelse ($bio as $paragraph)
                        <p class="sc-bio__text">{{ $paragraph }}</p>
                    @empty
                        <p class="sc-panel__muted">Belum ada deskripsi. Klik Edit Profil untuk menambahkan.</p>
                    @endforelse
                </div>

                <div class="sc-panel">
                    <div class="prf-panel-head">
                        <h2 class="sc-panel__title">Publikasi</h2>
                        <a href="{{ route('dosen.publikasi.index') }}" class="prf-link">Kelola <i class="bi bi-arrow-right-short" aria-hidden="true"></i></a>
                    </div>
                    @if ($publications->isNotEmpty())
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
                        <p class="sc-panel__muted">Belum ada publikasi yang dipublikasikan atas nama Anda.</p>
                    @endif
                </div>

                <div class="sc-panel">
                    <div class="prf-panel-head">
                        <h2 class="sc-panel__title">HKI</h2>
                        <a href="{{ route('dosen.hki.index') }}" class="prf-link">Kelola <i class="bi bi-arrow-right-short" aria-hidden="true"></i></a>
                    </div>
                    @if ($hkis->isNotEmpty())
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
                        <p class="sc-panel__muted">Belum ada HKI yang dipublikasikan atas nama Anda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
