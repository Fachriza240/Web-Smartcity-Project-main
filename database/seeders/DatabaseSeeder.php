<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('DatabaseSeeder dilewati karena aplikasi berjalan di environment production.');

            return;
        }

        $this->call([
            AkunSeeder::class,
            KontenSeeder::class,
        ]);
    }
}
