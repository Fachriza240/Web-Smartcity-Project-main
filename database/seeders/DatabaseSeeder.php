<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin bawaan
        User::create([
            'fullname' => 'Administrator SmartCity',
            'email' => 'admin@gmail.com',
            'nip' => '000000000',
            'password' => 'password',
            'role' => 'admin',
            'registration_status' => 'approved',
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
