<?php

namespace Database\Seeders;

use App\Models\Hki;
use App\Models\News;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Team;
use App\Models\User;
use App\Services\HkiNotifier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KontenSeeder extends Seeder
{
    private const WARNA = [
        ['#0b3d91', '#4c8dc9'],
        ['#0f766e', '#5eead4'],
        ['#5b21b6', '#a78bfa'],
        ['#9a3412', '#fdba74'],
        ['#9f1239', '#fda4af'],
        ['#1e293b', '#64748b'],
    ];

    private const CATATAN = 'Dokumen contoh ini dibuat otomatis oleh seeder untuk keperluan pengujian website CoE Smart City.';

    private int $nomorGambar = 0;

    public function run(): void
    {
        [$admin, $dosenA, $dosenB] = $this->pengguna();

        $jumlah = [
            'program' => $this->program(),
            'proyek' => $this->proyek(),
            'berita' => $this->berita(),
            'mitra' => $this->mitra(),
            'anggota tim' => $this->tim(),
            'publikasi' => $this->publikasi($dosenA, $dosenB),
            'HKI' => $this->hki($admin, $dosenA, $dosenB),
        ];

        $this->command?->info('Konten contoh siap: '.collect($jumlah)->map(fn (int $total, string $jenis) => "{$total} {$jenis}")->implode(', ').'.');
    }

    private function pengguna(): array
    {
        $cari = fn () => array_map(
            fn (string $email) => User::query()->where(User::emailCredential($email))->first(),
            ['admin@smartcity.ac.id', 'dosenA@smartcity.ac.id', 'dosenB@smartcity.ac.id']
        );

        $pengguna = $cari();

        if (in_array(null, $pengguna, true)) {
            $this->call(AkunSeeder::class);
            $pengguna = $cari();
        }

        return $pengguna;
    }

    private function program(): int
    {
        $daftar = [
            [
                'judul' => 'Program Pelatihan Smart City untuk ASN',
                'status' => Program::STATUS_PUBLISH,
                'deskripsi' => 'Pelatihan berjenjang bagi aparatur sipil negara tentang perencanaan, penerapan, dan evaluasi layanan kota cerdas, mulai dari tata kelola data hingga pemanfaatan dasbor kinerja kota.',
            ],
            [
                'judul' => 'Program Riset Kolaboratif Smart Mobility',
                'status' => Program::STATUS_DRAFT,
                'deskripsi' => 'Kolaborasi riset transportasi cerdas bersama pemerintah kota untuk memetakan pola kemacetan dan merancang rekayasa lalu lintas berbasis data. Program masih dalam tahap perencanaan.',
            ],
            [
                'judul' => 'Inkubasi Startup Teknologi Perkotaan',
                'status' => Program::STATUS_PUBLISH,
                'deskripsi' => 'Pendampingan bagi tim mahasiswa dan alumni yang mengembangkan produk teknologi untuk masalah perkotaan, meliputi validasi ide, pengembangan purwarupa, hingga temu bisnis dengan mitra pemerintah daerah.',
            ],
            [
                'judul' => 'Smart Campus Living Lab',
                'status' => Program::STATUS_PUBLISH,
                'deskripsi' => 'Lingkungan kampus dimanfaatkan sebagai laboratorium hidup untuk menguji sensor lingkungan, manajemen energi gedung, dan layanan parkir pintar sebelum diterapkan pada skala kota.',
            ],
            [
                'judul' => 'Literasi Data untuk Pemerintah Daerah',
                'status' => Program::STATUS_PUBLISH,
                'deskripsi' => 'Lokakarya dan pendampingan bagi perangkat daerah dalam mengelola, menganalisis, dan memvisualisasikan data sektoral sebagai dasar pengambilan keputusan yang lebih tepat sasaran.',
            ],
        ];

        foreach ($daftar as $urutan => $item) {
            Program::updateOrCreate(['judul' => $item['judul']], [
                'deskripsi' => $item['deskripsi'],
                'urutan' => $urutan + 1,
                'status' => $item['status'],
                'thumbnail_path' => $this->gambar('programs/thumbnails/contoh-'.Str::slug($item['judul']).'.svg'),
            ]);
        }

        return count($daftar);
    }

    private function proyek(): int
    {
        $daftar = [
            [
                'judul' => 'Sistem Monitoring Lalu Lintas Cerdas',
                'kategori' => 'Smart Mobility',
                'partner' => 'Dinas Komunikasi dan Informatika',
                'tahun' => 2024,
                'status' => Project::STATUS_PUBLISH,
                'galeri' => 3,
                'dokumen' => true,
                'deskripsi' => 'Implementasi sensor dan kamera berbasis kecerdasan buatan untuk memantau kepadatan lalu lintas di persimpangan utama kota. Data ditampilkan secara langsung pada dasbor pusat kendali sehingga petugas dapat mengatur siklus lampu lalu lintas dengan lebih cepat.',
            ],
            [
                'judul' => 'Prototipe Smart Waste Management',
                'kategori' => 'Smart Environment',
                'partner' => 'PT Teknologi Kota Cerdas',
                'tahun' => 2025,
                'status' => Project::STATUS_DRAFT,
                'galeri' => 0,
                'dokumen' => false,
                'deskripsi' => 'Pengembangan prototipe tempat sampah pintar dengan sensor kapasitas yang mengirimkan pemberitahuan ketika hampir penuh. Proyek masih dalam tahap pengujian internal.',
            ],
            [
                'judul' => 'Dasbor Kualitas Udara Kota',
                'kategori' => 'Smart Environment',
                'partner' => 'Dinas Lingkungan Hidup Kota',
                'tahun' => 2025,
                'status' => Project::STATUS_PUBLISH,
                'galeri' => 0,
                'dokumen' => true,
                'deskripsi' => 'Jaringan sensor kualitas udara yang dipasang di sejumlah titik strategis untuk mengukur partikel debu, karbon monoksida, suhu, dan kelembapan. Hasil pengukuran ditampilkan dalam peta interaktif yang dapat diakses masyarakat.',
            ],
            [
                'judul' => 'Aplikasi Pelaporan Warga Terpadu',
                'kategori' => 'Smart Governance',
                'partner' => 'Dinas Komunikasi dan Informatika',
                'tahun' => 2023,
                'status' => Project::STATUS_PUBLISH,
                'galeri' => 0,
                'dokumen' => false,
                'deskripsi' => 'Aplikasi seluler yang memudahkan warga melaporkan jalan rusak, lampu jalan padam, dan sampah menumpuk lengkap dengan foto serta lokasi. Laporan diteruskan otomatis ke perangkat daerah yang berwenang.',
            ],
            [
                'judul' => 'Penerangan Jalan Umum Berbasis IoT',
                'kategori' => 'Smart Energy',
                'partner' => 'PT Teknologi Kota Cerdas',
                'tahun' => 2026,
                'status' => Project::STATUS_PUBLISH,
                'galeri' => 0,
                'dokumen' => false,
                'deskripsi' => 'Lampu penerangan jalan yang dapat diatur tingkat kecerahannya sesuai kepadatan aktivitas di sekitarnya untuk menghemat energi. Setiap lampu melaporkan kondisi dan konsumsi listriknya ke server pusat.',
            ],
            [
                'judul' => 'Peta Digital Fasilitas Publik',
                'kategori' => 'Smart Living',
                'partner' => 'Badan Perencanaan Pembangunan Daerah Kota',
                'tahun' => 2022,
                'status' => Project::STATUS_PUBLISH,
                'galeri' => 0,
                'dokumen' => false,
                'deskripsi' => 'Pemetaan sekolah, puskesmas, taman, dan halte ke dalam satu peta digital yang diperbarui berkala. Peta membantu warga menemukan layanan terdekat dan membantu pemerintah merencanakan pembangunan fasilitas baru.',
            ],
        ];

        foreach ($daftar as $item) {
            $slug = Str::slug($item['judul']);

            Project::updateOrCreate(['judul' => $item['judul']], [
                'deskripsi' => $item['deskripsi'],
                'kategori' => $item['kategori'],
                'partner' => $item['partner'],
                'tahun' => $item['tahun'],
                'status' => $item['status'],
                'thumbnail_path' => $this->gambar("projects/thumbnails/contoh-{$slug}.svg"),
                'gallery_paths' => $item['galeri'] > 0
                    ? array_map(fn (int $nomor) => $this->gambar("projects/gallery/contoh-{$slug}-{$nomor}.svg"), range(1, $item['galeri']))
                    : null,
                'dokumen_path' => $item['dokumen']
                    ? $this->pdf("projects/documents/contoh-{$slug}.pdf", $item['judul'], [
                        'Dokumen Proyek CoE Smart City',
                        'Kategori: '.$item['kategori'],
                        'Mitra: '.$item['partner'],
                        'Tahun: '.$item['tahun'],
                        '',
                        $item['deskripsi'],
                        '',
                        self::CATATAN,
                    ])
                    : null,
            ]);
        }

        return count($daftar);
    }

    private function berita(): int
    {
        $daftar = [
            [
                'judul' => 'Peluncuran Portal Smart City Kota',
                'kategori' => 'Pengumuman',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2026-09-15 09:00:00',
                'konten' => "Pemerintah kota bersama CoE Smart City resmi meluncurkan portal layanan digital terpadu. Melalui portal ini, warga dapat mengakses informasi layanan publik, memantau kualitas udara, dan menyampaikan laporan dalam satu tempat.\n\nPortal dikembangkan secara bertahap. Pada tahap awal tersedia layanan informasi lalu lintas, peta fasilitas publik, dan kanal pengaduan warga yang terhubung langsung dengan perangkat daerah.",
            ],
            [
                'judul' => 'Workshop Internet of Things bagi Dosen dan Mahasiswa',
                'kategori' => 'Kegiatan',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2026-08-20 13:00:00',
                'konten' => "Sebanyak lima puluh peserta dari kalangan dosen dan mahasiswa mengikuti workshop Internet of Things di laboratorium CoE Smart City. Peserta belajar merakit perangkat sensor sederhana dan mengirimkan data ke platform pemantauan.\n\nKegiatan ini menjadi bekal awal bagi tim riset yang akan terlibat dalam proyek pemantauan lingkungan kampus.",
            ],
            [
                'judul' => 'Draft Berita: Rencana Kerja Sama Riset 2026',
                'kategori' => 'Riset',
                'status' => News::STATUS_DRAFT,
                'tanggal' => null,
                'konten' => 'Draft internal mengenai rencana kerja sama riset dengan mitra baru. Berita ini belum dipublikasikan ke halaman publik.',
            ],
            [
                'judul' => 'CoE Smart City Raih Hibah Riset Kota Berkelanjutan',
                'kategori' => 'Riset',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2026-07-10 10:00:00',
                'konten' => "Tim peneliti CoE Smart City memperoleh hibah riset untuk mengembangkan model perencanaan kota berkelanjutan. Riset akan berlangsung selama dua tahun dan melibatkan dosen lintas program studi.\n\nFokus utama riset adalah pemanfaatan data sensor dan citra satelit untuk memetakan ruang terbuka hijau serta potensi genangan di kawasan permukiman.",
            ],
            [
                'judul' => 'Uji Coba Sensor Peringatan Dini Banjir di Lingkungan Kampus',
                'kategori' => 'Teknologi',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2026-05-22 08:30:00',
                'konten' => "Sensor ketinggian air mulai diuji coba pada saluran drainase utama kampus. Sensor mengirimkan data setiap lima menit dan memberikan peringatan dini ketika ketinggian air melewati ambang batas.\n\nHasil uji coba akan menjadi dasar pengembangan sistem peringatan dini banjir untuk kawasan permukiman di sekitar kampus.",
            ],
            [
                'judul' => 'Mahasiswa Kembangkan Aplikasi Parkir Pintar',
                'kategori' => 'Inovasi',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2026-03-18 14:00:00',
                'konten' => "Tim mahasiswa binaan CoE Smart City mengembangkan aplikasi parkir pintar yang menampilkan ketersediaan slot parkir secara langsung. Aplikasi memanfaatkan kamera dan pengolahan citra untuk mendeteksi kendaraan.\n\nPurwarupa aplikasi telah diuji di area parkir gedung perkuliahan dan mendapat tanggapan positif dari pengguna.",
            ],
            [
                'judul' => 'Seminar Nasional Tata Kelola Data Kota',
                'kategori' => 'Kegiatan',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2025-11-27 09:00:00',
                'konten' => "Seminar nasional tata kelola data kota menghadirkan pembicara dari kalangan akademisi, pemerintah daerah, dan pelaku industri. Diskusi menyoroti pentingnya standar data yang seragam agar data antarinstansi dapat saling terhubung.\n\nSeminar diikuti lebih dari dua ratus peserta secara luring dan daring.",
            ],
            [
                'judul' => 'Kunjungan Pemerintah Daerah ke Laboratorium Smart City',
                'kategori' => 'Kegiatan',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2025-09-04 10:30:00',
                'konten' => "Rombongan perangkat daerah berkunjung ke laboratorium CoE Smart City untuk melihat langsung hasil riset yang siap diterapkan. Kunjungan diisi dengan demonstrasi dasbor pemantauan lalu lintas dan kualitas udara.\n\nKedua pihak sepakat menindaklanjuti kunjungan dengan penyusunan rencana uji coba di beberapa kelurahan.",
            ],
            [
                'judul' => 'Penerapan Kecerdasan Buatan untuk Analisis Lalu Lintas',
                'kategori' => 'Teknologi',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2025-06-12 11:00:00',
                'konten' => "Model kecerdasan buatan yang dikembangkan tim riset mampu menghitung jumlah kendaraan dan mendeteksi antrean di persimpangan dari rekaman kamera. Akurasi penghitungan kendaraan pada uji coba mencapai lebih dari sembilan puluh persen.\n\nModel ini akan diintegrasikan dengan sistem monitoring lalu lintas cerdas yang telah berjalan.",
            ],
            [
                'judul' => 'Pembukaan Pendaftaran Program Inkubasi Startup Perkotaan',
                'kategori' => 'Pengumuman',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2025-02-03 08:00:00',
                'konten' => "CoE Smart City membuka pendaftaran program inkubasi bagi tim mahasiswa dan alumni yang memiliki ide solusi permasalahan kota. Peserta terpilih mendapatkan pendampingan, akses laboratorium, dan kesempatan presentasi kepada mitra.\n\nPendaftaran dibuka hingga akhir bulan melalui laman resmi CoE Smart City.",
            ],
            [
                'judul' => 'Hasil Riset Pola Konsumsi Energi Gedung Kampus',
                'kategori' => 'Riset',
                'status' => News::STATUS_PUBLISH,
                'tanggal' => '2024-10-21 15:00:00',
                'konten' => "Riset pemantauan energi selama satu semester menunjukkan konsumsi listrik tertinggi terjadi pada siang hari akibat penggunaan pendingin ruangan. Tim merekomendasikan pengaturan jadwal pendingin dan pemasangan sensor kehadiran di ruang kelas.\n\nRekomendasi tersebut diperkirakan dapat menurunkan konsumsi energi gedung hingga lima belas persen.",
            ],
        ];

        foreach ($daftar as $item) {
            News::updateOrCreate(['judul' => $item['judul']], [
                'kategori' => $item['kategori'],
                'konten' => $item['konten'],
                'status' => $item['status'],
                'published_at' => $item['tanggal'] ? Carbon::parse($item['tanggal']) : null,
                'thumbnail_path' => $this->gambar('news/thumbnails/contoh-'.Str::slug($item['judul']).'.svg'),
            ]);
        }

        return count($daftar);
    }

    private function mitra(): int
    {
        $daftar = [
            [
                'nama' => 'Dinas Komunikasi dan Informatika',
                'status' => Partner::STATUS_PUBLISH,
                'website' => 'https://diskominfo.example.go.id',
                'deskripsi' => 'Mitra pemerintah daerah dalam pengembangan layanan digital kota, pengelolaan pusat data, dan integrasi kanal pengaduan warga.',
            ],
            [
                'nama' => 'PT Teknologi Kota Cerdas',
                'status' => Partner::STATUS_PUBLISH,
                'website' => 'https://teknologikotacerdas.example.com',
                'deskripsi' => 'Mitra industri penyedia perangkat Internet of Things, sensor lingkungan, dan dukungan teknis untuk uji coba lapangan.',
            ],
            [
                'nama' => 'Universitas Mitra Riset (Draft)',
                'status' => Partner::STATUS_DRAFT,
                'website' => null,
                'deskripsi' => 'Calon mitra riset dari perguruan tinggi lain. Kerja sama masih dalam tahap pembahasan sehingga belum ditampilkan di halaman publik.',
            ],
            [
                'nama' => 'Dinas Lingkungan Hidup Kota',
                'status' => Partner::STATUS_PUBLISH,
                'website' => 'https://dlh.example.go.id',
                'deskripsi' => 'Mitra dalam pemantauan kualitas udara, pengelolaan sampah, dan perluasan ruang terbuka hijau berbasis data.',
            ],
            [
                'nama' => 'Badan Perencanaan Pembangunan Daerah Kota',
                'status' => Partner::STATUS_PUBLISH,
                'website' => 'https://bappeda.example.go.id',
                'deskripsi' => 'Mitra dalam penyusunan rencana pembangunan kota yang memanfaatkan peta digital dan analitik data perkotaan.',
            ],
            [
                'nama' => 'Komunitas Inovator Kota Cerdas',
                'status' => Partner::STATUS_PUBLISH,
                'website' => 'https://komunitas.example.org',
                'deskripsi' => 'Komunitas pengembang perangkat lunak yang berkolaborasi dalam hackathon dan pengembangan aplikasi layanan warga.',
            ],
        ];

        foreach ($daftar as $urutan => $item) {
            Partner::updateOrCreate(['nama' => $item['nama']], [
                'deskripsi' => $item['deskripsi'],
                'website' => $item['website'],
                'status' => $item['status'],
                'urutan' => $urutan + 1,
                'logo_path' => $this->logo('partners/logos/contoh-'.Str::slug($item['nama']).'.svg', $item['nama']),
            ]);
        }

        return count($daftar);
    }

    private function tim(): int
    {
        $daftar = [
            ['nama' => 'Budi Santoso', 'jabatan' => 'Ketua Tim Riset', 'bidang' => 'Internet of Things', 'tipe' => Team::TIPE_STAFF, 'status' => Team::STATUS_PUBLISH, 'email' => 'budi.santoso@smartcity.ac.id'],
            ['nama' => 'Siti Aminah', 'jabatan' => 'Anggota Peneliti', 'bidang' => 'Data Science', 'tipe' => Team::TIPE_STAFF, 'status' => Team::STATUS_PUBLISH, 'email' => 'siti.aminah@smartcity.ac.id'],
            ['nama' => 'Andi Wijaya', 'jabatan' => 'Koordinator Laboratorium', 'bidang' => 'Infrastruktur Jaringan', 'tipe' => Team::TIPE_STAFF, 'status' => Team::STATUS_PUBLISH, 'email' => 'andi.wijaya@smartcity.ac.id'],
            ['nama' => 'Dewi Anggraini', 'jabatan' => 'Staf Administrasi', 'bidang' => 'Kerja Sama dan Kemitraan', 'tipe' => Team::TIPE_STAFF, 'status' => Team::STATUS_PUBLISH, 'email' => 'dewi.anggraini@smartcity.ac.id'],
            ['nama' => 'Rian Pratama', 'jabatan' => 'Mahasiswa Magang', 'bidang' => 'Frontend Development', 'tipe' => Team::TIPE_INTERN, 'status' => Team::STATUS_DRAFT, 'email' => null],
            ['nama' => 'Nadia Putri', 'jabatan' => 'Magang Analis Data', 'bidang' => 'Sains Data', 'tipe' => Team::TIPE_INTERN, 'status' => Team::STATUS_PUBLISH, 'email' => null],
            ['nama' => 'Fajar Nugroho', 'jabatan' => 'Magang Pengembang Web', 'bidang' => 'Rekayasa Perangkat Lunak', 'tipe' => Team::TIPE_INTERN, 'status' => Team::STATUS_PUBLISH, 'email' => null],
            ['nama' => 'Intan Permata', 'jabatan' => 'Magang Desain Konten', 'bidang' => 'Desain Komunikasi Visual', 'tipe' => Team::TIPE_INTERN, 'status' => Team::STATUS_PUBLISH, 'email' => null],
        ];

        foreach ($daftar as $urutan => $item) {
            Team::updateOrCreate(['nama' => $item['nama']], [
                'jabatan' => $item['jabatan'],
                'bidang' => $item['bidang'],
                'tipe' => $item['tipe'],
                'status' => $item['status'],
                'email' => $item['email'],
                'urutan' => $urutan + 1,
            ]);
        }

        return count($daftar);
    }

    private function publikasi(User $dosenA, User $dosenB): int
    {
        $daftar = [
            [
                'judul' => 'Pengembangan Sistem Smart City Berbasis IoT',
                'pemilik' => $dosenA,
                'penulis' => 'Dosen A Pengirim',
                'tahun' => 2024,
                'kategori' => Publication::CATEGORY_JOURNAL,
                'penerbit' => 'Jurnal Teknologi Informasi',
                'doi' => '10.1234/jti.2024.001',
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Penelitian ini mengembangkan arsitektur sistem smart city berbasis Internet of Things yang menghubungkan sensor lalu lintas, sensor lingkungan, dan dasbor pemantauan dalam satu platform. Pengujian pada skala kampus menunjukkan data dapat diterima server dengan jeda rata-rata di bawah dua detik.',
            ],
            [
                'judul' => 'Analisis Keamanan Jaringan Sensor Nirkabel',
                'pemilik' => $dosenA,
                'penulis' => 'Dosen A Pengirim',
                'tahun' => 2025,
                'kategori' => Publication::CATEGORY_CONFERENCE,
                'penerbit' => null,
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Penelitian ini memetakan celah keamanan pada jaringan sensor nirkabel dan mengusulkan mekanisme autentikasi ringan untuk perangkat dengan daya terbatas. Hasil pengujian menunjukkan mekanisme tersebut mampu menahan serangan penyadapan tanpa menambah beban energi secara berarti.',
            ],
            [
                'judul' => 'Studi Implementasi Smart City di Indonesia',
                'pemilik' => null,
                'pengusul' => 'Prof. Eksternal Universitas Lain',
                'penulis' => 'Prof. Eksternal Universitas Lain',
                'tahun' => 2023,
                'kategori' => Publication::CATEGORY_REPORT,
                'penerbit' => 'Pusat Kajian Kebijakan Perkotaan',
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Studi komparatif implementasi konsep smart city di beberapa kota di Indonesia yang menyoroti kesiapan infrastruktur, sumber daya manusia, dan tata kelola data. Laporan ini merumuskan rekomendasi tahapan penerapan bagi pemerintah daerah.',
            ],
            [
                'judul' => 'Model Prediksi Kemacetan Menggunakan Data Sensor Lalu Lintas',
                'pemilik' => $dosenA,
                'penulis' => 'Dosen A Pengirim, Dosen B Penerima',
                'tahun' => 2026,
                'kategori' => Publication::CATEGORY_JOURNAL,
                'penerbit' => 'Jurnal Sistem Cerdas',
                'doi' => '10.1234/jsc.2026.014',
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Penelitian ini membandingkan beberapa algoritma pembelajaran mesin untuk memprediksi tingkat kemacetan berdasarkan data sensor lalu lintas. Model terbaik mampu memprediksi kemacetan tiga puluh menit ke depan dengan akurasi yang memadai untuk mendukung pengaturan lampu lalu lintas.',
            ],
            [
                'judul' => 'Rancang Bangun Dasbor Kualitas Udara Berbasis Web',
                'pemilik' => $dosenB,
                'penulis' => 'Dosen B Penerima',
                'tahun' => 2025,
                'kategori' => Publication::CATEGORY_CONFERENCE,
                'penerbit' => 'Prosiding Seminar Nasional Teknologi Informasi',
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Makalah ini memaparkan perancangan dasbor berbasis web untuk menampilkan data kualitas udara dari jaringan sensor. Dasbor menyajikan peta sebaran, grafik tren harian, dan peringatan ketika kualitas udara memburuk.',
            ],
            [
                'judul' => 'Pemetaan Fasilitas Publik dengan Sistem Informasi Geografis',
                'pemilik' => $dosenB,
                'penulis' => 'Dosen B Penerima',
                'tahun' => 2024,
                'kategori' => Publication::CATEGORY_JOURNAL,
                'penerbit' => 'Jurnal Geoinformatika Terapan',
                'doi' => '10.1234/jgt.2024.007',
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Penelitian ini memetakan sebaran fasilitas pendidikan, kesehatan, dan ruang publik menggunakan sistem informasi geografis. Analisis jangkauan layanan digunakan untuk mengidentifikasi wilayah yang belum terlayani secara memadai.',
            ],
            [
                'judul' => 'Buku Ajar Dasar-Dasar Kota Cerdas',
                'pemilik' => $dosenA,
                'penulis' => 'Dosen A Pengirim, Dosen B Penerima',
                'tahun' => 2023,
                'kategori' => Publication::CATEGORY_BOOK,
                'penerbit' => 'Penerbit Kampus Teknologi',
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Buku ajar ini membahas konsep dasar kota cerdas, komponen teknologi pendukung, tata kelola data, serta contoh penerapan di berbagai kota. Buku disusun untuk mahasiswa tingkat awal dan pemangku kepentingan yang baru mengenal konsep kota cerdas.',
            ],
            [
                'judul' => 'Purwarupa Penerangan Jalan Adaptif Hemat Energi',
                'pemilik' => $dosenA,
                'penulis' => 'Dosen A Pengirim',
                'tahun' => 2025,
                'kategori' => Publication::CATEGORY_PATENT,
                'penerbit' => null,
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Dokumen ini menjelaskan purwarupa lampu penerangan jalan yang menyesuaikan tingkat kecerahan berdasarkan deteksi pergerakan dan intensitas cahaya sekitar. Uji coba menunjukkan penghematan energi yang signifikan tanpa mengurangi kenyamanan pengguna jalan.',
            ],
            [
                'judul' => 'Evaluasi Layanan Aduan Warga Berbasis Aplikasi Seluler',
                'pemilik' => null,
                'pengusul' => 'Rina Kusumawati',
                'penulis' => 'Rina Kusumawati, Agus Setiawan',
                'tahun' => 2022,
                'kategori' => Publication::CATEGORY_JOURNAL,
                'penerbit' => 'Jurnal Administrasi Publik Digital',
                'doi' => '10.1234/japd.2022.031',
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Penelitian ini mengevaluasi tingkat kepuasan warga terhadap layanan aduan berbasis aplikasi seluler. Hasil survei menunjukkan kecepatan tindak lanjut laporan menjadi faktor yang paling memengaruhi kepuasan pengguna.',
            ],
            [
                'judul' => 'Kerangka Tata Kelola Data Terbuka Pemerintah Daerah',
                'pemilik' => null,
                'pengusul' => 'Hendra Saputra',
                'penulis' => 'Hendra Saputra',
                'tahun' => 2021,
                'kategori' => Publication::CATEGORY_REPORT,
                'penerbit' => 'Pusat Kajian Kebijakan Perkotaan',
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Laporan ini menyusun kerangka tata kelola data terbuka yang mencakup standar format data, mekanisme publikasi, dan perlindungan data pribadi. Kerangka diharapkan menjadi acuan bagi pemerintah daerah dalam membuka data sektoral kepada publik.',
            ],
            [
                'judul' => 'Optimasi Rute Pengangkutan Sampah Kota',
                'pemilik' => $dosenB,
                'penulis' => 'Dosen B Penerima',
                'tahun' => 2026,
                'kategori' => Publication::CATEGORY_CONFERENCE,
                'penerbit' => 'Prosiding Konferensi Nasional Kota Cerdas',
                'doi' => null,
                'status' => Publication::STATUS_PUBLISH,
                'abstrak' => 'Makalah ini mengusulkan algoritma optimasi rute armada pengangkut sampah berdasarkan data kapasitas tempat sampah pintar. Simulasi menunjukkan jarak tempuh armada dapat berkurang sehingga biaya operasional menjadi lebih efisien.',
            ],
            [
                'judul' => 'Sistem Rekomendasi Rute Transportasi Publik',
                'pemilik' => null,
                'pengusul' => 'Dinas Perhubungan Kota',
                'penulis' => 'Tim Riset Dinas Perhubungan',
                'tahun' => 2026,
                'kategori' => Publication::CATEGORY_JOURNAL,
                'penerbit' => null,
                'doi' => null,
                'status' => Publication::STATUS_DRAFT,
                'abstrak' => 'Usulan penelitian dari mitra eksternal tentang sistem rekomendasi rute transportasi publik yang mempertimbangkan waktu tempuh, biaya, dan kepadatan penumpang. Publikasi ini diinput Admin dan belum dipublikasikan ke halaman publik.',
            ],
        ];

        foreach ($daftar as $item) {
            $slug = Str::slug($item['judul']);
            $pemilik = $item['pemilik'];

            Publication::updateOrCreate(['judul' => $item['judul']], [
                'user_id' => $pemilik?->id,
                'submission_type' => $pemilik ? 'member' : 'non_member',
                'recommended_by' => $pemilik ? null : $item['pengusul'],
                'penulis' => $item['penulis'],
                'tahun' => $item['tahun'],
                'abstrak' => $item['abstrak'],
                'kategori' => $item['kategori'],
                'penerbit' => $item['penerbit'],
                'doi' => $item['doi'],
                'status' => $item['status'],
                'pdf_path' => $this->pdf("publications/pdf/contoh-{$slug}.pdf", $item['judul'], [
                    'Penulis: '.$item['penulis'],
                    'Tahun: '.$item['tahun'],
                    'Kategori: '.$item['kategori'],
                    'Penerbit: '.($item['penerbit'] ?? '-'),
                    'DOI: '.($item['doi'] ?? '-'),
                    '',
                    'Abstrak',
                    $item['abstrak'],
                    '',
                    self::CATATAN,
                ]),
                'thumbnail_path' => $this->sampul("publications/thumbnails/contoh-{$slug}.svg", $item['kategori'], $item['judul'], $item['tahun']),
            ]);
        }

        return count($daftar);
    }

    private function hki(User $admin, User $dosenA, User $dosenB): int
    {
        $daftar = [
            [
                'nomor' => 'HKI-2024-001',
                'tanggal' => '2024-01-15',
                'judul' => 'Hak Cipta Perangkat Lunak SmartCity Monitoring',
                'jenis' => 'Hak Cipta',
                'pencipta' => 'Dosen A Pengirim, Dosen B Penerima',
                'pemilik' => $dosenA,
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'HKI-2024-002',
                'tanggal' => '2024-06-01',
                'judul' => 'Paten Sederhana Alat Monitoring Kualitas Udara',
                'jenis' => 'Paten Sederhana',
                'pencipta' => 'Tim Eksternal Riset Kota',
                'pemilik' => null,
                'pengusul' => 'Dinas Lingkungan Hidup',
                'status' => Hki::STATUS_DRAFT,
            ],
            [
                'nomor' => 'EC00202512345',
                'tanggal' => '2025-03-10',
                'judul' => 'Aplikasi Pelaporan Warga Terpadu',
                'jenis' => 'Hak Cipta',
                'pencipta' => 'Dosen B Penerima, Dosen A Pengirim',
                'pemilik' => $dosenB,
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'IDS000012345',
                'tanggal' => '2025-08-21',
                'judul' => 'Sistem Penerangan Jalan Adaptif Berbasis Sensor Cahaya',
                'jenis' => 'Paten',
                'pencipta' => 'Dosen A Pengirim',
                'pemilik' => $dosenA,
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'IDM000987654',
                'tanggal' => '2023-11-02',
                'judul' => 'Merek SmartCity Lab',
                'jenis' => 'Merek',
                'pencipta' => 'Dosen A Pengirim, Dosen B Penerima',
                'pemilik' => $dosenA,
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'IDD000045678',
                'tanggal' => '2026-02-14',
                'judul' => 'Desain Tiang Sensor Lingkungan Modular',
                'jenis' => 'Desain Industri',
                'pencipta' => 'Dosen B Penerima',
                'pemilik' => $dosenB,
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'EC00202398765',
                'tanggal' => '2023-05-19',
                'judul' => 'Basis Data Peta Fasilitas Publik Kota',
                'jenis' => 'Hak Cipta',
                'pencipta' => 'Rina Kusumawati, Agus Setiawan',
                'pemilik' => null,
                'pengusul' => 'Rina Kusumawati',
                'status' => Hki::STATUS_PUBLISH,
            ],
            [
                'nomor' => 'EC00202600111',
                'tanggal' => '2026-09-01',
                'judul' => 'Modul Pembelajaran Kota Cerdas',
                'jenis' => 'Hak Cipta',
                'pencipta' => 'Dosen B Penerima',
                'pemilik' => $dosenB,
                'status' => Hki::STATUS_PUBLISH,
            ],
        ];

        $notifier = app(HkiNotifier::class);

        foreach ($daftar as $item) {
            $pemilik = $item['pemilik'];

            $hki = Hki::updateOrCreate(['nomor_sertifikat' => $item['nomor']], [
                'tgl_terbit' => $item['tanggal'],
                'judul_sertifikat' => $item['judul'],
                'jenis_sertifikat' => $item['jenis'],
                'pencipta' => $item['pencipta'],
                'user_id' => $pemilik?->id,
                'submission_type' => $pemilik ? 'member' : 'non_member',
                'recommended_by' => $pemilik ? null : $item['pengusul'],
                'status' => $item['status'],
                'file_sertifikat' => $this->pdf('hki/sertifikat/contoh-'.Str::slug($item['nomor']).'.pdf', 'Sertifikat '.$item['jenis'], [
                    'Nomor Sertifikat: '.$item['nomor'],
                    'Judul: '.$item['judul'],
                    'Pencipta atau Pemegang Hak: '.$item['pencipta'],
                    'Tanggal Terbit: '.Carbon::parse($item['tanggal'])->translatedFormat('d F Y'),
                    '',
                    self::CATATAN,
                ]),
            ]);

            $notifier->sync($hki, $pemilik ?? $admin);
        }

        return count($daftar);
    }

    private function warna(): array
    {
        return self::WARNA[$this->nomorGambar++ % count(self::WARNA)];
    }

    private function gradasi(array $warna): string
    {
        return '<defs><linearGradient id="latar" x1="0" y1="0" x2="1" y2="1">'
            .'<stop offset="0" stop-color="'.$warna[0].'"/><stop offset="1" stop-color="'.$warna[1].'"/>'
            .'</linearGradient></defs>';
    }

    private function gambar(string $path): string
    {
        $nomor = $this->nomorGambar;
        $warna = $this->warna();
        $gedung = '';

        foreach ([300, 420, 250, 500, 340, 460, 280, 520, 360, 430] as $i => $tinggi) {
            $tinggi -= ($nomor * 37 + $i * 53) % 90;
            $x = 30 + $i * 116;
            $gedung .= '<rect x="'.$x.'" y="'.(800 - $tinggi).'" width="96" height="'.$tinggi.'" rx="6" fill="#ffffff" fill-opacity="'.['0.14', '0.2', '0.26'][$i % 3].'"/>';

            for ($y = 830 - $tinggi; $y < 760; $y += 52) {
                $gedung .= '<rect x="'.($x + 20).'" y="'.$y.'" width="20" height="26" rx="3" fill="#ffffff" fill-opacity="0.35"/>'
                    .'<rect x="'.($x + 56).'" y="'.$y.'" width="20" height="26" rx="3" fill="#ffffff" fill-opacity="0.35"/>';
            }
        }

        return $this->tulis($path, '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800">'
            .$this->gradasi($warna)
            .'<rect width="1200" height="800" fill="url(#latar)"/>'
            .'<circle cx="'.(760 + ($nomor % 4) * 90).'" cy="180" r="110" fill="#ffffff" fill-opacity="0.25"/>'
            .$gedung
            .'</svg>');
    }

    private function sampul(string $path, string $label, string $judul, int $tahun): string
    {
        $warna = $this->warna();
        $baris = '';

        foreach (array_slice(explode("\n", wordwrap($judul, 20, "\n", true)), 0, 6) as $i => $teks) {
            $baris .= '<text x="48" y="'.(300 + $i * 50).'" font-family="Arial, Helvetica, sans-serif" font-size="38" font-weight="700" fill="#ffffff">'.e($teks).'</text>';
        }

        return $this->tulis($path, '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800" viewBox="0 0 600 800">'
            .$this->gradasi($warna)
            .'<rect width="600" height="800" fill="url(#latar)"/>'
            .'<circle cx="540" cy="90" r="150" fill="#ffffff" fill-opacity="0.12"/>'
            .'<rect x="48" y="150" width="96" height="8" rx="4" fill="#ffffff" fill-opacity="0.8"/>'
            .'<text x="48" y="214" font-family="Arial, Helvetica, sans-serif" font-size="26" letter-spacing="4" fill="#ffffff" fill-opacity="0.9">'.e(mb_strtoupper($label)).'</text>'
            .$baris
            .'<text x="48" y="740" font-family="Arial, Helvetica, sans-serif" font-size="26" fill="#ffffff" fill-opacity="0.85">CoE Smart City - '.$tahun.'</text>'
            .'</svg>');
    }

    private function logo(string $path, string $nama): string
    {
        $warna = $this->warna();
        $inisial = collect(explode(' ', $nama))
            ->filter(fn (string $kata) => preg_match('/^\p{Lu}/u', $kata) === 1)
            ->map(fn (string $kata) => mb_substr($kata, 0, 1))
            ->take(3)
            ->implode('');

        return $this->tulis($path, '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="240" viewBox="0 0 400 240">'
            .$this->gradasi($warna)
            .'<rect width="400" height="240" rx="32" fill="url(#latar)"/>'
            .'<text x="200" y="152" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="92" font-weight="700" letter-spacing="6" fill="#ffffff">'.e($inisial).'</text>'
            .'</svg>');
    }

    private function pdf(string $path, string $judul, array $isi): string
    {
        $aliran = [];
        $y = 780;

        foreach (explode("\n", wordwrap(Str::ascii($judul), 50, "\n", true)) as $teks) {
            $aliran[] = 'BT /F2 16 Tf 56 '.$y.' Td ('.$this->teksPdf($teks).') Tj ET';
            $y -= 22;
        }

        $y -= 10;

        foreach ($isi as $paragraf) {
            foreach (explode("\n", wordwrap(Str::ascii($paragraf), 84, "\n", true)) as $teks) {
                if ($y < 60) {
                    break 2;
                }

                if ($teks !== '') {
                    $aliran[] = 'BT /F1 11 Tf 56 '.$y.' Td ('.$this->teksPdf($teks).') Tj ET';
                }

                $y -= 16;
            }
        }

        $konten = implode("\n", $aliran);

        $objek = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
            '<< /Length '.strlen($konten).' >>'."\n".'stream'."\n".$konten."\n".'endstream',
        ];

        $berkas = '%PDF-1.4'."\n";
        $posisi = [];

        foreach ($objek as $i => $isiObjek) {
            $posisi[] = strlen($berkas);
            $berkas .= ($i + 1).' 0 obj'."\n".$isiObjek."\n".'endobj'."\n";
        }

        $xref = strlen($berkas);
        $berkas .= 'xref'."\n".'0 '.(count($objek) + 1)."\n".'0000000000 65535 f '."\n";

        foreach ($posisi as $offset) {
            $berkas .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT).' 00000 n '."\n";
        }

        $berkas .= 'trailer'."\n".'<< /Size '.(count($objek) + 1).' /Root 1 0 R >>'."\n".'startxref'."\n".$xref."\n".'%%EOF'."\n";

        return $this->tulis($path, $berkas);
    }

    private function teksPdf(string $teks): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $teks);
    }

    private function tulis(string $path, string $isi): string
    {
        Storage::disk('public')->put($path, $isi);

        return $path;
    }
}