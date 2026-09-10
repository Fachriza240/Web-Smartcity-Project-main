<?php

namespace Database\Seeders;

use App\Models\Hki;
use App\Models\News;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('DatabaseSeeder: dilewati karena environment production.');
            return;
        }

        $admin = User::firstOrCreate(['email' => 'admin@smartcity.ac.id'], [
            'fullname'            => 'Administrator SmartCity',
            'nip'                 => '000000000',
            'password'            => 'password',
            'role'                => 'admin',
            'registration_status' => 'approved',
        ]);

        $creator = User::firstOrCreate(['email' => 'creator@smartcity.ac.id'], [
            'fullname'            => 'Content Creator',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'approved',
        ]);

        User::firstOrCreate(['email' => 'creator.pending@smartcity.ac.id'], [
            'fullname'            => 'Content Creator Pending',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'pending',
        ]);

        User::firstOrCreate(['email' => 'creator.rejected@smartcity.ac.id'], [
            'fullname'            => 'Content Creator Rejected',
            'nip'                 => null,
            'password'            => 'password',
            'role'                => 'content_creator',
            'registration_status' => 'rejected',
        ]);

        User::firstOrCreate(['email' => 'dosen.pending@smartcity.ac.id'], [
            'fullname'            => 'Dosen Pending',
            'nip'                 => '198001012010011001',
            'prodi'               => 'Teknik Informatika',
            'fakultas'            => 'Fakultas Teknik',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'pending',
        ]);

        User::firstOrCreate(['email' => 'dosen.rejected@smartcity.ac.id'], [
            'fullname'            => 'Dosen Rejected',
            'nip'                 => '198001012010011002',
            'prodi'               => 'Teknik Informatika',
            'fakultas'            => 'Fakultas Teknik',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'rejected',
        ]);

        $dosenA = User::firstOrCreate(['email' => 'dosenA@smartcity.ac.id'], [
            'fullname'            => 'Dosen A Pengirim',
            'nip'                 => '198501012015011001',
            'prodi'               => 'Teknik Informatika',
            'fakultas'            => 'Fakultas Teknik',
            'bio'                 => 'Peneliti bidang Internet of Things dan Smart City.',
            'bidang_penelitian'   => 'IoT, Smart City',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'approved',
        ]);

        $dosenB = User::firstOrCreate(['email' => 'dosenB@smartcity.ac.id'], [
            'fullname'            => 'Dosen B Penerima',
            'nip'                 => '198501012015011002',
            'prodi'               => 'Sistem Informasi',
            'fakultas'            => 'Fakultas Teknik',
            'bio'                 => 'Peneliti bidang sistem informasi geografis.',
            'bidang_penelitian'   => 'GIS, Data Analytics',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'approved',
        ]);

        $dosenC = User::firstOrCreate(['email' => 'dosenC@smartcity.ac.id'], [
            'fullname'            => 'Dosen C Tanpa Konten',
            'nip'                 => '198501012015011003',
            'prodi'               => 'Teknik Elektro',
            'fakultas'            => 'Fakultas Teknik',
            'password'            => 'password',
            'role'                => 'dosen',
            'registration_status' => 'approved',
        ]);

        Publication::firstOrCreate([
            'judul' => 'Pengembangan Sistem Smart City Berbasis IoT',
        ], [
            'user_id'         => $dosenA->id,
            'submission_type' => 'member',
            'penulis'         => $dosenA->fullname,
            'tahun'           => 2024,
            'abstrak'         => 'Abstrak penelitian smart city berbasis Internet of Things.',
            'kategori'        => Publication::CATEGORY_JOURNAL,
            'penerbit'        => 'Jurnal Teknologi Informasi',
            'doi'             => '10.1234/jti.2024.001',
            'pdf_path'        => 'publications/sample.pdf',
            'status'          => Publication::STATUS_PUBLISH,
        ]);

        Publication::firstOrCreate([
            'judul' => 'Analisis Keamanan Jaringan Sensor Nirkabel',
        ], [
            'user_id'         => $dosenA->id,
            'submission_type' => 'member',
            'penulis'         => $dosenA->fullname,
            'tahun'           => 2025,
            'abstrak'         => 'Draft penelitian keamanan jaringan sensor nirkabel (belum direview admin).',
            'kategori'        => Publication::CATEGORY_CONFERENCE,
            'penerbit'        => null,
            'pdf_path'        => 'publications/sample-draft.pdf',
            'status'          => Publication::STATUS_DRAFT,
        ]);

        Publication::firstOrCreate([
            'judul' => 'Studi Implementasi Smart City di Indonesia',
        ], [
            'user_id'         => null,
            'submission_type' => 'non_member',
            'recommended_by'  => 'Prof. Eksternal Universitas Lain',
            'penulis'         => 'Prof. Eksternal Universitas Lain',
            'tahun'           => 2023,
            'abstrak'         => 'Studi komparatif implementasi konsep smart city di beberapa kota di Indonesia.',
            'kategori'        => Publication::CATEGORY_REPORT,
            'penerbit'        => 'Kementerian PUPR',
            'pdf_path'        => 'publications/sample-external.pdf',
            'status'          => Publication::STATUS_PUBLISH,
        ]);

        Hki::firstOrCreate([
            'nomor_sertifikat' => 'HKI-2024-001',
        ], [
            'tgl_terbit'       => '2024-01-15',
            'judul_sertifikat' => 'Hak Cipta Perangkat Lunak SmartCity Monitoring',
            'jenis_sertifikat' => 'Hak Cipta',
            'pencipta'         => $dosenA->fullname . ', ' . $dosenB->fullname,
            'user_id'          => $dosenA->id,
            'submission_type'  => 'member',
            'status'           => Hki::STATUS_PUBLISH,
        ]);

        Hki::firstOrCreate([
            'nomor_sertifikat' => 'HKI-2024-002',
        ], [
            'tgl_terbit'       => '2024-06-01',
            'judul_sertifikat' => 'Paten Sederhana Alat Monitoring Kualitas Udara',
            'jenis_sertifikat' => 'Paten Sederhana',
            'pencipta'         => 'Tim Eksternal Riset Kota',
            'user_id'          => null,
            'recommended_by'   => 'Dinas Lingkungan Hidup',
            'submission_type'  => 'non_member',
            'status'           => Hki::STATUS_DRAFT,
        ]);

        Team::firstOrCreate(['nama' => 'Budi Santoso'], [
            'jabatan' => 'Ketua Tim Riset',
            'bidang'  => 'Internet of Things',
            'tipe'    => Team::TIPE_STAFF,
            'status'  => Team::STATUS_PUBLISH,
            'urutan'  => 1,
            'email'   => 'budi.santoso@smartcity.ac.id',
        ]);

        Team::firstOrCreate(['nama' => 'Siti Aminah'], [
            'jabatan' => 'Anggota Peneliti',
            'bidang'  => 'Data Science',
            'tipe'    => Team::TIPE_STAFF,
            'status'  => Team::STATUS_PUBLISH,
            'urutan'  => 2,
        ]);

        Team::firstOrCreate(['nama' => 'Rian Pratama'], [
            'jabatan' => 'Mahasiswa Magang',
            'bidang'  => 'Frontend Development',
            'tipe'    => Team::TIPE_INTERN,
            'status'  => Team::STATUS_DRAFT, 
            'urutan'  => 3,
        ]);

        Program::firstOrCreate(['judul' => 'Program Pelatihan Smart City untuk ASN'], [
            'deskripsi' => 'Pelatihan penerapan konsep smart city bagi aparatur sipil negara.',
            'urutan'    => 1,
            'status'    => Program::STATUS_PUBLISH,
        ]);

        Program::firstOrCreate(['judul' => 'Program Riset Kolaboratif Smart Mobility'], [
            'deskripsi' => 'Kolaborasi riset transportasi cerdas bersama pemerintah kota (masih tahap perencanaan).',
            'urutan'    => 2,
            'status'    => Program::STATUS_DRAFT,
        ]);

        Partner::firstOrCreate(['nama' => 'Dinas Komunikasi dan Informatika'], [
            'deskripsi' => 'Mitra pemerintah daerah dalam implementasi layanan digital kota.',
            'website'   => 'https://diskominfo.example.go.id',
            'status'    => Partner::STATUS_PUBLISH,
            'urutan'    => 1,
        ]);

        Partner::firstOrCreate(['nama' => 'PT Teknologi Kota Cerdas'], [
            'deskripsi' => 'Mitra industri penyedia perangkat IoT.',
            'website'   => 'https://teknologikotacerdas.example.com',
            'status'    => Partner::STATUS_PUBLISH,
            'urutan'    => 2,
        ]);

        Partner::firstOrCreate(['nama' => 'Universitas Mitra Riset (Draft)'], [
            'deskripsi' => 'Calon mitra riset, kerja sama masih dalam pembahasan.',
            'status'    => Partner::STATUS_DRAFT,
            'urutan'    => 3,
        ]);

        Project::firstOrCreate(['judul' => 'Sistem Monitoring Lalu Lintas Cerdas'], [
            'deskripsi' => 'Implementasi sensor dan kamera AI untuk pemantauan kepadatan lalu lintas kota.',
            'kategori'  => 'Smart Mobility',
            'partner'   => 'Dinas Komunikasi dan Informatika',
            'tahun'     => 2024,
            'status'    => Project::STATUS_PUBLISH,
        ]);

        Project::firstOrCreate(['judul' => 'Prototipe Smart Waste Management'], [
            'deskripsi' => 'Pengembangan prototipe tempat sampah pintar dengan sensor kapasitas (masih pengujian internal).',
            'kategori'  => 'Smart Environment',
            'partner'   => 'PT Teknologi Kota Cerdas',
            'tahun'     => 2025,
            'status'    => Project::STATUS_DRAFT,
        ]);

        News::firstOrCreate(['judul' => 'Peluncuran Portal Smart City Kota'], [
            'konten'   => 'Pemerintah kota resmi meluncurkan portal layanan digital terpadu berbasis konsep smart city.',
            'kategori' => 'Pengumuman',
            'status'   => News::STATUS_PUBLISH,
        ]);

        News::firstOrCreate(['judul' => 'Workshop Internet of Things bagi Dosen dan Mahasiswa'], [
            'konten'   => 'Kegiatan workshop pengenalan IoT untuk mendukung riset smart city di kampus.',
            'kategori' => 'Kegiatan',
            'status'   => News::STATUS_PUBLISH,
        ]);

        News::firstOrCreate(['judul' => 'Draft Berita: Rencana Kerja Sama Riset 2026'], [
            'konten'   => 'Draft internal, belum dipublikasikan ke halaman publik.',
            'kategori' => 'Riset',
            'status'   => News::STATUS_DRAFT,
        ]);
    }
}