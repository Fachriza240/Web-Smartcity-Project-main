<?php

namespace App\Services;

class RekomendasiService
{
    public const CLUSTER_BELUM = 'Belum menonjol';

    public const PERAN_BELUM = 'Belum dapat direkomendasikan (data belum tersedia)';

    private array $konfigurasi;

    /**
     * @param  array|null  $konfigurasi  isi config/dss.php. Default: config('dss')
     */
    public function __construct(?array $konfigurasi = null)
    {
        $this->konfigurasi = $konfigurasi ?? (array) config('dss', []);
    }

    /**
     * @param  array<string, float>  $skorNormal  [kode => skor ternormalisasi 0 sampai 100]
     * @param  float  $skorAkhir  skor akhir SAW 0 sampai 100
     * @param  array<string, array>  $kriteria  [kode => [nama, bobot, cluster, peran_utama, peran_dasar, saran_gap]]
     * @param  array  $kompetensi  hasil petaKompetensi (butuh dominan_label)
     * @param  bool  $adaData  false jika dosen belum memiliki data sama sekali
     */
    public function susun(array $skorNormal, float $skorAkhir, array $kriteria, array $kompetensi = [], bool $adaData = true): array
    {
        $skorAkhir = round($skorAkhir, 2);
        $kategori = $this->tentukanKategori($skorAkhir);
        $kuat = $adaData ? $this->kekuatanUtama($skorNormal, $kriteria) : null;

        $ambangCluster = (float) ($this->konfigurasi['ambang_cluster'] ?? 60);
        $ambangPeran = (float) ($this->konfigurasi['ambang_peran_utama'] ?? 70);

        $cluster = self::CLUSTER_BELUM;
        $peran = self::PERAN_BELUM;

        if ($kuat !== null) {
            $info = $kriteria[$kuat['kode']];

            if ($kuat['skor'] >= $ambangCluster) {
                $cluster = (string) ($info['cluster'] ?? $info['nama']);
            }

            $pakaiPeranUtama = $skorAkhir >= $ambangPeran && $kuat['skor'] >= $ambangCluster;
            $peran = (string) ($pakaiPeranUtama ? $info['peran_utama'] : $info['peran_dasar']);

            $dimensi = $kompetensi['dominan_label'] ?? null;
            if ($dimensi) {
                $peran .= ' (' . $dimensi . ')';
            }
        }

        return [
            'kategori' => $kategori,
            'kekuatan_utama' => $kuat,
            'cluster' => $cluster,
            'rekomendasi_peran' => $peran,
            'gap' => $this->gapAnalysis($skorNormal, $kriteria),
        ];
    }

    /**
     * Kategori berdasarkan skor akhir yang sudah dibulatkan 2 desimal.
     *
     * @return array{min: float, label: string, warna: string, makna: string}
     */
    public function tentukanKategori(float $skorAkhir): array
    {
        $skorAkhir = round($skorAkhir, 2);
        $daftar = $this->konfigurasi['kategori'] ?? [];

        foreach ($daftar as $kategori) {
            if ($skorAkhir >= (float) $kategori['min'] - 1e-9) {
                return $this->formatKategori($kategori);
            }
        }

        return $this->formatKategori(end($daftar) ?: ['min' => 0, 'label' => '-', 'warna' => 'secondary', 'makna' => '']);
    }

    /**
     * Kriteria dengan skor ternormalisasi tertinggi. Jika sama, bobot yang lebih besar menang.
     * Mengembalikan null jika semua skor 0.
     *
     * @return array{kode: string, nama: string, skor: float}|null
     */
    public function kekuatanUtama(array $skorNormal, array $kriteria): ?array
    {
        $terbaik = null;

        foreach ($kriteria as $kode => $info) {
            $skor = (float) ($skorNormal[$kode] ?? 0);

            if ($skor <= 0) {
                continue;
            }

            if (
                $terbaik === null
                || $skor > $terbaik['skor'] + 1e-9
                || (abs($skor - $terbaik['skor']) <= 1e-9 && (float) $info['bobot'] > $terbaik['bobot'])
            ) {
                $terbaik = ['kode' => (string) $kode, 'nama' => (string) $info['nama'], 'skor' => $skor, 'bobot' => (float) $info['bobot']];
            }
        }

        if ($terbaik === null) {
            return null;
        }

        return [
            'kode' => $terbaik['kode'],
            'nama' => $terbaik['nama'],
            'skor' => round($terbaik['skor'], 2),
        ];
    }

    /**
     * Kriteria dengan skor ternormalisasi di bawah ambang gap, dari yang paling rendah.
     *
     * @return array<int, array{kode: string, kriteria: string, skor: float, saran: string}>
     */
    public function gapAnalysis(array $skorNormal, array $kriteria): array
    {
        $ambang = (float) ($this->konfigurasi['ambang_gap'] ?? 50);
        $gap = [];

        foreach ($kriteria as $kode => $info) {
            $skor = (float) ($skorNormal[$kode] ?? 0);

            if ($skor < $ambang) {
                $gap[] = [
                    'kode' => (string) $kode,
                    'kriteria' => (string) $info['nama'],
                    'skor' => round($skor, 2),
                    'saran' => (string) ($info['saran_gap'] ?? ''),
                ];
            }
        }

        usort($gap, fn ($a, $b) => $a['skor'] <=> $b['skor']);

        return $gap;
    }

    private function formatKategori(array $kategori): array
    {
        return [
            'min' => (float) $kategori['min'],
            'label' => (string) $kategori['label'],
            'warna' => (string) ($kategori['warna'] ?? 'secondary'),
            'makna' => (string) ($kategori['makna'] ?? ''),
        ];
    }
}