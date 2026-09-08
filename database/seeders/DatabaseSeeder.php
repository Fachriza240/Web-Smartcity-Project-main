<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Publication;
use App\Models\Hki;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin (Approved)
        User::firstOrCreate(['email' => 'admin@smartcity.ac.id'], [
            'fullname'            => 'Administrator SmartCity',
            'nip'                 => '000000000',
            'password'            => 'password',
            'role'                => 'admin',
            'registration_status' => 'approved',
        ]);

        // 2. Content Creator (Approved)
        User::firstOrCreate(['email' => 'creator@smartcity.ac.id'], [
            'fullname'            => 'Content Creator',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'approved',
        ]);

        // 3. Dosen (Pending)
        User::firstOrCreate(['email' => 'dosen.pending@smartcity.ac.id'], [
            'fullname'            => 'Dosen Pending',
            'nip'                 => '198001012010011001',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'pending',
        ]);

        // 4. Dosen (Rejected)
        User::firstOrCreate(['email' => 'dosen.rejected@smartcity.ac.id'], [
            'fullname'            => 'Dosen Rejected',
            'nip'                 => '198001012010011002',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'rejected',
        ]);

        // 5. Dosen A (Pengirim HKI / Inputter)
        $dosenA = User::firstOrCreate(['email' => 'dosenA@smartcity.ac.id'], [
            'fullname'            => 'Dosen A Pengirim',
            'nip'                 => '198501012015011001',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'approved',
        ]);

        // 6. Dosen B (Penerima Notifikasi HKI)
        $dosenB = User::firstOrCreate(['email' => 'dosenB@smartcity.ac.id'], [
            'fullname'            => 'Dosen B Penerima',
            'nip'                 => '198501012015011002',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'approved',
        ]);

        // Dummy Data 1 Publikasi milik Dosen A
        Publication::firstOrCreate([
            'judul'          => 'Pengembangan Sistem Smart City Berbasis IoT',
        ], [
            'penulis'        => $dosenA->fullname,
            'tahun'          => 2024,
            'abstrak'        => 'Abstrak penelitian smart city.',
            'kategori'       => 'Jurnal',
            'penerbit'       => 'Jurnal Teknologi Informasi',
            'pdf_path'       => 'publications/sample.pdf',
            'status'         => 'Publish',
        ]);

        // Dummy Data 1 HKI milik Dosen A
        Hki::firstOrCreate([
            'nomor_sertifikat' => 'HKI-2024-001',
        ], [
            'tgl_terbit'       => '2024-01-15',
            'judul_sertifikat' => 'Hak Cipta Perangkat Lunak SmartCity Monitoring',
            'jenis_sertifikat' => 'Hak Cipta',
            'pencipta'         => $dosenA->fullname,
            'user_id'          => $dosenA->id,
            'submission_type'  => 'member',
            'status'           => 'Publish',
        ]);
    }
}