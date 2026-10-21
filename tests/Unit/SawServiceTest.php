<?php

namespace Tests\Unit;

use App\Services\SawService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SawServiceTest extends TestCase
{
    private const BOBOT = ['C1' => 0.30, 'C2' => 0.25, 'C3' => 0.20, 'C4' => 0.15, 'C5' => 0.10];

    private function contohPoin(): array
    {
        return [
            1 => ['C1' => 40, 'C2' => 20, 'C3' => 30, 'C4' => 12, 'C5' => 10],
            2 => ['C1' => 20, 'C2' => 30, 'C3' => 6, 'C4' => 9, 'C5' => 15],
            3 => ['C1' => 10, 'C2' => 5, 'C3' => 24, 'C4' => 3, 'C5' => 5],
            4 => ['C1' => 0, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0],
        ];
    }

    public function test_contoh_lampiran_menghasilkan_skor_yang_benar(): void
    {
        $hasil = (new SawService)->hitung($this->contohPoin(), self::BOBOT);

        $this->assertEqualsWithDelta(88.33, $hasil['skor'][1], 0.005);
        $this->assertEqualsWithDelta(65.25, $hasil['skor'][2], 0.005);
        $this->assertEqualsWithDelta(34.75, $hasil['skor'][3], 0.005);
        $this->assertEqualsWithDelta(0.0, $hasil['skor'][4], 1e-9);

        $this->assertSame([1 => 1, 2 => 2, 3 => 3, 4 => 4], $hasil['peringkat']);
    }

    public function test_nilai_maksimum_per_kriteria(): void
    {
        $hasil = (new SawService)->hitung($this->contohPoin(), self::BOBOT);

        $this->assertEqualsWithDelta(40.0, $hasil['maks']['C1'], 1e-9);
        $this->assertEqualsWithDelta(30.0, $hasil['maks']['C2'], 1e-9);
        $this->assertEqualsWithDelta(30.0, $hasil['maks']['C3'], 1e-9);
        $this->assertEqualsWithDelta(12.0, $hasil['maks']['C4'], 1e-9);
        $this->assertEqualsWithDelta(15.0, $hasil['maks']['C5'], 1e-9);
    }

    public function test_normalisasi_dosen_terbaik_bernilai_seratus(): void
    {
        $hasil = (new SawService)->hitung($this->contohPoin(), self::BOBOT);

        $this->assertEqualsWithDelta(100.0, $hasil['normal'][1]['C1'], 1e-9);
        $this->assertEqualsWithDelta(100.0, $hasil['normal'][2]['C2'], 1e-9);
        $this->assertEqualsWithDelta(50.0, $hasil['normal'][2]['C1'], 1e-9);
        $this->assertEqualsWithDelta(80.0, $hasil['normal'][3]['C3'], 1e-9);
    }

    public function test_satu_dosen_dengan_semua_kriteria_berisi_mendapat_seratus(): void
    {
        $hasil = (new SawService)->hitung([7 => ['C1' => 3, 'C2' => 1, 'C3' => 2, 'C4' => 5, 'C5' => 4]], self::BOBOT);

        $this->assertEqualsWithDelta(100.0, $hasil['skor'][7], 1e-9);
        $this->assertSame([7 => 1], $hasil['peringkat']);
    }

    public function test_semua_poin_nol_tidak_menyebabkan_error(): void
    {
        $hasil = (new SawService)->hitung([
            1 => ['C1' => 0, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0],
            2 => ['C1' => 0, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0],
        ], self::BOBOT);

        $this->assertEqualsWithDelta(0.0, $hasil['skor'][1], 1e-9);
        $this->assertEqualsWithDelta(0.0, $hasil['skor'][2], 1e-9);
        $this->assertSame([1 => 1, 2 => 1], $hasil['peringkat']);
    }

    public function test_kriteria_yang_nol_untuk_semua_dosen_bernilai_nol(): void
    {
        $hasil = (new SawService)->hitung([
            1 => ['C1' => 10, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0],
            2 => ['C1' => 5, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0],
        ], self::BOBOT);

        $this->assertEqualsWithDelta(0.0, $hasil['maks']['C2'], 1e-9);
        $this->assertEqualsWithDelta(0.0, $hasil['normal'][1]['C2'], 1e-9);
        $this->assertEqualsWithDelta(30.0, $hasil['skor'][1], 1e-9);
        $this->assertEqualsWithDelta(15.0, $hasil['skor'][2], 1e-9);
    }

    public function test_kriteria_yang_hilang_dianggap_nol(): void
    {
        $hasil = (new SawService)->hitung([
            1 => ['C1' => 10],
            2 => ['C2' => 10],
        ], self::BOBOT);

        $this->assertEqualsWithDelta(30.0, $hasil['skor'][1], 1e-9);
        $this->assertEqualsWithDelta(25.0, $hasil['skor'][2], 1e-9);
    }

    public function test_poin_negatif_dianggap_nol(): void
    {
        $hasil = (new SawService)->hitung([
            1 => ['C1' => -5, 'C2' => 10, 'C3' => 0, 'C4' => 0, 'C5' => 0],
        ], self::BOBOT);

        $this->assertEqualsWithDelta(0.0, $hasil['normal'][1]['C1'], 1e-9);
        $this->assertEqualsWithDelta(25.0, $hasil['skor'][1], 1e-9);
    }

    public function test_skor_tidak_berubah_jika_semua_poin_dikali_konstanta(): void
    {
        $asli = (new SawService)->hitung($this->contohPoin(), self::BOBOT);

        $dikali = array_map(fn ($baris) => array_map(fn ($nilai) => $nilai * 10, $baris), $this->contohPoin());
        $hasil = (new SawService)->hitung($dikali, self::BOBOT);

        foreach ([1, 2, 3, 4] as $id) {
            $this->assertEqualsWithDelta($asli['skor'][$id], $hasil['skor'][$id], 1e-9);
        }
    }

    public function test_skor_akhir_selalu_di_antara_nol_dan_seratus(): void
    {
        $hasil = (new SawService)->hitung($this->contohPoin(), self::BOBOT);

        foreach ($hasil['skor'] as $skor) {
            $this->assertGreaterThanOrEqual(0.0, $skor);
            $this->assertLessThanOrEqual(100.0 + 1e-9, $skor);
        }
    }

    public function test_peringkat_seri_berbagi_nomor_yang_sama(): void
    {
        $peringkat = (new SawService)->peringkat([1 => 80.0, 2 => 80.0, 3 => 50.0, 4 => 10.0]);

        $this->assertSame([1 => 1, 2 => 1, 3 => 3, 4 => 4], $peringkat);
    }

    public function test_peringkat_membandingkan_skor_setelah_dibulatkan_dua_desimal(): void
    {
        $peringkat = (new SawService)->peringkat([1 => 65.254, 2 => 65.249, 3 => 10.0]);

        $this->assertSame([1 => 1, 2 => 1, 3 => 3], $peringkat);
    }

    public function test_peringkat_urut_menurun(): void
    {
        $peringkat = (new SawService)->peringkat([10 => 20.0, 20 => 90.0, 30 => 55.5]);

        $this->assertSame(1, $peringkat[20]);
        $this->assertSame(2, $peringkat[30]);
        $this->assertSame(3, $peringkat[10]);
    }

    public function test_tanpa_dosen_menghasilkan_hasil_kosong(): void
    {
        $hasil = (new SawService)->hitung([], self::BOBOT);

        $this->assertSame([], $hasil['skor']);
        $this->assertSame([], $hasil['peringkat']);
    }

    public function test_bobot_yang_tidak_berjumlah_satu_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new SawService)->hitung($this->contohPoin(), ['C1' => 0.5, 'C2' => 0.2, 'C3' => 0.1, 'C4' => 0.1, 'C5' => 0.0]);
    }

    public function test_bobot_kosong_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new SawService)->hitung($this->contohPoin(), []);
    }

    public function test_bobot_negatif_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new SawService)->hitung($this->contohPoin(), ['C1' => 1.2, 'C2' => -0.2]);
    }
}