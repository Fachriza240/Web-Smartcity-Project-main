<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminDosenSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@example.test'], [
            'fullname'            => 'Admin Test',
            'nip'                 => '0000',
            'password'            => 'password',
            'role'                => 'admin',
            'registration_status' => 'approved',
        ]);

        User::firstOrCreate(['email' => 'creator@example.test'], [
            'fullname'            => 'Creator Test',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'approved',
        ]);

        User::firstOrCreate(['email' => 'dosen.pending@example.test'], [
            'fullname'            => 'Dosen Pending',
            'nip'                 => '1001',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'pending',
        ]);
    }
}
