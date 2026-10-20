<?php

namespace Tests\Unit;

use App\Services\AhpService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AhpServiceTest extends TestCase
{
    private const RI = [1 => 0.0, 2 => 0.0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49];

    private function service(): AhpService
    {
        return new AhpService(self::RI, 0.1);
    }

    private function matriksKonsisten(): array
    {
        $w = [6, 5, 4, 3, 2];
        $matriks = [];

        foreach ($w as $i => $a) {
            foreach ($w as $j => $b) {
                $matriks[$i][$j] = $a / $b;
            }
        }

        return $matriks;
    }

    private function matriksSaaty(): array
    {
        return [
            [1, 2, 2, 3, 4],
            [1 / 2, 1, 2, 2, 3],
            [1 / 2, 1 / 2, 1, 2, 2],
            [1 / 3, 1 / 2, 1 / 2, 1, 2],
            [1 / 4, 1 / 3, 1 / 2, 1 / 2, 1],
        ];
    }

    private function matriksAcak(): array
    {
        return [
            [1, 9, 1 / 9, 3, 5],
            [1 / 9, 1, 9, 1 / 3, 1],
            [9, 1 / 9, 1, 7, 1 / 5],
            [1 / 3, 3, 1 / 7, 1, 9],
            [1 / 5, 1, 5, 1 / 9, 1],
        ];
    }

    public function test_matriks_konsisten_menghasilkan_bobot_tepat_dan_cr_nol(): void
    {
        $hasil = $this->service()->hitung($this->matriksKonsisten());

        foreach ([0.30, 0.25, 0.20, 0.15, 0.10] as $i => $diharapkan) {
            $this->assertEqualsWithDelta($diharapkan, $hasil['bobot'][$i], 1e-9);
        }

        $this->assertEqualsWithDelta(5.0, $hasil['lambda_max'], 1e-9);
        $this->assertEqualsWithDelta(0.0, $hasil['ci'], 1e-9);
        $this->assertEqualsWithDelta(0.0, $hasil['cr'], 1e-9);
        $this->assertTrue($hasil['konsisten']);
    }

    public function test_matriks_skala_saaty_valid_diterima(): void
    {
        $hasil = $this->service()->hitung($this->matriksSaaty());

        foreach ([0.373649, 0.248589, 0.175512, 0.122413, 0.079837] as $i => $diharapkan) {
            $this->assertEqualsWithDelta($diharapkan, $hasil['bobot'][$i], 0.00001);
        }

        $this->assertEqualsWithDelta(5.084942, $hasil['lambda_max'], 0.00001);
        $this->assertEqualsWithDelta(0.021235, $hasil['ci'], 0.00001);
        $this->assertEqualsWithDelta(1.12, $hasil['ri'], 1e-9);
        $this->assertEqualsWithDelta(0.018960, $hasil['cr'], 0.00001);
        $this->assertTrue($hasil['konsisten']);
    }

    public function test_matriks_acak_ditolak_karena_cr_terlalu_besar(): void
    {
        $hasil = $this->service()->hitung($this->matriksAcak());

        $this->assertEqualsWithDelta(14.365599, $hasil['lambda_max'], 0.0001);
        $this->assertEqualsWithDelta(2.090536, $hasil['cr'], 0.0001);
        $this->assertFalse($hasil['konsisten']);
    }

    public function test_matriks_tiga_kriteria(): void
    {
        $hasil = $this->service()->hitung([
            [1, 3, 5],
            [1 / 3, 1, 3],
            [1 / 5, 1 / 3, 1],
        ]);

        foreach ([0.633346, 0.260498, 0.106156] as $i => $diharapkan) {
            $this->assertEqualsWithDelta($diharapkan, $hasil['bobot'][$i], 0.00001);
        }

        $this->assertEqualsWithDelta(3.038715, $hasil['lambda_max'], 0.00001);
        $this->assertEqualsWithDelta(0.033375, $hasil['cr'], 0.00001);
        $this->assertTrue($hasil['konsisten']);
    }

    public function test_matriks_dua_kriteria_selalu_konsisten(): void
    {
        $hasil = $this->service()->hitung([[1, 5], [1 / 5, 1]]);

        $this->assertEqualsWithDelta(0.0, $hasil['cr'], 1e-9);
        $this->assertTrue($hasil['konsisten']);
        $this->assertEqualsWithDelta(1.0, array_sum($hasil['bobot']), 1e-9);
    }

    public function test_jumlah_bobot_selalu_satu(): void
    {
        foreach ([$this->matriksKonsisten(), $this->matriksSaaty(), $this->matriksAcak()] as $matriks) {
            $hasil = $this->service()->hitung($matriks);

            $this->assertEqualsWithDelta(1.0, array_sum($hasil['bobot']), 1e-9);
        }
    }

    public function test_batas_cr_mengikuti_pengaturan(): void
    {
        $ketat = new AhpService(self::RI, 0.01);

        $this->assertFalse($ketat->hitung($this->matriksSaaty())['konsisten']);
        $this->assertTrue($this->service()->hitung($this->matriksSaaty())['konsisten']);
    }

    public function test_matriks_satu_kriteria_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service()->hitung([[1]]);
    }

    public function test_matriks_tidak_persegi_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service()->hitung([[1, 2, 3], [1 / 2, 1, 2]]);
    }

    public function test_diagonal_bukan_satu_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service()->hitung([[2, 3], [1 / 3, 1]]);
    }

    public function test_matriks_tidak_resiprokal_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service()->hitung([[1, 3], [1 / 2, 1]]);
    }

    public function test_nilai_nol_atau_negatif_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service()->hitung([[1, 0], [0, 1]]);
    }

    public function test_jumlah_kriteria_di_luar_tabel_ri_ditolak(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $n = 11;
        $matriks = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $matriks[$i][$j] = 1;
            }
        }

        $this->service()->hitung($matriks);
    }

    public function test_buat_matriks_dari_segitiga_atas(): void
    {
        $matriks = AhpService::buatMatriks(3, [0 => [1 => 3.0, 2 => 5.0], 1 => [2 => 2.0]]);

        $this->assertSame(1.0, $matriks[0][0]);
        $this->assertEqualsWithDelta(3.0, $matriks[0][1], 1e-9);
        $this->assertEqualsWithDelta(1 / 3, $matriks[1][0], 1e-9);
        $this->assertEqualsWithDelta(1 / 5, $matriks[2][0], 1e-9);
        $this->assertEqualsWithDelta(1 / 2, $matriks[2][1], 1e-9);
        $this->assertCount(3, $matriks);
    }

    public function test_buat_matriks_menolak_nilai_kosong(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AhpService::buatMatriks(3, [0 => [1 => 3.0, 2 => 5.0]]);
    }

    public function test_parse_nilai_hanya_menerima_skala_saaty(): void
    {
        $this->assertEqualsWithDelta(5.0, AhpService::parseNilai('5'), 1e-9);
        $this->assertEqualsWithDelta(1.0, AhpService::parseNilai('1'), 1e-9);
        $this->assertEqualsWithDelta(1 / 3, AhpService::parseNilai('1/3'), 1e-9);
        $this->assertEqualsWithDelta(1 / 9, AhpService::parseNilai(' 1/9 '), 1e-9);

        foreach (['0', '10', '1/1', '1/10', '2/3', '2.5', '-3', 'abc', '', '1/0'] as $tidakValid) {
            $this->assertNull(AhpService::parseNilai($tidakValid), "'{$tidakValid}' seharusnya ditolak");
        }
    }

    public function test_format_nilai_adalah_kebalikan_parse_nilai(): void
    {
        foreach (AhpService::nilaiDiizinkan() as $teks) {
            $this->assertSame($teks, AhpService::formatNilai(AhpService::parseNilai($teks)));
        }
    }

    public function test_nilai_diizinkan_berisi_tujuh_belas_pilihan(): void
    {
        $daftar = AhpService::nilaiDiizinkan();

        $this->assertCount(17, $daftar);
        $this->assertSame('1', $daftar[0]);
    }

    public function test_pembulatan_bobot_menjumlah_tepat_satu(): void
    {
        $hasil = $this->service()->bulatkanBobot([
            'C1' => 1 / 3,
            'C2' => 1 / 3,
            'C3' => 1 / 3,
        ]);

        $this->assertEqualsWithDelta(1.0, array_sum($hasil), 1e-12);
        $this->assertSame(['C1', 'C2', 'C3'], array_keys($hasil));
    }

    public function test_pembulatan_bobot_ahp_nyata_menjumlah_tepat_satu(): void
    {
        $service = $this->service();
        $hasil = $service->hitung($this->matriksSaaty());
        $bulat = $service->bulatkanBobot(array_combine(['C1', 'C2', 'C3', 'C4', 'C5'], $hasil['bobot']));

        $this->assertEqualsWithDelta(1.0, array_sum($bulat), 1e-12);
    }
}