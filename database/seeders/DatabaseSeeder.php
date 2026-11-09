<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn(
                'Seeder data dummy dilewati. '
                . 'Seeder hanya boleh berjalan pada '
                . 'environment local atau testing.'
            );

            return;
        }

        $this->call([
            AkunSeeder::class,
            KontenSeeder::class,
            StatistikProyekSeeder::class,
            DssKriteriaSeeder::class,
        ]);
    }
}