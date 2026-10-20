<?php

namespace App\Services;

use InvalidArgumentException;

class AhpService
{
    public const SKALA_LABEL = [
        1 => 'Sama penting',
        2 => 'Antara sama penting dan sedikit lebih penting',
        3 => 'Sedikit lebih penting',
        4 => 'Antara sedikit lebih penting dan lebih penting',
        5 => 'Lebih penting',
        6 => 'Antara lebih penting dan sangat lebih penting',
        7 => 'Sangat lebih penting',
        8 => 'Antara sangat lebih penting dan mutlak lebih penting',
        9 => 'Mutlak lebih penting',
    ];

    private array $ri;

    private float $ambangCr;

    public function __construct(?array $ri = null, ?float $ambangCr = null)
    {
        $this->ri = $ri ?? (array) config('dss.ri', []);
        $this->ambangCr = $ambangCr ?? (float) config('dss.ambang_cr', 0.1);
    }

    public function hitung(array $matriks): array
    {
        $matriks = $this->pastikanValid($matriks);
        $n = count($matriks);

        $jumlahKolom = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $jumlahKolom[$j] += $matriks[$i][$j];
            }
        }

        $ternormalisasi = [];
        $bobot = [];
        for ($i = 0; $i < $n; $i++) {
            $total = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $ternormalisasi[$i][$j] = $matriks[$i][$j] / $jumlahKolom[$j];
                $total += $ternormalisasi[$i][$j];
            }
            $bobot[$i] = $total / $n;
        }

        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $hasilKali = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $hasilKali += $matriks[$i][$j] * $bobot[$j];
            }
            $lambdaMax += $hasilKali / $bobot[$i];
        }
        $lambdaMax /= $n;

        $ci = max(0.0, ($lambdaMax - $n) / ($n - 1));
        $ri = (float) $this->ri[$n];
        $cr = $ri > 0 ? $ci / $ri : 0.0;

        return [
            'n' => $n,
            'matriks' => $matriks,
            'jumlah_kolom' => $jumlahKolom,
            'ternormalisasi' => $ternormalisasi,
            'bobot' => $bobot,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'ambang_cr' => $this->ambangCr,
            'konsisten' => $cr <= $this->ambangCr + 1e-9,
        ];
    }

    public static function buatMatriks(int $n, array $atas): array
    {
        $matriks = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matriks[$i][$j] = 1.0;
                } elseif ($i < $j) {
                    $nilai = $atas[$i][$j] ?? null;
                    if (! is_numeric($nilai) || $nilai <= 0) {
                        throw new InvalidArgumentException("Nilai perbandingan baris {$i} kolom {$j} belum valid.");
                    }
                    $matriks[$i][$j] = (float) $nilai;
                } else {
                    $nilai = $atas[$j][$i] ?? null;
                    if (! is_numeric($nilai) || $nilai <= 0) {
                        throw new InvalidArgumentException("Nilai perbandingan baris {$j} kolom {$i} belum valid.");
                    }
                    $matriks[$i][$j] = 1.0 / (float) $nilai;
                }
            }
        }

        return $matriks;
    }

    public static function nilaiDiizinkan(): array
    {
        $daftar = ['1'];

        for ($k = 2; $k <= 9; $k++) {
            $daftar[] = (string) $k;
            $daftar[] = '1/' . $k;
        }

        return $daftar;
    }

    public static function parseNilai(string $nilai): ?float
    {
        $nilai = trim($nilai);

        if (preg_match('/^[1-9]$/', $nilai) === 1) {
            return (float) $nilai;
        }

        if (preg_match('/^1\/([2-9])$/', $nilai, $cocok) === 1) {
            return 1.0 / (int) $cocok[1];
        }

        return null;
    }

    public static function formatNilai(float $nilai): string
    {
        if ($nilai <= 0) {
            return '1';
        }

        if ($nilai >= 1) {
            $bulat = (int) round($nilai);

            return (string) max(1, min(9, $bulat));
        }

        $penyebut = (int) round(1 / $nilai);

        return '1/' . max(2, min(9, $penyebut));
    }

    public function bulatkanBobot(array $bobot, int $presisi = 6): array
    {
        $bulat = array_map(fn ($b) => round((float) $b, $presisi), $bobot);
        $selisih = round(1 - array_sum($bulat), $presisi);

        if ($selisih != 0.0 && $bulat !== []) {
            $kunci = array_keys($bulat, max($bulat))[0];
            $bulat[$kunci] = round($bulat[$kunci] + $selisih, $presisi);
        }

        return $bulat;
    }

    private function pastikanValid(array $matriks): array
    {
        foreach ($matriks as $baris) {
            if (! is_array($baris)) {
                throw new InvalidArgumentException('Matriks harus berupa array dua dimensi.');
            }
        }

        $matriks = array_values(array_map('array_values', $matriks));
        $n = count($matriks);

        if ($n < 2) {
            throw new InvalidArgumentException('Matriks minimal berukuran 2 x 2.');
        }

        if (! array_key_exists($n, $this->ri)) {
            throw new InvalidArgumentException("Tabel Random Index belum mendukung {$n} kriteria.");
        }

        foreach ($matriks as $i => $baris) {
            if (count($baris) !== $n) {
                throw new InvalidArgumentException('Matriks harus berbentuk persegi (jumlah baris sama dengan jumlah kolom).');
            }

            foreach ($baris as $j => $nilai) {
                if ((! is_int($nilai) && ! is_float($nilai)) || ! is_finite((float) $nilai) || $nilai <= 0) {
                    throw new InvalidArgumentException('Setiap nilai matriks harus berupa angka lebih besar dari 0.');
                }

                $matriks[$i][$j] = (float) $nilai;
            }
        }

        for ($i = 0; $i < $n; $i++) {
            if (abs($matriks[$i][$i] - 1.0) > 1e-9) {
                throw new InvalidArgumentException('Diagonal matriks harus bernilai 1.');
            }

            for ($j = $i + 1; $j < $n; $j++) {
                if (abs($matriks[$i][$j] * $matriks[$j][$i] - 1.0) > 1e-6) {
                    throw new InvalidArgumentException('Matriks harus resiprokal: nilai [i][j] dikali [j][i] harus sama dengan 1.');
                }
            }
        }

        return $matriks;
    }
}