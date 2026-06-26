<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(['email' => 'admin@smartcity.ac.id'], [
            'fullname'            => 'Administrator SmartCity',
            'nip'                 => '000000000',
            'password'            => 'password',   // di-hash otomatis via model mutator
            'role'                => 'admin',
            'registration_status' => 'approved',
        ]);

        // Content Creator
        User::firstOrCreate(['email' => 'creator@smartcity.ac.id'], [
            'fullname'            => 'Content Creator',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'approved',
        ]);

        // Dosen (contoh, pending)
        User::firstOrCreate(['email' => 'dosen@smartcity.ac.id'], [
            'fullname'            => 'Dosen Contoh',
            'nip'                 => '198001012010011001',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'pending',
        ]);
    }
}
