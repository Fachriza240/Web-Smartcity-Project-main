@props(['user'])

{{-- ─────────────────────────────────────────────────────────
     STYLE — ikuti main.css (profile-header, card-publication, dsb)
     ───────────────────────────────────────────────────────── --}}
<style>
.prof-edit-bar {
    background: #fff8e1;
    border-bottom: 2px solid #ffd600;
    padding: 10px 0;
    position: sticky;
    top: 0;
    z-index: 999;
    display: none;
}
.prof-edit-bar.show { display: block; }
.prof-edit-bar .container {
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px;
}
.prof-edit-bar .edit-label {
    font-size: 13px; font-weight: 700; color: #b45309;
    display: flex; align-items: center; gap: 6px;
}
.prof-edit-bar .btn-save-bar {
    background: #22c55e; color: #fff;
    border: none; border-radius: 8px;
    padding: 8px 20px; font-weight: 700;
    font-size: 13px; cursor: pointer;
    transition: background .2s;
}
.prof-edit-bar .btn-save-bar:hover { background: #16a34a; }
.prof-edit-bar .btn-cancel-bar {
    background: transparent; color: #64748b;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    padding: 8px 16px; font-weight: 600;
    font-size: 13px; cursor: pointer;
    transition: all .2s;
}
.prof-edit-bar .btn-cancel-bar:hover { border-color: #94a3b8; color: #0f172a; }

/* avatar overlay */
.prof-avatar-container { position: relative; display: inline-block; }
.prof-avatar-overlay {
    position: absolute; inset: 0; border-radius: 50%;
    background: rgba(0,0,0,.45);
    display: none; align-items: center;
    justify-content: center; flex-direction: column;
    cursor: pointer; gap: 3px;
}
.edit-mode .prof-avatar-overlay { display: flex; }
.prof-avatar-overlay span {
    color: #fff; font-size: 11px; font-weight: 600;
    text-align: center; line-height: 1.2;
}
.prof-avatar-overlay i { font-size: 22px; color: #fff; }

/* inline edit field */
.edit-field {
    display: none;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.4);
    border-radius: 8px;
    color: #fff;
    padding: 6px 12px;
    font-size: inherit;
    font-family: inherit;
    width: 100%;
    margin-bottom: 6px;
    outline: none;
}
.edit-field::placeholder { color: rgba(255,255,255,.5); }
.edit-field:focus { border-color: #fff; background: rgba(255,255,255,.25); }
.edit-mode .edit-field { display: block; }
.edit-mode .view-text  { display: none; }

/* body edit field */
.body-edit-field {
    display: none;
    width: 100%;
    padding: 9px 13px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    resize: vertical;
}
.body-edit-field:focus {
    border-color: #4c8dc9;
    box-shadow: 0 0 0 3px rgba(76,141,201,.12);
    background: #fff;
}
.edit-mode .body-edit-field { display: block; }
.edit-mode .body-view-text  { display: none; }

/* section-title-profile sudah ada di main.css */
.edit-profile-btn {
    margin-top: 1rem;
    background: rgba(255,255,255,.15);
    border: 2px solid rgba(255,255,255,.5);
    color: #fff;
    padding: 8px 22px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.edit-profile-btn:hover { background: rgba(255,255,255,.28); }
.edit-profile-btn.hide-on-edit { }
.edit-mode .hide-on-edit { display: none !important; }

/* success alert */
.prof-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #16a34a;
    border-radius: 10px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}
</style>

{{-- ─── STICKY EDIT BAR ───────────────────────────────── --}}
<div class="prof-edit-bar" id="editBar">
    <div class="container">
        <div class="edit-label">
            <i class="bi bi-pencil-fill"></i>
            Mode Edit aktif — perubahan belum disimpan
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn-cancel-bar" onclick="cancelEdit()">
                Batal
            </button>
            <button type="submit" form="profForm" class="btn-save-bar">
                <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<form action="{{ route('profil.dosen.update') }}" method="POST"
      enctype="multipart/form-data" id="profForm">
@csrf
@method('PUT')

{{-- ─── PROFILE HEADER ────────────────────────────────── --}}
<div class="profile-header" id="profileHeader">
    <div class="container profile-header-content">

        @if(session('success'))
            <div class="prof-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="prof-success" style="background:#fef2f2;border-color:#fecaca;color:#dc2626;">
                <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
            </div>
        @endif

        <div class="row align-items-center">

            {{-- Foto --}}
            <div class="col-md-3 text-center">
                <div class="prof-avatar-container">
                    @if($user->foto)
                        <img src="{{ asset('storage/'.$user->foto) }}"
                             alt="{{ $user->fullname }}"
                             class="rounded-circle img-fluid profile-image"
                             id="avatarPreview">
                    @else
                        <div class="rounded-circle profile-image d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,.15);border:3px solid rgba(255,255,255,.3);"
                             id="avatarPreview">
                            <i class="bi bi-person-fill" style="font-size:3.5rem;color:rgba(255,255,255,.7);"></i>
                        </div>
                    @endif
                    <label for="fotoInput" class="prof-avatar-overlay" title="Ganti foto">
                        <i class="bi bi-camera-fill"></i>
                        <span>Ganti<br>Foto</span>
                    </label>
                        <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/png,image/gif,image/bmp,image/webp"
                           style="display:none;" onchange="previewFoto(this)">
                </div>
            </div>

            {{-- Info header --}}
            <div class="col-md-9">

                {{-- Nama --}}
                <h1 style="color:white;">
                    <span class="view-text">{{ $user->fullname }}</span>
                    <input type="text" name="fullname" class="edit-field"
                           value="{{ old('fullname', $user->fullname) }}"
                           placeholder="Nama Lengkap" required style="font-size:1.5rem;">
                </h1>

                {{-- Jabatan / Prodi --}}
                <h4 class="mb-2" style="color:white;">
                    <span class="view-text">{{ $user->prodi ?? 'Program Studi belum diisi' }}</span>
                    <input type="text" name="prodi" class="edit-field"
                           value="{{ old('prodi', $user->prodi) }}"
                           placeholder="Program Studi" style="font-size:1.1rem;">
                </h4>

                {{-- Fakultas --}}
                <p class="lead mb-0" style="color:white;">
                    <span class="view-text">
                        {{ $user->fakultas ? $user->fakultas . ' — Universitas Telkom' : 'Fakultas belum diisi' }}
                    </span>
                    <input type="text" name="fakultas" class="edit-field"
                           value="{{ old('fakultas', $user->fakultas) }}"
                           placeholder="Fakultas" style="font-size:1rem;">
                </p>

                {{-- Social icons (view) --}}
                <div class="mt-4 social-icons-profile view-text">
                    @if($user->email)
                        <a href="mailto:{{ $user->email }}" title="{{ $user->email }}">
                            <i class="fas fa-envelope"></i>
                        </a>
                    @endif
                </div>

                {{-- Edit tombol --}}
                <button type="button" onclick="startEdit()"
                        class="edit-profile-btn hide-on-edit" id="btnStartEdit">
                    <i class="bi bi-pencil-fill"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ─── MAIN CONTENT ───────────────────────────────────── --}}
<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div class="row">

        {{-- ── Kolom Kiri ──────────────────────────────── --}}
        <div class="col-lg-4 mb-4">

            {{-- Tentang Saya --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Tentang Saya</h3>

                    <div class="body-view-text">
                        @if($user->bio ?? null)
                            @foreach(explode("\n", $user->bio) as $para)
                                @if(trim($para))
                                    <p class="about-me">{{ $para }}</p>
                                @endif
                            @endforeach
                        @else
                            <p class="about-me text-muted">Belum ada deskripsi. Klik <em>Edit Profil</em> untuk menambahkan.</p>
                        @endif
                    </div>

                    <textarea name="bio" class="body-edit-field" rows="6"
                              placeholder="Tulis deskripsi singkat tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                </div>
            </div>

            {{-- Informasi Akun --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Informasi Akun</h3>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#888;">Email</strong>
                            <div class="body-view-text" style="font-size:14px;color:#333;">{{ $user->email }}</div>
                            <input type="email" name="email" class="body-edit-field mt-1"
                                   value="{{ old('email', $user->email) }}" required>
                        </li>
                        <li class="mb-3">
                            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#888;">NIP</strong>
                            <div class="body-view-text" style="font-size:14px;color:#333;">{{ $user->nip ?? '-' }}</div>
                            <input type="text" name="nip" class="body-edit-field mt-1"
                                   value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                        </li>
                        <li>
                            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#888;">Role</strong>
                            <div style="font-size:14px;color:#333;margin-top:4px;">
                                <span class="badge"
                                      style="background:#dbeafe;color:#1d4ed8;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                                    Dosen
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- ── Kolom Kanan ─────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- Bidang Penelitian --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Bidang Penelitian</h3>

                    <div class="body-view-text">
                        @if($user->bidang_penelitian ?? null)
                            <p class="research-interests mb-3">{{ $user->bidang_penelitian }}</p>
                        @else
                            <p class="text-muted" style="font-size:14px;">Belum diisi.</p>
                        @endif
                    </div>

                    <textarea name="bidang_penelitian" class="body-edit-field" rows="4"
                              placeholder="Deskripsikan bidang penelitian Anda...">{{ old('bidang_penelitian', $user->bidang_penelitian ?? '') }}</textarea>
                </div>
            </div>

            {{-- Publikasi dari DB --}}
            @php
                $myPubs = \App\Models\Publication::published()
                    ->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('penulis', 'like', '%'.$user->fullname.'%');
                    })
                    ->orderByDesc('tahun')
                    ->limit(6)
                    ->get();
            @endphp

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Publikasi Penelitian</h3>

                    <div class="row">
                    @forelse($myPubs as $pub)
                        <div class="col-md-6 mb-4">
                            <div class="card card-publication h-100">
                                <div class="card-body">
                                    <span class="publication-year">{{ $pub->tahun }}</span>
                                    <h5 class="publication-title">{{ $pub->judul }}</h5>
                                    <p class="journal-name">{{ $pub->penulis }}
                                        @if($pub->penerbit) · {{ $pub->penerbit }} @endif
                                    </p>
                                    <a href="{{ route('publications.show', $pub) }}"
                                       class="btn btn-sm btn-view" target="_blank">
                                        Lihat Publikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted" style="font-size:14px;">
                                Belum ada publikasi yang terdaftar atas nama Anda.
                            </p>
                        </div>
                    @endforelse
                    </div>

                    @if($myPubs->isNotEmpty())
                        <div class="text-center mt-2">
                            <a href="{{ route('publications.index') }}" class="btn btn-view">
                                Lihat Semua Publikasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- HKI dari DB --}}
            @php
                $myHkis = \App\Models\Hki::published()
                    ->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('pencipta', 'like', '%'.$user->fullname.'%');
                    })
                    ->orderByDesc('tgl_terbit')
                    ->limit(6)
                    ->get();
            @endphp

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">HKI</h3>

                    @forelse($myHkis as $hki)
                        <div class="publication-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="publication-year" style="font-size:13px;color:#4c8dc9;font-weight:700;">
                                        {{ $hki->jenis_sertifikat }}
                                    </div>
                                    <h5 class="publication-title mt-1">{{ $hki->judul_sertifikat }}</h5>
                                    <p class="journal-name mb-1">No. {{ $hki->nomor_sertifikat }}</p>
                                    <p class="journal-name">
                                        Terbit: {{ $hki->tgl_terbit?->format('d M Y') }}
                                    </p>
                                    @if($hki->file_sertifikat)
                                        <a href="{{ asset('storage/'.$hki->file_sertifikat) }}"
                                           target="_blank" class="btn btn-sm btn-view mt-1">
                                            Lihat Sertifikat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted" style="font-size:14px;">
                            Belum ada HKI yang terdaftar atas nama Anda.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

</form>

<script>
var _editMode = false;

function startEdit() {
    _editMode = true;
    document.getElementById('profileHeader').classList.add('edit-mode');
    document.getElementById('editBar').classList.add('show');

    // Body edit fields
    document.querySelectorAll('.body-edit-field').forEach(function(el) {
        el.style.display = 'block';
    });
    document.querySelectorAll('.body-view-text').forEach(function(el) {
        el.style.display = 'none';
    });

    // Scroll ke atas agar bar terlihat
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function cancelEdit() {
    window.location.reload();
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.getElementById('avatarPreview');
            if (wrap.tagName === 'IMG') {
                wrap.src = e.target.result;
            } else {
                // placeholder div — ganti dengan img
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.id = 'avatarPreview';
                img.className = 'rounded-circle img-fluid profile-image';
                wrap.parentNode.replaceChild(img, wrap);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Jika ada error validasi, langsung buka mode edit
@if($errors->any())
document.addEventListener('DOMContentLoaded', startEdit);
@endif
</script>
