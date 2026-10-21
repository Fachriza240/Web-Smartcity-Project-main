<?php

namespace App\Services;

use App\Models\AktivitasDosen;
use App\Models\DssHasil;
use App\Models\DssKriteria;
use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DssLaporanService
{

    public const LABEL_RINCIAN = [
        'C1' => ['publikasi' => 'Poin publikasi', 'penelitian' => 'Poin aktivitas penelitian'],
        'C2' => ['abdimas' => 'Poin aktivitas abdimas'],
        'C3' => ['hki' => 'Poin HKI', 'produk' => 'Poin produk dan inovasi'],
        'C4' => ['dokumen_cocok' => 'Dokumen cocok kata kunci (jumlah)', 'dimensi_tercakup' => 'Dimensi Smart City tercakup (jumlah)'],
        'C5' => ['kolaborasi' => 'Poin kolaborasi', 'kepemimpinan' => 'Poin kepemimpinan'],
    ];

    public function __construct(private DssHitungService $hitung)
    {
    }

    public function terakhirDihitung(): ?Carbon
    {
        $nilai = DssHasil::query()->max('dihitung_pada');

        return $nilai ? Carbon::parse($nilai) : null;
    }

    public function perubahanTerakhir(?User $user = null): ?Carbon
    {
        $publikasi = Publication::query();
        $hki = Hki::query();
        $aktivitas = AktivitasDosen::query();

        if ($user) {
            $publikasi->forDosen($user);
            $hki->forDosen($user);
            $aktivitas->milik($user);
        }

        return collect([
            $publikasi->max('updated_at'),
            $hki->max('updated_at'),
            $aktivitas->max('updated_at'),
            DssKriteria::query()->max('updated_at'),
        ])
            ->filter()
            ->map(fn ($nilai) => Carbon::parse($nilai))
            ->max();
    }

    public function kedaluwarsaGlobal(): bool
    {
        $waktu = $this->terakhirDihitung();

        if (! $waktu) {
            return false;
        }

        $dinilai = $this->hitung->dosenYangDinilai()->pluck('id')->map(fn ($id) => (int) $id)->sort()->values()->all();
        $tersimpan = DssHasil::query()->pluck('user_id')->map(fn ($id) => (int) $id)->sort()->values()->all();

        if ($dinilai !== $tersimpan) {
            return true;
        }

        $perubahan = $this->perubahanTerakhir();

        return $perubahan !== null && $perubahan->gt($waktu);
    }

    public function kedaluwarsaUntuk(User $user, DssHasil $hasil): bool
    {
        if (! $hasil->dihitung_pada) {
            return false;
        }

        $perubahan = $this->perubahanTerakhir($user);

        return $perubahan !== null && $perubahan->gt($hasil->dihitung_pada);
    }

    public function kalibrasi(Collection $semua): array
    {
        $skor = $semua->pluck('skor_akhir')->map(fn ($nilai) => (float) $nilai)->sort()->values();
        $jumlah = $skor->count();

        $median = 0.0;
        if ($jumlah > 0) {
            $tengah = intdiv($jumlah, 2);
            $median = $jumlah % 2 === 1 ? $skor[$tengah] : ($skor[$tengah - 1] + $skor[$tengah]) / 2;
        }

        $kriteria = [];
        foreach (DssKriteria::daftar() as $item) {
            $poin = $semua->map(fn ($hasil) => (float) ($hasil->poin_mentah[$item->kode] ?? 0));

            $kriteria[$item->kode] = [
                'nama' => $item->nama,
                'bobot' => (float) $item->bobot,
                'maks' => (float) ($poin->max() ?? 0),
                'rata' => (float) ($poin->avg() ?? 0),
            ];
        }

        $sebaran = [];
        foreach ((array) config('dss.kategori', []) as $kategori) {
            $sebaran[] = [
                'label' => $kategori['label'],
                'min' => (float) $kategori['min'],
                'warna' => $kategori['warna'] ?? 'secondary',
                'jumlah' => $semua->where('kategori', $kategori['label'])->count(),
            ];
        }

        return [
            'jumlah' => $jumlah,
            'rata' => $jumlah > 0 ? (float) $skor->avg() : 0.0,
            'median' => (float) $median,
            'tertinggi' => $jumlah > 0 ? (float) $skor->last() : 0.0,
            'terendah' => $jumlah > 0 ? (float) $skor->first() : 0.0,
            'kriteria' => $kriteria,
            'sebaran' => $sebaran,
        ];
    }

    public function petaDimensi(Collection $semua): array
    {
        $peta = [];

        foreach ((array) config('dss.bidang_smart_city', []) as $kunci => $dimensi) {
            $peta[$kunci] = [
                'label' => (string) ($dimensi['label'] ?? $kunci),
                'jumlah_dosen' => 0,
                'jumlah_dokumen' => 0,
                'dosen' => [],
            ];
        }

        foreach ($semua as $hasil) {
            foreach ((array) ($hasil->kompetensi['dimensi'] ?? []) as $kunci => $info) {
                if (! isset($peta[$kunci])) {
                    continue;
                }

                if ((int) ($info['jumlah'] ?? 0) > 0 || ! empty($info['dari_profil'])) {
                    $peta[$kunci]['jumlah_dosen']++;
                    $peta[$kunci]['jumlah_dokumen'] += (int) ($info['jumlah'] ?? 0);
                    $peta[$kunci]['dosen'][] = [
                        'id' => (int) $hasil->user_id,
                        'nama' => (string) ($hasil->user?->fullname ?? '-'),
                    ];
                }
            }
        }

        foreach ($peta as $kunci => $isi) {
            usort($peta[$kunci]['dosen'], fn ($a, $b) => strcasecmp($a['nama'], $b['nama']));
        }

        return $peta;
    }

    public function barisKriteria(DssHasil $hasil, ?array $maks = null): array
    {
        $baris = [];

        foreach (DssKriteria::daftar() as $item) {
            $normal = (float) ($hasil->skor_normal[$item->kode] ?? 0);

            $baris[] = [
                'kode' => $item->kode,
                'nama' => $item->nama,
                'poin' => (float) ($hasil->poin_mentah[$item->kode] ?? 0),
                'maks' => $maks[$item->kode] ?? null,
                'normal' => $normal,
                'bobot' => (float) $item->bobot,
                'kontribusi' => round($normal * (float) $item->bobot, 2),
            ];
        }

        return $baris;
    }

    public function rincianPoin(DssHasil $hasil): array
    {
        $hasilRincian = [];

        foreach (self::LABEL_RINCIAN as $kode => $daftar) {
            foreach ($daftar as $kunci => $label) {
                $hasilRincian[$kode][] = [
                    'label' => $label,
                    'nilai' => (float) ($hasil->kompetensi['rincian'][$kode][$kunci] ?? 0),
                ];
            }
        }

        return $hasilRincian;
    }

    public function labelDimensi(?DssHasil $hasil): array
    {
        if (! $hasil) {
            return [];
        }

        $dominan = $hasil->kompetensi['dominan'] ?? null;
        $daftar = [];

        foreach ((array) ($hasil->kompetensi['dimensi'] ?? []) as $kunci => $info) {
            if ((int) ($info['jumlah'] ?? 0) > 0 || ! empty($info['dari_profil'])) {
                $daftar[$kunci] = (string) ($info['label'] ?? $kunci);
            }
        }

        if ($dominan !== null && isset($daftar[$dominan])) {
            $daftar = [$dominan => $daftar[$dominan]] + $daftar;
        }

        return $daftar;
    }

    /**
     * Data untuk halaman publik: hanya label dimensi, tanpa skor apa pun.
     *
     * @return array<int, string>
     */
    public function dimensiPublik(User $user): array
    {
        $hasil = DssHasil::query()->where('user_id', $user->id)->first();

        return array_values($this->labelDimensi($hasil));
    }

    public function analisisPribadi(User $user): ?array
    {
        $hasil = DssHasil::query()->where('user_id', $user->id)->first();

        if (! $hasil) {
            return null;
        }

        return [
            'hasil' => $hasil,
            'baris' => $this->barisKriteria($hasil),
            'kedaluwarsa' => $this->kedaluwarsaUntuk($user, $hasil),
            'dimensi' => array_values($this->labelDimensi($hasil)),
            'kategori' => $this->infoKategori((string) $hasil->kategori),
        ];
    }

    public function infoKategori(string $label): array
    {
        foreach ((array) config('dss.kategori', []) as $kategori) {
            if ($kategori['label'] === $label) {
                return [
                    'label' => $kategori['label'],
                    'warna' => $kategori['warna'] ?? 'secondary',
                    'makna' => $kategori['makna'] ?? '',
                ];
            }
        }

        return ['label' => $label, 'warna' => 'secondary', 'makna' => ''];
    }

    public function warnaKategori(): array
    {
        return collect((array) config('dss.kategori', []))->pluck('warna', 'label')->all();
    }
}