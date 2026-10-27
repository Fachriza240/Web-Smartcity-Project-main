# Website CoE Smart City - Universitas Telkom

Aplikasi web Center of Excellence Smart City Telkom University. Menampilkan profil, program, proyek, berita, mitra, tim, publikasi, dan HKI, dilengkapi Sistem Pendukung Keputusan (DSS) metode AHP dan SAW untuk memetakan potensi dosen.

Teknologi: Laravel 12, PHP 8.2 atau lebih baru, MySQL, Bootstrap, JavaScript.

## Peran pengguna

- Pengunjung: melihat halaman publik dan mencari konten.
- Dosen: registrasi (divalidasi admin), mengelola publikasi, HKI, dan aktivitas sendiri, serta melihat analisis kompetensi pribadi.
- Content creator: mengelola konten situs.
- Admin: validasi registrasi, kelola seluruh konten, pembobotan AHP, dan hasil penilaian DSS.

## Instalasi

    composer install
    cp .env.example .env
    php artisan key:generate

Atur koneksi database di `.env`, lalu:

    php artisan migrate
    php artisan db:seed
    php artisan storage:link
    php artisan serve

Di environment production, `php artisan db:seed` sengaja dilewati. Isi kriteria DSS dengan:

    php artisan db:seed --class=DssKriteriaSeeder

Akun contoh ada di `database/seeders/AkunSeeder.php`.

## Sistem Pendukung Keputusan (DSS)

1. Admin menentukan bobot kriteria lewat menu Pembobotan AHP. Bobot hanya bisa disimpan jika Consistency Ratio tidak melebihi 0,1.
2. Dosen mengisi publikasi, HKI, dan aktivitas (penelitian, abdimas, produk, kolaborasi, kepemimpinan).
3. Admin menekan Hitung Ulang di menu Hasil Penilaian, atau menjalankan:

        php artisan dss:hitung --tampilkan

Aturan poin, ambang kategori, dan kata kunci dimensi Smart City ada di `config/dss.php`. Skor SAW bersifat relatif terhadap dosen terbaik, jadi ambang `kategori` perlu dikalibrasi mengikuti sebaran skor pada data asli. Setelah mengubah config jalankan `php artisan config:clear`.

Data demo (dosen fiktif, hanya untuk lokal atau database salinan):

    php artisan db:seed --class=DssDemoSeeder

Hapus data demo lewat `php artisan tinker`:

    Database\Seeders\DssDemoSeeder::bersihkan();

## Pengujian

    php artisan test