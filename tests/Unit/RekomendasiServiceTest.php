<?php

namespace Tests\Unit;

use App\Services\RekomendasiService;
use PHPUnit\Framework\TestCase;

class RekomendasiServiceTest extends TestCase
{
    private array $konfigurasi;

    protected function setUp(): void
    {
        $this->konfigurasi = require __DIR__ . '/../../config/dss.php';
    }

    private function service(): RekomendasiService
    {
        return new RekomendasiService($this->konfigurasi);
    }

    private function kriteria(): array
    {
        $info = [];

        foreach ($this->konfigurasi['kriteria'] as $kode => $data) {
            $info[$kode] = [
                'nama' => $data['nama'],
                'bobot' => $data['bobot_awal'],
                'cluster' => $data['cluster'],
                'peran_utama' => $data['peran_utama'],
                'peran_dasar' => $data['peran_dasar'],
                'saran_gap' => $data['saran_gap'],
            ];
        }

        return $info;
    }

    private function normal(float $c1 = 0, float $c2 = 0, float $c3 = 0, float $c4 = 0, float $c5 = 0): array
    {
        return ['C1' => $c1, 'C2' => $c2, 'C3' => $c3, 'C4' => $c4, 'C5' => $c5];
    }

    public function test_batas_kategori(): void
    {
        $service = $this->service();

        $this->assertSame('Sangat Potensial', $service->tentukanKategori(100.0)['label']);
        $this->assertSame('Sangat Potensial', $service->tentukanKategori(85.0)['label']);
        $this->assertSame('Potensial', $service->tentukanKategori(84.99)['label']);
        $this->assertSame('Potensial', $service->tentukanKategori(70.0)['label']);
        $this->assertSame('Cukup Potensial', $service->tentukanKategori(69.99)['label']);
        $this->assertSame('Cukup Potensial', $service->tentukanKategori(55.0)['label']);
        $this->assertSame('Perlu Pengembangan', $service->tentukanKategori(54.99)['label']);
        $this->assertSame('Perlu Pengembangan', $service->tentukanKategori(0.0)['label']);
    }

    public function test_kategori_memakai_skor_yang_dibulatkan_dua_desimal(): void
    {
        $this->assertSame('Sangat Potensial', $this->service()->tentukanKategori(84.996)['label']);
        $this->assertSame('Potensial', $this->service()->tentukanKategori(84.994)['label']);
    }

    public function test_kategori_membawa_warna_dan_makna(): void
    {
        $kategori = $this->service()->tentukanKategori(90.0);

        $this->assertSame('success', $kategori['warna']);
        $this->assertSame(85.0, $kategori['min']);
        $this->assertTrue($kategori['makna'] !== '');
    }

    public function test_kekuatan_utama_adalah_skor_tertinggi(): void
    {
        $kuat = $this->service()->kekuatanUtama($this->normal(40, 90, 20, 10, 5), $this->kriteria());

        $this->assertSame('C2', $kuat['kode']);
        $this->assertSame('Abdimas', $kuat['nama']);
        $this->assertSame(90.0, $kuat['skor']);
    }

    public function test_kekuatan_utama_seri_dimenangkan_bobot_lebih_besar(): void
    {
        $kuat = $this->service()->kekuatanUtama($this->normal(100, 100, 100, 100, 100), $this->kriteria());

        $this->assertSame('C1', $kuat['kode']);
    }

    public function test_kekuatan_utama_null_jika_semua_nol(): void
    {
        $this->assertNull($this->service()->kekuatanUtama($this->normal(), $this->kriteria()));
    }

    public function test_cluster_mengikuti_kekuatan_utama_jika_mencapai_ambang(): void
    {
        $service = $this->service();
        $kriteria = $this->kriteria();

        $this->assertSame('Kuat Riset', $service->susun($this->normal(60), 20.0, $kriteria)['cluster']);
        $this->assertSame('Belum menonjol', $service->susun($this->normal(59.99), 20.0, $kriteria)['cluster']);
        $this->assertSame('Kuat Jejaring', $service->susun($this->normal(0, 0, 0, 0, 80), 20.0, $kriteria)['cluster']);
    }

    public function test_peran_utama_untuk_skor_tinggi_dengan_dimensi_dominan(): void
    {
        $hasil = $this->service()->susun(
            $this->normal(100, 66.67, 100, 100, 66.67),
            88.33,
            $this->kriteria(),
            ['dominan_label' => 'Smart Environment']
        );

        $this->assertSame('Ketua Riset (Smart Environment)', $hasil['rekomendasi_peran']);
        $this->assertSame('Penelitian', $hasil['kekuatan_utama']['nama']);
        $this->assertSame('Sangat Potensial', $hasil['kategori']['label']);
    }

    public function test_peran_dasar_untuk_skor_di_bawah_ambang(): void
    {
        $hasil = $this->service()->susun($this->normal(0, 100, 0, 0, 0), 25.0, $this->kriteria(), []);

        $this->assertSame('Anggota Tim Abdimas', $hasil['rekomendasi_peran']);
    }

    public function test_peran_dasar_jika_skor_tinggi_tetapi_tidak_ada_kekuatan_menonjol(): void
    {
        $hasil = $this->service()->susun($this->normal(55, 55, 55, 55, 55), 72.0, $this->kriteria(), []);

        $this->assertSame('Anggota Riset', $hasil['rekomendasi_peran']);
        $this->assertSame('Belum menonjol', $hasil['cluster']);
    }

    public function test_tanpa_data_tidak_ada_rekomendasi_peran(): void
    {
        $hasil = $this->service()->susun($this->normal(), 0.0, $this->kriteria(), [], false);

        $this->assertNull($hasil['kekuatan_utama']);
        $this->assertSame(RekomendasiService::PERAN_BELUM, $hasil['rekomendasi_peran']);
        $this->assertSame('Belum menonjol', $hasil['cluster']);
        $this->assertSame('Perlu Pengembangan', $hasil['kategori']['label']);
        $this->assertCount(5, $hasil['gap']);
    }

    public function test_gap_analysis_mengurutkan_dari_skor_terendah(): void
    {
        $gap = $this->service()->gapAnalysis($this->normal(100, 20, 49.99, 50, 0), $this->kriteria());

        $this->assertCount(3, $gap);
        $this->assertSame('C5', $gap[0]['kode']);
        $this->assertSame('C2', $gap[1]['kode']);
        $this->assertSame('C3', $gap[2]['kode']);
        $this->assertSame(0.0, $gap[0]['skor']);
        $this->assertSame('Kolaborasi dan Kepemimpinan', $gap[0]['kriteria']);
        $this->assertTrue($gap[0]['saran'] !== '');
    }

    public function test_tidak_ada_gap_jika_semua_kriteria_mencapai_ambang(): void
    {
        $this->assertSame([], $this->service()->gapAnalysis($this->normal(50, 60, 70, 80, 90), $this->kriteria()));
    }
}