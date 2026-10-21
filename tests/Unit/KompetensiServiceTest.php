<?php

namespace Tests\Unit;

use App\Services\KompetensiService;
use PHPUnit\Framework\TestCase;

class KompetensiServiceTest extends TestCase
{
    private function service(): KompetensiService
    {
        return new KompetensiService(require __DIR__ . '/../../config/dss.php');
    }

    private function aktivitas(string $jenis, array $lain = []): array
    {
        return array_merge([
            'jenis' => $jenis,
            'judul' => 'Kegiatan Uji',
            'tahun' => 2026,
            'skala' => null,
            'peran' => 'ketua',
            'sub_jenis' => null,
            'bidang_smart_city' => null,
            'mitra' => null,
            'berkelanjutan' => false,
        ], $lain);
    }

    public function test_data_kosong_menghasilkan_poin_nol(): void
    {
        $hasil = $this->service()->hitungPoin([]);

        foreach (['C1', 'C2', 'C3', 'C4', 'C5'] as $kode) {
            $this->assertEqualsWithDelta(0.0, $hasil['poin'][$kode], 1e-9);
        }

        $this->assertNull($hasil['kompetensi']['dominan']);
        $this->assertSame(0, $hasil['kompetensi']['dokumen_cocok']);
        $this->assertSame(0, $hasil['kompetensi']['dimensi_tercakup']);
    }

    public function test_poin_publikasi_mengikuti_kategori(): void
    {
        $hasil = $this->service()->hitungPoin(['publikasi' => [
            ['judul' => 'Judul A', 'kategori' => 'Jurnal'],
            ['judul' => 'Judul B', 'kategori' => 'Jurnal'],
            ['judul' => 'Judul C', 'kategori' => 'Conference'],
            ['judul' => 'Judul D', 'kategori' => 'Paten'],
            ['judul' => 'Judul E', 'kategori' => 'Kategori Tidak Dikenal'],
        ]]);

        $this->assertEqualsWithDelta(26.0, $hasil['poin']['C1'], 1e-9);
        $this->assertEqualsWithDelta(26.0, $hasil['rincian']['C1']['publikasi'], 1e-9);
    }

    public function test_poin_penelitian_memakai_skala_dan_pengali_peran(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('penelitian', ['skala' => 'nasional', 'peran' => 'ketua']),
            $this->aktivitas('penelitian', ['skala' => 'internasional', 'peran' => 'anggota']),
            $this->aktivitas('penelitian', ['skala' => 'institusi', 'peran' => 'ketua']),
        ]]);

        // 8 x 1,0 + 12 x 0,6 + 4 x 1,0
        $this->assertEqualsWithDelta(19.2, $hasil['poin']['C1'], 1e-9);
    }

    public function test_peran_tidak_dikenal_diperlakukan_sebagai_anggota(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('penelitian', ['skala' => 'nasional', 'peran' => 'asisten']),
        ]]);

        $this->assertEqualsWithDelta(4.8, $hasil['poin']['C1'], 1e-9);
    }

    public function test_poin_abdimas_dengan_bonus_keberlanjutan_tanpa_pengali_peran(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('abdimas', ['skala' => 'nasional', 'peran' => 'ketua', 'berkelanjutan' => true]),
            $this->aktivitas('abdimas', ['skala' => 'institusi', 'peran' => 'anggota', 'berkelanjutan' => false]),
        ]]);

        // (6 x 1,0 + 2) + (3 x 0,6)
        $this->assertEqualsWithDelta(9.8, $hasil['poin']['C2'], 1e-9);
    }

    public function test_poin_inovasi_dari_hki_dan_produk(): void
    {
        $hasil = $this->service()->hitungPoin([
            'hki' => [
                ['judul' => 'Judul HKI A', 'jenis' => 'Paten'],
                ['judul' => 'Judul HKI B', 'jenis' => 'Hak Cipta'],
                ['judul' => 'Judul HKI C', 'jenis' => 'Merek'],
            ],
            'aktivitas' => [
                $this->aktivitas('produk', ['sub_jenis' => 'iot_ai', 'peran' => 'ketua']),
                $this->aktivitas('produk', ['sub_jenis' => 'prototipe', 'peran' => 'anggota']),
            ],
        ]);

        // HKI: 12 + 5 + 3 = 20. Produk: 10 x 1,0 + 6 x 0,6 = 13,6.
        $this->assertEqualsWithDelta(33.6, $hasil['poin']['C3'], 1e-9);
        $this->assertEqualsWithDelta(20.0, $hasil['rincian']['C3']['hki'], 1e-9);
        $this->assertEqualsWithDelta(13.6, $hasil['rincian']['C3']['produk'], 1e-9);
    }

    public function test_kolaborasi_dan_kepemimpinan_tidak_memakai_pengali_peran(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('kolaborasi', ['sub_jenis' => 'industri', 'peran' => 'anggota', 'mitra' => 'Mitra Uji']),
            $this->aktivitas('kolaborasi', ['sub_jenis' => 'luar_negeri', 'peran' => 'anggota', 'mitra' => 'Mitra Uji']),
            $this->aktivitas('kepemimpinan', ['sub_jenis' => 'ketua', 'peran' => 'anggota']),
            $this->aktivitas('kepemimpinan', ['sub_jenis' => 'pic', 'peran' => 'anggota']),
        ]]);

        // 5 + 8 + 6 + 4, peran anggota tidak mengurangi poin
        $this->assertEqualsWithDelta(23.0, $hasil['poin']['C5'], 1e-9);
    }

    public function test_jenis_aktivitas_tidak_dikenal_diabaikan(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('lainnya', ['skala' => 'nasional']),
        ]]);

        foreach (['C1', 'C2', 'C3', 'C5'] as $kode) {
            $this->assertEqualsWithDelta(0.0, $hasil['poin'][$kode], 1e-9);
        }
    }

    public function test_kesesuaian_smart_city_dari_kata_kunci_dan_tag(): void
    {
        $hasil = $this->service()->hitungPoin([
            'publikasi' => [['judul' => 'Pemantauan Kualitas Udara Perkotaan', 'kategori' => 'Jurnal']],
            'hki' => [['judul' => 'Optimasi Lalu Lintas Kota', 'jenis' => 'Hak Cipta']],
            'aktivitas' => [
                $this->aktivitas('abdimas', ['judul' => 'Kegiatan Netral', 'skala' => 'institusi', 'bidang_smart_city' => 'smart_people']),
                $this->aktivitas('abdimas', ['judul' => 'Kegiatan Tanpa Kaitan', 'skala' => 'institusi']),
            ],
        ]);

        $kompetensi = $hasil['kompetensi'];

        $this->assertSame(3, $kompetensi['dokumen_cocok']);
        $this->assertSame(3, $kompetensi['dimensi_tercakup']);
        $this->assertSame(1, $kompetensi['dimensi']['smart_environment']['jumlah']);
        $this->assertSame(1, $kompetensi['dimensi']['smart_mobility']['jumlah']);
        $this->assertSame(1, $kompetensi['dimensi']['smart_people']['jumlah']);
        $this->assertSame(0, $kompetensi['dimensi']['smart_governance']['jumlah']);
        $this->assertSame('smart_environment', $kompetensi['dominan']);
        $this->assertSame('Smart Environment', $kompetensi['dominan_label']);

        // 3 dokumen x 2 + 3 dimensi x 3
        $this->assertEqualsWithDelta(15.0, $hasil['poin']['C4'], 1e-9);
    }

    public function test_dimensi_dominan_adalah_yang_paling_banyak_dokumennya(): void
    {
        $hasil = $this->service()->hitungPoin(['publikasi' => [
            ['judul' => 'Analisis Transportasi Publik', 'kategori' => 'Jurnal'],
            ['judul' => 'Model Parkir Cerdas', 'kategori' => 'Jurnal'],
            ['judul' => 'Sensor Polusi Udara dan Energi', 'kategori' => 'Jurnal'],
        ]]);

        $this->assertSame('smart_mobility', $hasil['kompetensi']['dominan']);
        $this->assertSame(2, $hasil['kompetensi']['dimensi']['smart_mobility']['jumlah']);
    }

    public function test_bidang_penelitian_pada_profil_menambah_cakupan_dimensi(): void
    {
        $hasil = $this->service()->hitungPoin(['bidang_penelitian' => 'Pendidikan Digital, E-Learning']);
        $kompetensi = $hasil['kompetensi'];

        $this->assertSame(0, $kompetensi['dokumen_cocok']);
        $this->assertSame(1, $kompetensi['dimensi_tercakup']);
        $this->assertTrue($kompetensi['dimensi']['smart_people']['dari_profil']);
        $this->assertSame('smart_people', $kompetensi['dominan']);
        $this->assertEqualsWithDelta(3.0, $hasil['poin']['C4'], 1e-9);
    }

    public function test_mitra_pada_aktivitas_ikut_dicocokkan(): void
    {
        $hasil = $this->service()->hitungPoin(['aktivitas' => [
            $this->aktivitas('kolaborasi', ['judul' => 'Kerja Sama Uji', 'sub_jenis' => 'pemerintah', 'mitra' => 'Dinas Lingkungan Hidup']),
        ]]);

        $this->assertSame(1, $hasil['kompetensi']['dimensi']['smart_environment']['jumlah']);
        $this->assertSame(1, $hasil['kompetensi']['dokumen_cocok']);
    }

    public function test_kata_kunci_dicocokkan_di_awal_kata(): void
    {
        $service = $this->service();

        $this->assertSame([], $service->deteksiDimensi('Xindustri Baru'));
        $this->assertSame([], $service->deteksiDimensi('Ksdmx'));
        $this->assertSame(['smart_economy'], $service->deteksiDimensi('Industrial Internet'));
        $this->assertSame(['smart_people'], $service->deteksiDimensi('Pelatihan SDM Kampus'));
        $this->assertSame(['smart_governance'], $service->deteksiDimensi('Sistem E-Government Daerah'));
        $this->assertSame([], $service->deteksiDimensi('   '));
    }

    public function test_dokumen_dengan_dua_dimensi_dihitung_satu_dokumen_dan_dua_dimensi(): void
    {
        $hasil = $this->service()->hitungPoin(['publikasi' => [
            ['judul' => 'Transportasi dan Energi Hijau', 'kategori' => 'Jurnal'],
        ]]);

        $this->assertSame(1, $hasil['kompetensi']['dokumen_cocok']);
        $this->assertSame(2, $hasil['kompetensi']['dimensi_tercakup']);
        // 1 x 2 + 2 x 3
        $this->assertEqualsWithDelta(8.0, $hasil['poin']['C4'], 1e-9);
    }

    public function test_rincian_dan_poin_konsisten(): void
    {
        $hasil = $this->service()->hitungPoin([
            'publikasi' => [['judul' => 'Judul A', 'kategori' => 'Buku']],
            'aktivitas' => [$this->aktivitas('penelitian', ['skala' => 'nasional'])],
        ]);

        $this->assertEqualsWithDelta(
            $hasil['rincian']['C1']['publikasi'] + $hasil['rincian']['C1']['penelitian'],
            $hasil['poin']['C1'],
            1e-9
        );
        $this->assertEqualsWithDelta(16.0, $hasil['poin']['C1'], 1e-9);
    }
}