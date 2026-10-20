<?php

namespace Database\Seeders;

use App\Models\StatistikProyek;
use Illuminate\Database\Seeder;

class StatistikProyekSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            StatistikProyek::TIPE_INTERNASIONAL => [
                'Quantum MMU',
                'Asean IVO (PATRIOT-41R-Net)',
                'G20/T20',
                'APT Wireless Group',
                'AI-Agriculture',
                'DSA',
                'RHENIuM',
                'PolarRaptor-IoT',
            ],
            StatistikProyek::TIPE_NASIONAL => [
                'PATRIOT-NET',
                'ADA-DIKTI-MCRBS',
                '5G-MERDEKA',
                'HARUMI 2021',
                'HARUKA 2022',
                'BRIN - Kereta Cepat 2021',
                'BRIN - Roket 2021',
                'BRIN - Kereta Cepat 2022',
                'Master Plan TIK UNP',
                'Deteksi Sinyal',
                '5G-Point',
                'Zebra Codes',
                'Kereta Cepat-KCIC',
                'T3LESDM-Net',
                'ANTASENA',
                'SEKAI',
            ],
            StatistikProyek::TIPE_PRODUK => [
                'Sistem Monitoring Kota Cerdas',
                'Platform IoT Terintegrasi',
                'Manajemen Lalu Lintas Berbasis AI',
                'Solusi Energi Smart Grid',
            ],
        ];

        foreach ($data as $tipe => $daftar) {
            foreach ($daftar as $urutan => $nama) {
                StatistikProyek::updateOrCreate(
                    ['tipe' => $tipe, 'nama' => $nama],
                    ['urutan' => $urutan + 1]
                );
            }
        }
    }
}