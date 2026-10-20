<?php

namespace Database\Seeders;

use App\Models\AktivitasDosen;
use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Seeder;

class DssDemoSeeder extends Seeder
{
    public const DOMAIN = '@demo.dss.test';
    public const PREFIKS_DOI = '10.0000/demo-dss.';
    public const PREFIKS_HKI = 'DEMO-DSS-';

    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('DssDemoSeeder dilewati karena aplikasi berjalan di environment production.');

            return;
        }

        static::bersihkanKonten();

        $tahun = (int) date('Y');
        $nomorHki = 1;
        $baris = [];

        foreach (static::data() as $indeks => $d) {
            $user = AkunSeeder::simpan([
                'email' => $d['slug'] . self::DOMAIN,
                'fullname' => $d['nama'],
                'nip' => '1990010120150' . str_pad((string) ($indeks + 1), 5, '0', STR_PAD_LEFT),
                'prodi' => $d['prodi'],
                'fakultas' => 'Fakultas Ilmu Terapan',
                'bio' => $d['bio'],
                'bidang_penelitian' => $d['bidang'],
                'keterangan' => 'Dosen demo DSS',
            ]);

            AktivitasDosen::where('user_id', $user->id)->delete();

            foreach ($d['publikasi'] as [$judul, $kategori, $selisih]) {
                Publication::create([
                    'user_id' => $user->id,
                    'submission_type' => 'member',
                    'judul' => $judul,
                    'penulis' => $user->fullname,
                    'tahun' => $tahun - $selisih,
                    'abstrak' => 'Abstrak data demo untuk pengujian sistem pendukung keputusan CoE Smart City.',
                    'kategori' => $kategori,
                    'penerbit' => 'Penerbit Demo',
                    'doi' => self::PREFIKS_DOI . substr(md5($judul . $user->email), 0, 10),
                    'pdf_path' => 'demo-dss/placeholder.pdf',
                    'status' => Publication::STATUS_PUBLISH,
                ]);
            }

            foreach ($d['hki'] as [$judul, $jenis, $selisih]) {
                Hki::create([
                    'nomor_sertifikat' => self::PREFIKS_HKI . str_pad((string) $nomorHki++, 4, '0', STR_PAD_LEFT),
                    'tgl_terbit' => ($tahun - $selisih) . '-06-15',
                    'judul_sertifikat' => $judul,
                    'jenis_sertifikat' => $jenis,
                    'pencipta' => $user->fullname,
                    'user_id' => $user->id,
                    'submission_type' => 'member',
                    'status' => Hki::STATUS_PUBLISH,
                ]);
            }

            foreach ($d['aktivitas'] as $a) {
                AktivitasDosen::create([
                    'user_id' => $user->id,
                    'jenis' => $a['jenis'],
                    'judul' => $a['judul'],
                    'tahun' => $tahun - ($a['selisih'] ?? 0),
                    'skala' => $a['skala'] ?? null,
                    'peran' => $a['peran'] ?? 'anggota',
                    'sub_jenis' => $a['sub'] ?? null,
                    'bidang_smart_city' => $a['bidang'] ?? null,
                    'mitra' => $a['mitra'] ?? null,
                    'berkelanjutan' => $a['lanjut'] ?? false,
                    'status' => AktivitasDosen::STATUS_PUBLISH,
                ]);
            }

            $baris[] = [
                $user->fullname,
                count($d['publikasi']),
                count($d['hki']),
                count($d['aktivitas']),
            ];
        }

        $this->command?->table(['Dosen demo', 'Publikasi', 'HKI', 'Aktivitas'], $baris);
        $this->command?->warn('Dosen demo ikut tampil di halaman Tim dan statistik beranda. Bersihkan dengan DssDemoSeeder::bersihkan().');
    }

    public static function bersihkan(): void
    {
        static::bersihkanKonten();

        User::where('email', 'like', '%' . self::DOMAIN)->delete();
    }

    private static function bersihkanKonten(): void
    {
        Publication::where('doi', 'like', self::PREFIKS_DOI . '%')->delete();
        Hki::where('nomor_sertifikat', 'like', self::PREFIKS_HKI . '%')->delete();
    }

    public static function data(): array
    {
        return [
            [
                'slug' => 'rina.maharani',
                'nama' => 'Rina Maharani',
                'prodi' => 'Teknik Lingkungan',
                'bio' => 'Peneliti kualitas udara dan energi terbarukan untuk kota.',
                'bidang' => 'Kualitas Udara, Energi Terbarukan, IoT Lingkungan',
                'publikasi' => [
                    ['Pemantauan Kualitas Udara Perkotaan Berbasis Sensor IoT', 'Jurnal', 0],
                    ['Prediksi Polusi Udara dengan Pembelajaran Mesin', 'Jurnal', 1],
                    ['Optimasi Energi Hijau pada Gedung Kampus', 'Jurnal', 2],
                    ['Analisis Pengelolaan Sampah Kota Cerdas', 'Jurnal', 3],
                    ['Jaringan Sensor Lingkungan Berdaya Rendah', 'Conference', 1],
                ],
                'hki' => [['Aplikasi Pemantau Kualitas Udara', 'Hak Cipta', 1]],
                'aktivitas' => [
                    ['jenis' => 'penelitian', 'judul' => 'Hibah Riset Pemantauan Udara Kota Bandung', 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_environment', 'mitra' => 'Kemendikbudristek'],
                    ['jenis' => 'penelitian', 'judul' => 'Riset Kolaboratif Energi Bersih Asia Tenggara', 'selisih' => 1, 'skala' => 'internasional', 'peran' => 'ketua', 'bidang' => 'smart_environment', 'mitra' => 'Universitas Mitra Singapura'],
                    ['jenis' => 'penelitian', 'judul' => 'Penelitian Internal Sensor Lingkungan', 'selisih' => 2, 'skala' => 'institusi', 'peran' => 'ketua'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kerja Sama Dinas Lingkungan Hidup', 'selisih' => 1, 'sub' => 'pemerintah', 'mitra' => 'Dinas Lingkungan Hidup'],
                ],
            ],
            [
                'slug' => 'bagas.wicaksono',
                'nama' => 'Bagas Wicaksono',
                'prodi' => 'Sistem Informasi',
                'bio' => 'Pelaksana abdimas literasi digital dan pemberdayaan UMKM desa.',
                'bidang' => 'Pemberdayaan Masyarakat, UMKM Digital',
                'publikasi' => [
                    ['Model Pemberdayaan UMKM Desa Berbasis Digital', 'Jurnal', 1],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Digitalisasi UMKM Desa Wisata', 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_economy', 'mitra' => 'Pemerintah Desa Sukamaju', 'lanjut' => true],
                    ['jenis' => 'abdimas', 'judul' => 'Pendampingan Kelompok Tani Digital', 'selisih' => 1, 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_economy', 'mitra' => 'Kelompok Tani Makmur', 'lanjut' => true],
                    ['jenis' => 'abdimas', 'judul' => 'Literasi Digital untuk Pelajar SMA', 'selisih' => 2, 'skala' => 'institusi', 'peran' => 'ketua', 'bidang' => 'smart_people', 'mitra' => 'SMA Negeri 1'],
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Kewirausahaan Pemuda Desa', 'selisih' => 3, 'skala' => 'nasional', 'peran' => 'anggota', 'bidang' => 'smart_people', 'mitra' => 'Karang Taruna'],
                    ['jenis' => 'abdimas', 'judul' => 'Bank Sampah Digital Warga', 'selisih' => 1, 'skala' => 'institusi', 'peran' => 'ketua', 'bidang' => 'smart_environment', 'mitra' => 'RW 05', 'lanjut' => true],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kemitraan Komunitas Pelaku UMKM', 'sub' => 'komunitas', 'mitra' => 'Komunitas UMKM Bandung Raya'],
                ],
            ],
            [
                'slug' => 'sinta.kusuma',
                'nama' => 'Sinta Kusuma',
                'prodi' => 'Teknik Informatika',
                'bio' => 'Pengembang produk IoT dan kecerdasan buatan untuk layanan kota.',
                'bidang' => 'IoT, Kecerdasan Buatan, Sistem Tertanam',
                'publikasi' => [
                    ['Arsitektur Platform IoT Terintegrasi untuk Kota Cerdas', 'Jurnal', 1],
                    ['Deteksi Anomali Sensor dengan Kecerdasan Buatan', 'Conference', 0],
                ],
                'hki' => [
                    ['Sistem Pemantauan Lalu Lintas Berbasis Citra', 'Paten', 1],
                    ['Perangkat Lunak Dashboard Kota Cerdas', 'Hak Cipta', 0],
                    ['Modul Sensor Pintar Multiguna', 'Paten Sederhana', 2],
                ],
                'aktivitas' => [
                    ['jenis' => 'produk', 'judul' => 'Platform IoT Kota Cerdas', 'sub' => 'iot_ai', 'peran' => 'ketua', 'bidang' => 'smart_mobility', 'mitra' => 'Dinas Perhubungan'],
                    ['jenis' => 'produk', 'judul' => 'Dashboard Analitik Layanan Publik', 'selisih' => 1, 'sub' => 'dashboard', 'peran' => 'ketua', 'bidang' => 'smart_governance'],
                    ['jenis' => 'produk', 'judul' => 'Aplikasi Pelaporan Warga', 'selisih' => 2, 'sub' => 'aplikasi', 'peran' => 'ketua', 'bidang' => 'smart_governance'],
                    ['jenis' => 'produk', 'judul' => 'Prototipe Lampu Jalan Adaptif', 'selisih' => 1, 'sub' => 'prototipe', 'peran' => 'anggota', 'bidang' => 'smart_living'],
                ],
            ],
            [
                'slug' => 'dimas.prakoso',
                'nama' => 'Dimas Prakoso',
                'prodi' => 'Manajemen Bisnis',
                'bio' => 'Penggerak kemitraan industri dan pemerintah untuk hilirisasi riset.',
                'bidang' => 'Kemitraan Industri, Bisnis Teknologi, Startup',
                'publikasi' => [
                    ['Strategi Hilirisasi Hasil Riset Perguruan Tinggi', 'Conference', 1],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'kolaborasi', 'judul' => 'Kerja Sama Riset Industri Telekomunikasi', 'sub' => 'industri', 'mitra' => 'PT Telekomunikasi Mitra'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kemitraan Startup Teknologi Kota', 'selisih' => 1, 'sub' => 'industri', 'mitra' => 'Startup Kota Pintar'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kolaborasi Kampus Luar Negeri Smart City', 'selisih' => 1, 'sub' => 'luar_negeri', 'mitra' => 'Universitas Mitra Malaysia'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Nota Kesepahaman Pemerintah Kota', 'selisih' => 2, 'sub' => 'pemerintah', 'mitra' => 'Pemerintah Kota Bandung'],
                    ['jenis' => 'kepemimpinan', 'judul' => 'Ketua Konsorsium Riset Kota Cerdas', 'sub' => 'ketua'],
                    ['jenis' => 'kepemimpinan', 'judul' => 'Koordinator Program Kemitraan CoE', 'selisih' => 1, 'sub' => 'koordinator'],
                    ['jenis' => 'kepemimpinan', 'judul' => 'PIC Kerja Sama Industri', 'selisih' => 1, 'sub' => 'pic'],
                ],
            ],
            [
                'slug' => 'laila.nurhaliza',
                'nama' => 'Laila Nurhaliza',
                'prodi' => 'Teknik Transportasi',
                'bio' => 'Peneliti manajemen lalu lintas dan mobilitas perkotaan.',
                'bidang' => 'Lalu Lintas Cerdas, Transportasi Publik, Mobilitas',
                'publikasi' => [
                    ['Optimasi Lalu Lintas Persimpangan dengan Kecerdasan Buatan', 'Jurnal', 0],
                    ['Analisis Transportasi Publik Perkotaan', 'Jurnal', 2],
                    ['Model Parkir Cerdas Kawasan Kampus', 'Conference', 1],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'penelitian', 'judul' => 'Riset Mobilitas Kendaraan Listrik Perkotaan', 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_mobility'],
                    ['jenis' => 'penelitian', 'judul' => 'Studi Lalu Lintas Kawasan Pendidikan', 'selisih' => 2, 'skala' => 'institusi', 'peran' => 'anggota', 'bidang' => 'smart_mobility'],
                ],
            ],
            [
                'slug' => 'hendra.gunawan',
                'nama' => 'Hendra Gunawan',
                'prodi' => 'Teknik Elektro',
                'bio' => 'Dosen teknik elektro dengan kegiatan riset dan abdimas seimbang.',
                'bidang' => 'Sistem Tenaga, Otomasi',
                'publikasi' => [
                    ['Otomasi Distribusi Energi Gedung Pintar', 'Jurnal', 1],
                    ['Efisiensi Jaringan Listrik Skala Kecil', 'Conference', 2],
                ],
                'hki' => [['Perangkat Lunak Pemantau Konsumsi Listrik', 'Hak Cipta', 2]],
                'aktivitas' => [
                    ['jenis' => 'penelitian', 'judul' => 'Hibah Internal Otomasi Gedung', 'selisih' => 1, 'skala' => 'institusi', 'peran' => 'ketua'],
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Instalasi Listrik Aman Warga', 'skala' => 'institusi', 'peran' => 'ketua', 'bidang' => 'smart_living', 'mitra' => 'Warga RW 03'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kerja Sama Lintas Prodi Energi', 'selisih' => 1, 'sub' => 'lintas_prodi', 'mitra' => 'Prodi Teknik Mesin'],
                ],
            ],
            [
                'slug' => 'maya.anggraini',
                'nama' => 'Maya Anggraini',
                'prodi' => 'Pendidikan Teknologi',
                'bio' => 'Peneliti pembelajaran digital dan pelatihan kompetensi digital.',
                'bidang' => 'Pendidikan Digital, E-Learning, Pelatihan',
                'publikasi' => [
                    ['Pengembangan E-Learning untuk Kompetensi Digital Guru', 'Jurnal', 1],
                    ['Efektivitas Pelatihan Daring Aparatur Desa', 'Jurnal', 2],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Kompetensi Digital Guru Sekolah Dasar', 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_people', 'mitra' => 'Dinas Pendidikan', 'lanjut' => true],
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Literasi Digital Lansia', 'selisih' => 1, 'skala' => 'institusi', 'peran' => 'anggota', 'bidang' => 'smart_people', 'mitra' => 'Posyandu Lansia'],
                    ['jenis' => 'penelitian', 'judul' => 'Penelitian Model Pembelajaran Hibrida', 'selisih' => 2, 'skala' => 'institusi', 'peran' => 'ketua', 'bidang' => 'smart_people'],
                ],
            ],
            [
                'slug' => 'fajar.setiawan',
                'nama' => 'Fajar Setiawan',
                'prodi' => 'Teknik Informatika',
                'bio' => 'Dosen muda dengan aktivitas riset yang masih berkembang.',
                'bidang' => 'Jaringan Komputer',
                'publikasi' => [
                    ['Evaluasi Kinerja Jaringan Nirkabel Kampus', 'Conference', 1],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'penelitian', 'judul' => 'Riset Internal Jaringan Kampus', 'selisih' => 1, 'skala' => 'institusi', 'peran' => 'anggota'],
                ],
            ],
            [
                'slug' => 'citra.dewanti',
                'nama' => 'Citra Dewanti',
                'prodi' => 'Administrasi Publik',
                'bio' => 'Peneliti tata kelola pemerintahan digital dan layanan publik.',
                'bidang' => 'E-Government, Layanan Publik, Kebijakan',
                'publikasi' => [
                    ['Evaluasi Layanan Publik Digital Pemerintah Daerah', 'Jurnal', 0],
                    ['Kebijakan Open Data Pemerintah Kota', 'Jurnal', 1],
                    ['Tata Kelola Sistem Pemerintahan Berbasis Elektronik', 'Buku', 2],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'penelitian', 'judul' => 'Riset Kebijakan Layanan Publik Digital', 'skala' => 'nasional', 'peran' => 'ketua', 'bidang' => 'smart_governance', 'mitra' => 'Kementerian PANRB'],
                    ['jenis' => 'kolaborasi', 'judul' => 'Kemitraan Pemerintah Provinsi Jawa Barat', 'selisih' => 1, 'sub' => 'pemerintah', 'mitra' => 'Pemerintah Provinsi Jawa Barat'],
                    ['jenis' => 'kepemimpinan', 'judul' => 'Pengelola Proyek Layanan Publik Digital', 'selisih' => 1, 'sub' => 'pengelola_proyek'],
                ],
            ],
            [
                'slug' => 'yoga.pratama',
                'nama' => 'Yoga Pratama',
                'prodi' => 'Teknik Informatika',
                'bio' => 'Pengembang aplikasi dan sistem informasi untuk layanan kampus dan kota.',
                'bidang' => 'Rekayasa Perangkat Lunak, Aplikasi Mobile',
                'publikasi' => [
                    ['Rancang Bangun Aplikasi Layanan Warga', 'Conference', 1],
                ],
                'hki' => [
                    ['Aplikasi Layanan Informasi Kampus', 'Hak Cipta', 1],
                    ['Sistem Informasi Manajemen Aset', 'Hak Cipta', 2],
                ],
                'aktivitas' => [
                    ['jenis' => 'produk', 'judul' => 'Aplikasi Layanan Informasi Kampus', 'sub' => 'aplikasi', 'peran' => 'ketua'],
                    ['jenis' => 'produk', 'judul' => 'Sistem Informasi Manajemen Aset', 'selisih' => 1, 'sub' => 'sistem_informasi', 'peran' => 'ketua'],
                ],
            ],
            [
                'slug' => 'nadia.safitri',
                'nama' => 'Nadia Safitri',
                'prodi' => 'Sistem Informasi',
                'bio' => 'Dosen baru yang mulai membangun rekam jejak penelitian.',
                'bidang' => 'Analitik Data',
                'publikasi' => [
                    ['Analisis Data Pengunjung Pariwisata Daerah', 'Conference', 0],
                ],
                'hki' => [],
                'aktivitas' => [
                    ['jenis' => 'abdimas', 'judul' => 'Pelatihan Dasar Analitik Data Warga', 'selisih' => 8, 'skala' => 'institusi', 'peran' => 'anggota'],
                ],
            ],
            [
                'slug' => 'arif.budiman',
                'nama' => 'Arif Budiman',
                'prodi' => 'Teknik Elektro',
                'bio' => 'Dosen yang belum memiliki data aktivitas (kasus uji data kosong).',
                'bidang' => '',
                'publikasi' => [],
                'hki' => [],
                'aktivitas' => [],
            ],
        ];
    }
}