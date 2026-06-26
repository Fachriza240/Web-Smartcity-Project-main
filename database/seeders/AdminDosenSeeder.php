<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(['email' => 'admin@example.test'], [
            'fullname'            => 'Admin Test',
            'nip'                 => '0000',
            'password'            => 'secret',
            'role'                => 'admin',
            'registration_status' => 'approved',
        ]);

        // Content Creator test
        User::firstOrCreate(['email' => 'creator@example.test'], [
            'fullname'            => 'Creator Test',
            'nip'                 => null,
            'password'            => 'secret',
            'role'                => 'content_creator',
            'registration_status' => 'approved',
        ]);

        // Dosen pending
        User::firstOrCreate(['email' => 'dosen.pending@example.test'], [
            'fullname'            => 'Dosen Pending',
            'nip'                 => '1001',
            'password'            => 'secret',
            'role'                => 'dosen',
            'registration_status' => 'pending',
        ]);
    }
}
