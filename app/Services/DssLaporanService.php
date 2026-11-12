<?php

namespace App\Services;

use App\Models\AktivitasDosen;
use App\Models\DssAhp;
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
        'C1' => [
            'publikasi' => 'Poin publikasi',
            'penelitian' => 'Poin aktivitas penelitian',
        ],
        'C2' => [
            'abdimas' => 'Poin aktivitas abdimas',
        ],
        'C3' => [
            'hki' => 'Poin HKI',
            'produk' => 'Poin produk dan inovasi',
        ],
        'C4' => [
            'dokumen_cocok' => 'Dokumen cocok kata kunci (jumlah)',
            'dimensi_tercakup' => 'Dimensi Smart City tercakup (jumlah)',
        ],
        'C5' => [
            'kolaborasi' => 'Poin kolaborasi',
            'kepemimpinan' => 'Poin kepemimpinan',
        ],
    ];

    public function __construct(
        private DssHitungService $hitung,
        private KompetensiService $kompetensi,
    ) {
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

        $dosen = $this->hitung->dosenYangDinilai();
        $hasil = DssHasil::query()->get()->keyBy('user_id');

        $dinilai = $dosen->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values()
            ->all();

        $tersimpan = $hasil->keys()
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values()
            ->all();

        if ($dinilai !== $tersimpan) {
            return true;
        }

        $perubahan = $this->perubahanTerakhir();

        if ($perubahan !== null && $perubahan->gt($waktu)) {
            return true;
        }

        return $dosen->contains(
            fn (User $user) => $this->sumberBerubah(
                $user,
                $hasil->get($user->id)
            )
        );
    }

    public function kedaluwarsaUntuk(User $user, DssHasil $hasil): bool
    {
        return ! $hasil->dihitung_pada || $this->kedaluwarsaGlobal();
    }

    private function sumberBerubah(User $user, DssHasil $hasil): bool
    {
        $aktual = $this->kompetensi->analisis($user);
        $tersimpan = (array) $hasil->kompetensi;

        return ($tersimpan['bobot_penilaian'] ?? null) != DssKriteria::bobotSaatIni()
            || ($tersimpan['konfigurasi_hash'] ?? null) !== hash(
                'sha256',
                json_encode(config('dss'), JSON_THROW_ON_ERROR)
            )
            || (int) $hasil->ahp_id !== (int) DssAhp::terpakai()?->id
            || (array) $hasil->poin_mentah != $aktual['poin']
            || ($tersimpan['rincian'] ?? []) != $aktual['rincian']
            || ($tersimpan['sdgs'] ?? []) != $aktual['kompetensi']['sdgs']
            || ($tersimpan['dimensi'] ?? []) != $aktual['kompetensi']['dimensi'];
    }

    public function kalibrasi(Collection $semua): array
    {
        $skor = $semua->pluck('skor_akhir')
            ->map(fn ($nilai) => (float) $nilai)
            ->sort()
            ->values();

        $jumlah = $skor->count();
        $median = 0.0;

        if ($jumlah > 0) {
            $tengah = intdiv($jumlah, 2);
            $median = $jumlah % 2 === 1
                ? $skor[$tengah]
                : ($skor[$tengah - 1] + $skor[$tengah]) / 2;
        }

        $kriteria = [];

        foreach (DssKriteria::daftar() as $item) {
            $poin = $semua->map(
                fn ($hasil) => (float) ($hasil->poin_mentah[$item->kode] ?? 0)
            );

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
                'sdgs' => [],
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
                    $peta[$kunci]['sdgs'] = array_unique(array_merge($peta[$kunci]['sdgs'], $info['sdgs'] ?? []));
                    $peta[$kunci]['jumlah_dokumen'] += (int) ($info['jumlah'] ?? 0);
                    $peta[$kunci]['dosen'][] = [
                        'id' => (int) $hasil->user_id,
                        'nama' => (string) ($hasil->user?->fullname ?? '-'),
                    ];
                }
            }
        }

        foreach ($peta as $kunci => $isi) {
            usort(
                $peta[$kunci]['dosen'],
                fn ($a, $b) => strcasecmp($a['nama'], $b['nama'])
            );
        }

        return $peta;
    }

    public function barisKriteria(DssHasil $hasil, ?array $maks = null): array
    {
        $baris = [];
        $bobot = $hasil->kompetensi['bobot_penilaian'] ?? $hasil->ahp?->bobot;

        if (! is_array($bobot)) {
            $bobot = array_column(
                (array) config('dss.kriteria', []),
                'bobot_awal'
            );
            $bobot = array_combine(
                array_keys((array) config('dss.kriteria', [])),
                $bobot
            );
        }

        foreach (DssKriteria::daftar() as $item) {
            $normal = (float) ($hasil->skor_normal[$item->kode] ?? 0);

            $baris[] = [
                'kode' => $item->kode,
                'nama' => $item->nama,
                'poin' => (float) ($hasil->poin_mentah[$item->kode] ?? 0),
                'maks' => $maks[$item->kode] ?? null,
                'normal' => $normal,
                'bobot' => (float) ($bobot[$item->kode] ?? 0),
                'kontribusi' => round(
                    $normal * (float) ($bobot[$item->kode] ?? 0),
                    2
                ),
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
                    'nilai' => (float) (
                        $hasil->kompetensi['rincian'][$kode][$kunci] ?? 0
                    ),
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

        return [
            'label' => $label,
            'warna' => 'secondary',
            'makna' => '',
        ];
    }

    public function warnaKategori(): array
    {
        return collect((array) config('dss.kategori', []))
            ->pluck('warna', 'label')
            ->all();
    }
}
