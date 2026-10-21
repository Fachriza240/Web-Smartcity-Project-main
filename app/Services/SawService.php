<?php

namespace App\Services;

use InvalidArgumentException;

class SawService
{

    public function hitung(array $poin, array $bobot): array
    {
        $this->pastikanBobotValid($bobot);

        $maks = [];
        foreach (array_keys($bobot) as $kode) {
            $maks[$kode] = 0.0;

            foreach ($poin as $nilai) {
                $maks[$kode] = max($maks[$kode], max(0.0, (float) ($nilai[$kode] ?? 0)));
            }
        }

        $normal = [];
        $skor = [];

        foreach ($poin as $id => $nilai) {
            $skor[$id] = 0.0;

            foreach ($bobot as $kode => $w) {
                $x = max(0.0, (float) ($nilai[$kode] ?? 0));
                $r = $maks[$kode] > 0 ? ($x / $maks[$kode]) * 100 : 0.0;

                $normal[$id][$kode] = $r;
                $skor[$id] += (float) $w * $r;
            }
        }

        return [
            'maks' => $maks,
            'normal' => $normal,
            'skor' => $skor,
            'peringkat' => $this->peringkat($skor),
        ];
    }

    public function peringkat(array $skor): array
    {
        $dibulatkan = array_map(fn ($nilai) => round((float) $nilai, 2), $skor);

        $urutan = array_keys($dibulatkan);
        usort($urutan, function ($a, $b) use ($dibulatkan) {
            return [$dibulatkan[$b], $a] <=> [$dibulatkan[$a], $b];
        });

        $hasil = [];
        $peringkat = 0;
        $sebelumnya = null;

        foreach ($urutan as $indeks => $id) {
            if ($sebelumnya === null || abs($dibulatkan[$id] - $sebelumnya) > 1e-9) {
                $peringkat = $indeks + 1;
                $sebelumnya = $dibulatkan[$id];
            }

            $hasil[$id] = $peringkat;
        }

        return $hasil;
    }

    private function pastikanBobotValid(array $bobot): void
    {
        if ($bobot === []) {
            throw new InvalidArgumentException('Bobot kriteria belum tersedia.');
        }

        foreach ($bobot as $kode => $nilai) {
            if (! is_numeric($nilai) || $nilai < 0) {
                throw new InvalidArgumentException("Bobot kriteria {$kode} tidak valid.");
            }
        }

        if (abs(array_sum(array_map('floatval', $bobot)) - 1.0) > 0.001) {
            throw new InvalidArgumentException('Jumlah bobot kriteria harus sama dengan 1.');
        }
    }
}