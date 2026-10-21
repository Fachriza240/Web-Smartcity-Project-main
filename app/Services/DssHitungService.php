<?php

namespace App\Services;

use App\Models\DssAhp;
use App\Models\DssHasil;
use App\Models\DssKriteria;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DssHitungService
{
    public function __construct(
        private KompetensiService $kompetensi,
        private SawService $saw,
        private RekomendasiService $rekomendasi,
    ) {
    }

    public function dosenYangDinilai(): Collection
    {
        return User::query()
            ->where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->where(function ($query) {
                $query->where('is_active', true)->orWhereNull('is_active');
            })
            ->orderBy('id')
            ->get();
    }

    public function hitungSemua(): array
    {
        $kriteria = DssKriteria::daftar();

        if ($kriteria->isEmpty()) {
            throw new RuntimeException('Data kriteria belum tersedia. Jalankan: php artisan db:seed --class=DssKriteriaSeeder');
        }

        $bobot = [];
        $info = [];
        foreach ($kriteria as $item) {
            $konfig = (array) config('dss.kriteria.' . $item->kode, []);
            $bobot[$item->kode] = (float) $item->bobot;
            $info[$item->kode] = [
                'nama' => $item->nama,
                'bobot' => (float) $item->bobot,
                'cluster' => $konfig['cluster'] ?? $item->nama,
                'peran_utama' => $konfig['peran_utama'] ?? ('Pelaksana ' . $item->nama),
                'peran_dasar' => $konfig['peran_dasar'] ?? ('Anggota ' . $item->nama),
                'saran_gap' => $konfig['saran_gap'] ?? '',
            ];
        }

        $dosen = $this->dosenYangDinilai();
        $waktu = now();
        $ahpId = DssAhp::terpakai()?->id;

        $analisis = [];
        $poin = [];
        foreach ($dosen as $orang) {
            $analisis[$orang->id] = $this->kompetensi->analisis($orang);
            $poin[$orang->id] = $analisis[$orang->id]['poin'];
        }

        $saw = $this->saw->hitung($poin, $bobot);

        DB::transaction(function () use ($dosen, $analisis, $poin, $saw, $info, $waktu, $ahpId) {
            foreach ($dosen as $orang) {
                $id = $orang->id;
                $skorAkhir = round($saw['skor'][$id], 2);

                $rekomendasi = $this->rekomendasi->susun(
                    $saw['normal'][$id],
                    $skorAkhir,
                    $info,
                    $analisis[$id]['kompetensi'],
                    array_sum($poin[$id]) > 0
                );

                DssHasil::updateOrCreate(
                    ['user_id' => $id],
                    [
                        'ahp_id' => $ahpId,
                        'poin_mentah' => $poin[$id],
                        'skor_normal' => array_map(fn ($nilai) => round($nilai, 2), $saw['normal'][$id]),
                        'skor_akhir' => $skorAkhir,
                        'peringkat' => $saw['peringkat'][$id],
                        'kategori' => $rekomendasi['kategori']['label'],
                        'kekuatan_utama' => $rekomendasi['kekuatan_utama']['nama'] ?? null,
                        'cluster' => $rekomendasi['cluster'],
                        'rekomendasi_peran' => $rekomendasi['rekomendasi_peran'],
                        'gap' => $rekomendasi['gap'],
                        'kompetensi' => $analisis[$id]['kompetensi'] + ['rincian' => $analisis[$id]['rincian']],
                        'dihitung_pada' => $waktu,
                    ]
                );
            }

            DssHasil::whereNotIn('user_id', $dosen->pluck('id')->all())->delete();
        });

        return [
            'jumlah' => $dosen->count(),
            'dihitung_pada' => $waktu,
            'ahp_id' => $ahpId,
        ];
    }
}