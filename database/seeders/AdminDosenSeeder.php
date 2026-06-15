<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (approved)
        User::firstOrCreate([
            'email' => 'admin@example.test'
        ], [
            'fullname' => 'Admin Test',
            'nip' => '0000',
            'password' => 'secret',
            'role' => 'admin',
            'registration_status' => 'approved',
        ]);

        // Dosen (pending)
        User::firstOrCreate([
            'email' => 'dosen.pending@example.test'
        ], [
            'fullname' => 'Dosen Pending',
            'nip' => '1001',
            'password' => 'secret',
            'role' => 'dosen',
            'registration_status' => 'pending',
        ]);
    }
}
