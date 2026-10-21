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
            ->where('tahun', '>=', $mulai)
            ->get(['judul', 'kategori', 'tahun'])
            ->map(fn ($p) => [
                'judul' => (string) $p->judul,
                'kategori' => (string) $p->kategori,
                'tahun' => (int) $p->tahun,
            ])->all();

        $hki = Hki::published()
            ->forDosen($user)
            ->whereDate('tgl_terbit', '>=', $mulai . '-01-01')
            ->get(['judul_sertifikat', 'jenis_sertifikat', 'tgl_terbit'])
            ->map(fn ($h) => [
                'judul' => (string) $h->judul_sertifikat,
                'jenis' => (string) $h->jenis_sertifikat,
                'tahun' => (int) $h->tgl_terbit?->year,
            ])->all();

        $aktivitas = AktivitasDosen::milik($user)
            ->published()
            ->dalamJendela()
            ->get()
            ->map(fn ($a) => [
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
            'bidang_penelitian' => (string) $user->bidang_penelitian,
        ];
    }

    public function hitungPoin(array $data): array
    {
        $rubrik = $this->konfigurasi['rubrik'] ?? [];
        $publikasi = $data['publikasi'] ?? [];
        $hki = $data['hki'] ?? [];
        $aktivitas = $data['aktivitas'] ?? [];
        $profil = (string) ($data['bidang_penelitian'] ?? '');

        $poinPublikasi = 0.0;
        foreach ($publikasi as $item) {
            $poinPublikasi += (float) ($rubrik['publikasi'][(string) ($item['kategori'] ?? '')] ?? 0);
        }

        $poinHki = 0.0;
        foreach ($hki as $item) {
            $poinHki += (float) ($rubrik['hki'][(string) ($item['jenis'] ?? '')] ?? 0);
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

        $kompetensi = $this->petaKompetensi($publikasi, $hki, $aktivitas, $profil);

        $poinSmartCity = $kompetensi['dokumen_cocok'] * (float) ($rubrik['smart_city']['poin_per_dokumen'] ?? 0)
            + $kompetensi['dimensi_tercakup'] * (float) ($rubrik['smart_city']['bonus_per_dimensi'] ?? 0);

        $poin = [
            'C1' => $poinPublikasi + $per[AktivitasDosen::JENIS_PENELITIAN],
            'C2' => $per[AktivitasDosen::JENIS_ABDIMAS],
            'C3' => $poinHki + $per[AktivitasDosen::JENIS_PRODUK],
            'C4' => $poinSmartCity,
            'C5' => $per[AktivitasDosen::JENIS_KOLABORASI] + $per[AktivitasDosen::JENIS_KEPEMIMPINAN],
        ];

        $rincian = [
            'C1' => ['publikasi' => $poinPublikasi, 'penelitian' => $per[AktivitasDosen::JENIS_PENELITIAN]],
            'C2' => ['abdimas' => $per[AktivitasDosen::JENIS_ABDIMAS]],
            'C3' => ['hki' => $poinHki, 'produk' => $per[AktivitasDosen::JENIS_PRODUK]],
            'C4' => ['dokumen_cocok' => $kompetensi['dokumen_cocok'], 'dimensi_tercakup' => $kompetensi['dimensi_tercakup']],
            'C5' => ['kolaborasi' => $per[AktivitasDosen::JENIS_KOLABORASI], 'kepemimpinan' => $per[AktivitasDosen::JENIS_KEPEMIMPINAN]],
        ];

        return [
            'poin' => array_map(fn ($nilai) => round((float) $nilai, 4), $poin),
            'rincian' => array_map(
                fn ($bagian) => array_map(fn ($nilai) => round((float) $nilai, 4), $bagian),
                $rincian
            ),
            'kompetensi' => $kompetensi,
        ];
    }

    public function deteksiDimensi(string $teks): array
    {
        $teks = trim($teks);

        if ($teks === '') {
            return [];
        }

        $hasil = [];

        foreach ($this->konfigurasi['bidang_smart_city'] ?? [] as $kunci => $dimensi) {
            foreach ($dimensi['kata_kunci'] ?? [] as $kata) {
                $pola = '/(?<![\p{L}\p{N}])' . preg_quote(mb_strtolower((string) $kata), '/') . '/iu';

                if (preg_match($pola, $teks) === 1) {
                    $hasil[] = $kunci;
                    break;
                }
            }
        }

        return $hasil;
    }

    private function petaKompetensi(array $publikasi, array $hki, array $aktivitas, string $profil): array
    {
        $dimensiConf = $this->konfigurasi['bidang_smart_city'] ?? [];
        $jumlah = array_fill_keys(array_keys($dimensiConf), 0);
        $dariProfil = $this->deteksiDimensi($profil);

        $dokumen = [];
        foreach ($publikasi as $item) {
            $dokumen[] = [(string) ($item['judul'] ?? ''), null];
        }
        foreach ($hki as $item) {
            $dokumen[] = [(string) ($item['judul'] ?? ''), null];
        }
        foreach ($aktivitas as $item) {
            $dokumen[] = [
                trim((string) ($item['judul'] ?? '') . ' ' . (string) ($item['mitra'] ?? '')),
                $item['bidang_smart_city'] ?? null,
            ];
        }

        $dokumenCocok = 0;
        foreach ($dokumen as [$teks, $tag]) {
            $dimensi = $this->deteksiDimensi($teks);

            if ($tag && isset($jumlah[$tag])) {
                $dimensi[] = $tag;
            }

            $dimensi = array_values(array_unique($dimensi));

            if ($dimensi !== []) {
                $dokumenCocok++;

                foreach ($dimensi as $kunci) {
                    $jumlah[$kunci]++;
                }
            }
        }

        $daftar = [];
        $tercakup = 0;
        foreach ($dimensiConf as $kunci => $dimensi) {
            $dariProfilIni = in_array($kunci, $dariProfil, true);

            if ($jumlah[$kunci] > 0 || $dariProfilIni) {
                $tercakup++;
            }

            $daftar[$kunci] = [
                'label' => (string) ($dimensi['label'] ?? $kunci),
                'jumlah' => $jumlah[$kunci],
                'dari_profil' => $dariProfilIni,
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

        if ($dominan === null && $dariProfil !== []) {
            $dominan = $dariProfil[0];
        }

        return [
            'dimensi' => $daftar,
            'dominan' => $dominan,
            'dominan_label' => $dominan !== null ? ($daftar[$dominan]['label'] ?? null) : null,
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