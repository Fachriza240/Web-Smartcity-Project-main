# Konteks Project: Website CoE Smart City — Universitas Telkom

## Identitas Project

**Nama:** Website Center of Excellence (CoE) Smart City  
**Institusi:** Universitas Telkom  
**Deskripsi:** Platform informasi dan layanan untuk CoE Smart City — unit strategis yang mempercepat riset, inovasi, bisnis, dan layanan masyarakat di bidang ilmu pengetahuan, teknologi, manajemen, dan seni.  
**Framework:** Laravel (PHP)  
**Database:** MySQL  
**Autentikasi:** Laravel built-in session-based auth (tanpa Sanctum/Passport)

---

## Stack Teknologi

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel, PHP |
| Templating | Blade + Blade Components |
| Styling | Bootstrap 5, CSS kustom (`public/css/main.css`) |
| Font Heading | Spline Sans |
| Font Body | Lato |
| CSS Utama | `--primary-blue: #4c8dc9` |
| Build Tool | Vite (tanpa Vue/React) |
| Database | MySQL |

### Aturan Desain yang Wajib Konsisten
- **Warna utama:** `--primary-blue: #4c8dc9` (biru Smart City)
- **Font heading (h1–h6, nav-link):** `"Spline Sans", sans-serif`
- **Font body/paragraf:** `"Lato", sans-serif`
- **Warna teks heading:** `--text-black: #000000`
- **Warna teks paragraf:** `--text-gray: #7b7b7b`
- **Background:** `--bg-white: #ffffff`
- **Lebar sidebar admin:** `--sidebar-width: 280px`
- Seluruh teks web menggunakan **Bahasa Indonesia** — tidak ada teks Bahasa Inggris pada antarmuka pengguna
- Desain halaman baru harus konsisten dengan gaya halaman Beranda yang sudah ada

---

## Struktur Folder Kunci

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          ← login, register, logout
│   │   ├── AboutController.php
│   │   ├── NewsController.php          ← halaman publik berita
│   │   ├── ProgramController.php       ← halaman publik program
│   │   ├── ProjectController.php       ← halaman publik proyek
│   │   ├── PublicationController.php   ← halaman publik publikasi
│   │   ├── TeamController.php          ← halaman publik tim
│   │   ├── ProfileController.php       ← (dikomentari, belum aktif)
│   │   └── Admin/
│   │       ├── ValidationController.php   ← approve/reject dosen
│   │       ├── NewsController.php
│   │       ├── ProgramController.php
│   │       ├── ProjectController.php
│   │       ├── PublicationController.php
│   │       ├── TeamController.php
│   │       ├── PartnerController.php
│   │       └── SettingsController.php
│   └── Middleware/
│       └── EnsureUserApproved.php      ← blokir dosen yang belum approved
├── Models/
│   ├── User.php
│   ├── News.php
│   ├── Program.php
│   ├── Project.php
│   ├── Publication.php
│   ├── Team.php
│   └── Partner.php

resources/views/
├── authorized/              ← halaman login & registrasi (wrapper)
├── components/
│   ├── layout/              ← navbar, footer, link (layout utama)
│   ├── halaman-user/        ← komponen section halaman publik
│   ├── halaman-dosen/       ← komponen section halaman dosen
│   ├── halaman-admin/       ← komponen section dashboard admin lama
│   └── halaman-creator/     ← komponen section dashboard content creator
├── halaman-user/            ← page wrapper user (memanggil komponen)
├── halaman-dosen/           ← page wrapper dosen
├── halaman-admin/           ← page wrapper admin
├── halaman-creator/         ← page wrapper content creator
└── admin/                   ← view CRUD admin baru (news, programs, dll)

public/
├── css/
│   ├── main.css             ← CSS global untuk halaman publik & dosen
│   └── admin.css            ← CSS khusus panel admin
└── img/
    ├── logosc.png           ← logo Smart City
    ├── fotoaboutus.jpg      ← foto tim untuk hero
    ├── fotosukses.png       ← foto alternatif hero
    └── ...
```

---

## Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| fullname | string | Nama lengkap |
| email | string | Email unik |
| nip | string | NIP/ID pegawai, unik |
| password | string | Di-hash otomatis oleh model |
| role | enum | `admin`, `dosen`, `content_creator` |
| foto | string | Path foto profil (nullable) |
| registration_status | enum | `pending`, `approved`, `rejected` |
| timestamps | | created_at, updated_at |

**Konstanta di `User.php`:**
- `User::STATUS_PENDING` = `'pending'`
- `User::STATUS_APPROVED` = `'approved'`
- `User::STATUS_REJECTED` = `'rejected'`

### Tabel `dosen`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| dosen_id | FK → users.id | Relasi ke akun user dosen |
| nama | string | |
| jabatan | string | |
| lokasi | string | |
| website | string | |
| foto | string | |
| bidang_keahlian | text | |
| kelompok_riset | text | |
| publikasi_penelitian | longText | Data lama (tidak lagi dipakai aktif) |
| publikasi_pengabdian | longText | Data lama (tidak lagi dipakai aktif) |

### Tabel `publications`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | |
| judul | string | |
| penulis | string | Nama penulis (teks bebas, bukan FK ke dosen) |
| tahun | year | |
| abstrak | text | |
| kategori | enum | `Jurnal`, `Conference`, `Buku`, `Paten`, `Laporan Penelitian` |
| penerbit | string | nullable |
| doi | string | nullable |
| pdf_path | string | Path file PDF |
| thumbnail_path | string | nullable |
| status | enum | `Draft`, `Publish` |

### Tabel `news`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | |
| title | string | |
| slug | string | Unik, untuk URL |
| excerpt | text | Ringkasan |
| content | longText | Isi berita |
| image | string | nullable |
| category | string | nullable |
| tags | string | nullable |
| status | enum | `draft`, `published` |
| published_at | timestamp | nullable |

### Tabel `programs`, `projects`, `teams`, `partners`
Masing-masing memiliki kolom dasar: `id`, konten relevan (judul/nama, deskripsi, foto/logo), timestamps.

---

## Sistem Role

### 1. Admin
- **Dibuat oleh:** Developer, lewat seeder atau database langsung
- **Login:** `/login`
- **Dashboard:** `/beranda-admin`
- **Hak akses:**
  - Kendali penuh atas seluruh sistem
  - Satu-satunya role yang dapat approve/reject pendaftar dosen lewat `/admin/validasi-registrasi`
  - CRUD semua modul: Publikasi, Proyek, Berita, Program, Tim, Mitra
  - Akses ke Settings (`/admin/settings`)
- **Dibuat via:** Seeder/database secara manual oleh developer, tidak lewat form registrasi publik

### 2. Dosen
- **Dibuat oleh:** Mendaftar sendiri lewat `/registrasi`
- **Alur pendaftaran:**
  1. Dosen mengisi form registrasi → role otomatis `dosen`, status `pending`
  2. Admin melihat daftar pendaftar di `/admin/validasi-registrasi`
  3. Admin klik **Approve** → status jadi `approved` → dosen bisa login
  4. Admin klik **Reject** → status jadi `rejected` → dosen tidak bisa login
  5. Jika dosen login saat masih `pending` atau `rejected`, langsung diblokir di `AuthController@login` dan dikembalikan ke halaman login dengan pesan error
- **Login:** `/login`
- **Dashboard:** `/beranda-dosen`
- **Hak akses:** Melihat semua halaman konten versi dosen, mengisi/mengelola profil pribadi (ProfileController sedang dikomentari, belum aktif)
- **Middleware:** `EnsureUserApproved` — memastikan role adalah `dosen` dan status adalah `approved`; jika tidak, redirect ke `/dosen/status`

### 3. Content Creator
- **Dibuat oleh:** Developer secara manual (seeder/tinker/database langsung). Saat ini tidak ada form registrasi publik untuk role ini
- **Login:** `/login`
- **Dashboard:** `/beranda-creator`
- **Hak akses:** CRUD konten (Publikasi, Proyek, Berita, Program, Tim, Mitra) — sama seperti admin untuk manajemen konten, tetapi tidak bisa approve/reject user dan tidak bisa akses Settings
- **Catatan:** Diperuntukkan untuk anak magang yang bertugas mengisi konten website

---

## Alur Autentikasi & Redirect

```
/registrasi  →  form (hanya dosen) → status: pending → redirect /login
/login       →  cek role + status:
                ├── role: admin                          → /beranda-admin
                ├── role: dosen + status: approved       → /beranda-dosen
                ├── role: dosen + status: pending        → BLOKIR, kembali ke /login
                ├── role: dosen + status: rejected       → BLOKIR, kembali ke /login
                └── role: content_creator                → /beranda-creator
```

### Middleware `EnsureUserApproved`
Diterapkan pada semua route dosen (kecuali `/dosen/status`). Logika:
1. Jika belum login → redirect `/login`
2. Jika role bukan `dosen` → abort 403
3. Jika `registration_status` bukan `approved` → redirect `/dosen/status`

---

## Peta Halaman & Route

### Halaman Publik (Tanpa Login)
| Route | Controller | Keterangan |
|---|---|---|
| `/` | closure → view | Beranda user |
| `/about-user` | `AboutController@userIndex` | Tentang |
| `/program-user` | `ProgramController@index` | Program |
| `/project-user` | `ProjectController@index` | Proyek |
| `/publication-user` | `PublicationController@index` | Publikasi |
| `/publications/{pub}` | `PublicationController@show` | Detail publikasi |
| `/publications/{pub}/download` | `PublicationController@download` | Unduh PDF |
| `/news-user` | `NewsController@index` | Berita |
| `/news/{news:slug}` | `NewsController@show` | Detail berita |
| `/team-user` | `TeamController@index` | Tim |
| `/biografi-user` | closure → view | Biografi dosen |

### Halaman Dosen (Login + Approved)
| Route | Keterangan |
|---|---|
| `/beranda-dosen` | Dashboard dosen |
| `/about-dosen` | Tentang (versi dosen) |
| `/program-dosen` | Program (versi dosen) |
| `/project-dosen` | Proyek (versi dosen) |
| `/news-dosen` | Berita (versi dosen) |
| `/team-dosen` | Tim (versi dosen) |
| `/profil-dosen` | Profil pribadi dosen |
| `/biografi-dosen` | Biografi (versi dosen) |
| `/dosen/status` | Halaman tunggu (jika pending/rejected) |

### Panel Admin (Login + role: admin)
| Route | Keterangan |
|---|---|
| `/beranda-admin` | Dashboard admin |
| `/admin/validasi-registrasi` | Approve/reject pendaftar dosen |
| `/admin/publications` | CRUD Publikasi |
| `/admin/projects` | CRUD Proyek |
| `/admin/news` | CRUD Berita |
| `/admin/programs` | CRUD Program |
| `/admin/teams` | CRUD Tim |
| `/admin/partners` | CRUD Mitra |
| `/admin/settings` | Settings |

### Panel Content Creator (Login + role: content_creator)
| Route | Keterangan |
|---|---|
| `/beranda-creator` | Dashboard content creator |
| `/admin/*` (CRUD) | Akses CRUD konten (sama seperti admin, kecuali validasi & settings) |

---

## Cara Kerja View (Blade Components)

Setiap halaman menggunakan dua lapis file:

**1. Page Wrapper** (di `resources/views/halaman-{role}/`)  
File tipis yang hanya memanggil komponen:
```blade
{{-- resources/views/halaman-user/beranda-user.blade.php --}}
<x-layout.link>
    <x-layout.navbar></x-layout.navbar>
    <x-layout.home></x-layout.home>
    <x-layout.footer></x-layout.footer>
</x-layout.link>
```

**2. Komponen** (di `resources/views/components/halaman-{role}/`)  
File berisi HTML/CSS aktual dari section tersebut.

**Layout utama** (`<x-layout.link>`) memuat:
- Bootstrap 5 CSS & JS
- Font Awesome
- Bootstrap Icons
- AOS (scroll animation)
- CSS file (`main.css` atau `admin.css`)
- JS file (`main.js`)

---

## Konvensi Penamaan File View

| Pola | Contoh | Keterangan |
|---|---|---|
| `halaman-user/nama-user.blade.php` | `halaman-user/news-user.blade.php` | Page wrapper publik |
| `halaman-dosen/nama-dosen.blade.php` | `halaman-dosen/news-dosen.blade.php` | Page wrapper dosen |
| `halaman-admin/nama-admin.blade.php` | `halaman-admin/beranda-admin.blade.php` | Page wrapper admin |
| `components/halaman-user/nama-user.blade.php` | `components/halaman-user/news-user.blade.php` | Komponen isi user |
| `components/halaman-dosen/nama-dosen.blade.php` | | Komponen isi dosen |
| `components/halaman-admin/nama-admin.blade.php` | | Komponen isi admin |
| `admin/modul/index.blade.php` | `admin/news/index.blade.php` | View CRUD admin baru |

---

## CSS — Cara Penulisan yang Dipakai Project Ini

Project ini menggunakan tiga lapisan CSS secara bersamaan:

1. **External CSS (`public/css/main.css`)** — style global yang berlaku di semua halaman publik dan dosen. Berisi variabel warna, font, navbar, about-section, cards, gallery, footer, scroll-top, dll. Ini yang paling utama untuk style reusable.

2. **Bootstrap 5** — juga External CSS (dari CDN). Dipakai lewat class langsung di HTML: `container`, `row`, `col-lg-6`, `d-flex`, `btn`, dll. Tidak perlu tulis CSS manual untuk layout dan utilitas umum.

3. **Internal CSS (`<style>` tag di dalam file blade)** — dipakai untuk style khusus section baru yang belum ada di `main.css`. Jika makin berkembang, pindahkan ke `main.css`.

Inline CSS (`style="..."` langsung di tag HTML) dihindari — hanya untuk kondisi darurat seperti fallback foto (`onerror`).

---

## Hal Penting untuk Diketahui AI

1. **Saat membuat halaman baru**, wajib:
   - Ikuti pola dua lapis (page wrapper + komponen)
   - Gunakan variabel CSS dari `main.css` (`--primary-blue`, dll.)
   - Font: Spline Sans untuk heading, Lato untuk body
   - Seluruh teks antarmuka dalam **Bahasa Indonesia**
   - Konsisten secara visual dengan halaman Beranda yang sudah ada

2. **Saat membuat route baru**:
   - Halaman publik: tidak perlu middleware
   - Halaman dosen: tambahkan `->middleware(\App\Http\Middleware\EnsureUserApproved::class)`
   - Halaman admin: tambahkan pengecekan `Auth::user()->role !== 'admin'` → abort 403
   - Halaman content creator: tambahkan pengecekan `Auth::user()->role !== 'content_creator'` → abort 403
   - Route CRUD admin berada di prefix `/admin` dengan name prefix `admin.`

3. **Registrasi publik** hanya untuk role `dosen`. Role `admin` dan `content_creator` dibuat manual oleh developer.

4. **Pengecekan role** dilakukan secara inline di masing-masing route (closure) atau di controller menggunakan `Auth::user()->role`. Belum ada RoleMiddleware terpusat.

5. **Tabel `publications`** menggunakan kolom `penulis` sebagai teks bebas (bukan foreign key ke dosen). Ini kondisi sementara yang disengaja.

6. **ProfileController** saat ini dikomentari — fitur edit profil dosen belum aktif.
