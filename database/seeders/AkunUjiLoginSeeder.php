<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AkunUjiLoginSeeder extends Seeder
{
    public function run(): void
    {
        $akunUji = ['dosen.lama@smartcity.ac.id', 'dosen.nonaktif@smartcity.ac.id'];

        foreach (AkunSeeder::akun() as $akun) {
            if (in_array($akun['email'], $akunUji, true)) {
                AkunSeeder::simpan($akun);
            }
        }

        $this->command?->info('Akun uji login siap: dosen.lama@smartcity.ac.id (akun lama tidak aktif) dan dosen.nonaktif@smartcity.ac.id (dinonaktifkan Admin). Password: '.AkunSeeder::PASSWORD);
    }
}
