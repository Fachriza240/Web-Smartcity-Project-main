<?php

namespace Database\Seeders;

use App\Models\DssKriteria;
use Illuminate\Database\Seeder;

class DssKriteriaSeeder extends Seeder
{

    public function run(): void
    {
        $urutan = 1;

        foreach (config('dss.kriteria', []) as $kode => $data) {
            $kriteria = DssKriteria::firstOrNew(['kode' => $kode]);

            $kriteria->nama = $data['nama'];
            $kriteria->deskripsi = $data['deskripsi'] ?? null;
            $kriteria->jenis = $data['jenis'] ?? 'benefit';
            $kriteria->urutan = $urutan++;

            if (! $kriteria->exists) {
                $kriteria->bobot = $data['bobot_awal'];
            }

            $kriteria->save();
        }
    }
}