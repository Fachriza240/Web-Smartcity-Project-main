<?php

namespace App\Services;

use App\Models\AktivitasDosen;
use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;

class KompetensiService
{
    private array $konfigurasi;

    public function __construct(?array $konfigurasi = null)
    {
        $this->konfigurasi = $konfigurasi ?? (array) config('dss', []);
    }

    public function analisis(User $user): array
    {
        return $this->hitungPoin($this->kumpulkanData($user));
    }

    public function kumpulkanData(User $user): array
    {
        $mulai = AktivitasDosen::tahunMulai();

        $publikasi = Publication::published()
            ->forDosen($user)
            ->whereBetween('tahun', [$mulai, now()->year])
            ->get(['judul', 'kategori', 'tahun', 'sdgs', 'smart_city_pillars'])
            ->map(fn ($p) => [
                'sdgs' => $p->sdgs ?? [],
                'smart_city_pillars' => $p->smart_city_pillars,
                'judul' => (string) $p->judul,
                'kategori' => (string) $p->kategori,
                'tahun' => (int) $p->tahun,
            ])->all();

        $hki = Hki::published()
            ->forDosen($user)
            ->whereDate('tgl_terbit', '>=', $mulai . '-01-01')
            ->whereDate('tgl_terbit', '<=', now()->toDateString())
            ->get(['judul_sertifikat', 'jenis_sertifikat', 'tgl_terbit', 'sdgs', 'smart_city_pillars'])
            ->map(fn ($h) => [
                'sdgs' => $h->sdgs ?? [],
                'smart_city_pillars' => $h->smart_city_pillars,
                'judul' => (string) $h->judul_sertifikat,
                'jenis' => (string) $h->jenis_sertifikat,
                'tahun' => (int) $h->tgl_terbit?->year,
            ])->all();

        $aktivitas = AktivitasDosen::milik($user)
            ->published()
            ->dalamJendela()
            ->where('tahun', '<=', now()->year)
            ->get()
            ->map(fn ($a) => [
                'sdgs' => $a->sdgs ?? [],
                'smart_city_pillars' => $a->smart_city_pillars,
                'jenis' => (string) $a->jenis,
                'judul' => (string) $a->judul,
                'tahun' => (int) $a->tahun,
                'skala' => $a->skala,
                'peran' => (string) $a->peran,
                'sub_jenis' => $a->sub_jenis,
                'bidang_smart_city' => $a->bidang_smart_city,
                'mitra' => $a->mitra,
                'berkelanjutan' => (bool) $a->berkelanjutan,
            ])->all();

        return [
            'publikasi' => $publikasi,
            'hki' => $hki,
            'aktivitas' => $aktivitas,
        ];
    }

    public function hitungPoin(array $data): array
    {
        $rubrik = $this->konfigurasi['rubrik'] ?? [];
        $publikasi = $data['publikasi'] ?? [];
        $hki = $data['hki'] ?? [];
        $aktivitas = $data['aktivitas'] ?? [];

        $poinPublikasi = 0.0;

        foreach ($publikasi as $item) {
            $poinPublikasi += (float) (
                $rubrik['publikasi'][(string) ($item['kategori'] ?? '')] ?? 0
            );
        }

        $poinHki = 0.0;

        foreach ($hki as $item) {
            $poinHki += (float) (
                $rubrik['hki'][(string) ($item['jenis'] ?? '')] ?? 0
            );
        }

        $per = [
            AktivitasDosen::JENIS_PENELITIAN => 0.0,
            AktivitasDosen::JENIS_ABDIMAS => 0.0,
            AktivitasDosen::JENIS_PRODUK => 0.0,
            AktivitasDosen::JENIS_KOLABORASI => 0.0,
            AktivitasDosen::JENIS_KEPEMIMPINAN => 0.0,
        ];

        foreach ($aktivitas as $item) {
            $jenis = (string) ($item['jenis'] ?? '');
            $skala = (string) ($item['skala'] ?? '');
            $sub = (string) ($item['sub_jenis'] ?? '');
            $pengali = $this->pengaliPeran((string) ($item['peran'] ?? ''));

            switch ($jenis) {
                case AktivitasDosen::JENIS_PENELITIAN:
                    $per[$jenis] += (float) ($rubrik['penelitian'][$skala] ?? 0) * $pengali;
                    break;

                case AktivitasDosen::JENIS_ABDIMAS:
                    $per[$jenis] += (float) ($rubrik['abdimas'][$skala] ?? 0) * $pengali;

                    if (! empty($item['berkelanjutan'])) {
                        $per[$jenis] += (float) ($rubrik['bonus_berkelanjutan'] ?? 0);
                    }

                    break;

                case AktivitasDosen::JENIS_PRODUK:
                    $per[$jenis] += (float) ($rubrik['produk'][$sub] ?? 0) * $pengali;
                    break;

                case AktivitasDosen::JENIS_KOLABORASI:
                    $per[$jenis] += (float) ($rubrik['kolaborasi'][$sub] ?? 0);
                    break;

                case AktivitasDosen::JENIS_KEPEMIMPINAN:
                    $per[$jenis] += (float) ($rubrik['kepemimpinan'][$sub] ?? 0);
                    break;
            }
        }

        $kompetensi = $this->petaKompetensi($publikasi, $hki, $aktivitas);

        $poinSmartCity = $kompetensi['dokumen_cocok']
            * (float) ($rubrik['smart_city']['poin_per_dokumen'] ?? 0)
            + $kompetensi['dimensi_tercakup']
            * (float) ($rubrik['smart_city']['bonus_per_dimensi'] ?? 0);

        $poin = [
            'C1' => $poinPublikasi + $per[AktivitasDosen::JENIS_PENELITIAN],
            'C2' => $per[AktivitasDosen::JENIS_ABDIMAS],
            'C3' => $poinHki + $per[AktivitasDosen::JENIS_PRODUK],
            'C4' => $poinSmartCity,
            'C5' => $per[AktivitasDosen::JENIS_KOLABORASI]
                + $per[AktivitasDosen::JENIS_KEPEMIMPINAN],
        ];

        $rincian = [
            'C1' => [
                'publikasi' => $poinPublikasi,
                'penelitian' => $per[AktivitasDosen::JENIS_PENELITIAN],
            ],
            'C2' => ['abdimas' => $per[AktivitasDosen::JENIS_ABDIMAS]],
            'C3' => [
                'hki' => $poinHki,
                'produk' => $per[AktivitasDosen::JENIS_PRODUK],
            ],
            'C4' => [
                'dokumen_cocok' => $kompetensi['dokumen_cocok'],
                'dimensi_tercakup' => $kompetensi['dimensi_tercakup'],
            ],
            'C5' => [
                'kolaborasi' => $per[AktivitasDosen::JENIS_KOLABORASI],
                'kepemimpinan' => $per[AktivitasDosen::JENIS_KEPEMIMPINAN],
            ],
        ];

        return [
            'poin' => array_map(
                fn ($nilai) => round((float) $nilai, 4),
                $poin
            ),
            'rincian' => array_map(
                fn ($bagian) => array_map(
                    fn ($nilai) => round((float) $nilai, 4),
                    $bagian
                ),
                $rincian
            ),
            'kompetensi' => $kompetensi,
        ];
    }

    private function petaKompetensi(
        array $publikasi,
        array $hki,
        array $aktivitas
    ): array {
        $dimensiConf = $this->konfigurasi['bidang_smart_city'] ?? [];
        $jumlah = array_fill_keys(array_keys($dimensiConf), 0);

        $dokumen = array_merge($publikasi, $hki, $aktivitas);

        $dokumenCocok = 0;

        $sdgs = [];
        $sdgPerDimensi = [];

        foreach ($dokumen as $item) {
            $tujuan = $item['sdgs'] ?? [];
            $sdgs = array_merge($sdgs, $tujuan);
            $pilihan = $item['smart_city_pillars'] ?? (array) ($item['bidang_smart_city'] ?? []);
            $dimensi = array_unique(array_intersect($pilihan, array_keys($jumlah)));

            if ($dimensi !== []) {
                $dokumenCocok++;

                foreach ($dimensi as $kunci) {
                    $jumlah[$kunci]++;
                    $sdgPerDimensi[$kunci] = array_merge($sdgPerDimensi[$kunci] ?? [], $tujuan);
                }
            }
        }

        $daftar = [];
        $tercakup = 0;

        foreach ($dimensiConf as $kunci => $dimensi) {
            if ($jumlah[$kunci] > 0) {
                $tercakup++;
            }

            $daftar[$kunci] = [
                'label' => (string) ($dimensi['label'] ?? $kunci),
                'jumlah' => $jumlah[$kunci],
                'sdgs' => array_values(array_unique($sdgPerDimensi[$kunci] ?? [])),
            ];
        }

        $dominan = null;
        $terbanyak = 0;

        foreach ($jumlah as $kunci => $banyak) {
            if ($banyak > $terbanyak) {
                $terbanyak = $banyak;
                $dominan = $kunci;
            }
        }

        return [
            'dimensi' => $daftar,
            'sdgs' => array_values(array_unique($sdgs)),
            'dominan' => $dominan,
            'dominan_label' => $dominan !== null
                ? ($daftar[$dominan]['label'] ?? null)
                : null,
            'dokumen_cocok' => $dokumenCocok,
            'dimensi_tercakup' => $tercakup,
        ];
    }

    private function pengaliPeran(string $peran): float
    {
        $tabel = $this->konfigurasi['rubrik']['peran'] ?? [];

        return (float) ($tabel[$peran] ?? $tabel['anggota'] ?? 1.0);
    }
}