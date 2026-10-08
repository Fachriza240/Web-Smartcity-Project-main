<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    public const PASSWORD = 'password';

    public function run(): void
    {
        $baris = [];

        foreach (static::akun() as $akun) {
            $user = static::simpan($akun);

            $baris[] = [
                $user->email,
                self::PASSWORD,
                match ($user->role) {
                    'admin' => 'Admin',
                    'content_creator' => 'Content Creator',
                    default => 'Dosen',
                },
                match ($user->registration_status) {
                    User::STATUS_PENDING => 'Menunggu Validasi',
                    User::STATUS_REJECTED => 'Ditolak',
                    default => 'Disetujui',
                },
                $akun['keterangan'],
            ];
        }

        $this->command?->table(['Email', 'Password', 'Peran', 'Status', 'Keterangan'], $baris);
    }

    public static function akun(): array
    {
        $hari = max(1, (int) config('login.dormant_days', 90));

        return [
            [
                'email' => 'admin@smartcity.ac.id',
                'fullname' => 'Administrator SmartCity',
                'nip' => '000000000',
                'role' => 'admin',
                'keterangan' => 'Admin, mengelola konten dan validasi registrasi',
            ],
            [
                'email' => 'creator@smartcity.ac.id',
                'fullname' => 'Content Creator',
                'role' => 'content_creator',
                'keterangan' => 'Content Creator aktif',
            ],
            [
                'email' => 'creator.pending@smartcity.ac.id',
                'fullname' => 'Content Creator Pending',
                'role' => 'content_creator',
                'registration_status' => User::STATUS_PENDING,
                'keterangan' => 'Untuk diuji Terima atau Tolak oleh Admin',
            ],
            [
                'email' => 'creator.pending2@smartcity.ac.id',
                'fullname' => 'Content Creator Pending Dua',
                'role' => 'content_creator',
                'registration_status' => User::STATUS_PENDING,
                'keterangan' => 'Untuk diuji Terima atau Tolak oleh Admin',
            ],
            [
                'email' => 'creator.rejected@smartcity.ac.id',
                'fullname' => 'Content Creator Rejected',
                'role' => 'content_creator',
                'registration_status' => User::STATUS_REJECTED,
                'rejection_reason' => 'Nama lengkap tidak sesuai dengan identitas resmi. Silakan perbaiki data registrasi Anda.',
                'keterangan' => 'Registrasi ditolak beserta alasan',
            ],
            [
                'email' => 'dosenA@smartcity.ac.id',
                'fullname' => 'Dosen A Pengirim',
                'nip' => '198501012015011001',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'bio' => 'Peneliti bidang Internet of Things dan Smart City yang aktif mengembangkan sistem pemantauan lalu lintas dan penerangan jalan berbasis sensor.',
                'bidang_penelitian' => 'IoT, Smart City, Sistem Tertanam',
                'keterangan' => 'Punya publikasi, HKI, dan notifikasi',
            ],
            [
                'email' => 'dosenB@smartcity.ac.id',
                'fullname' => 'Dosen B Penerima',
                'nip' => '198501012015011002',
                'prodi' => 'Sistem Informasi',
                'fakultas' => 'Fakultas Teknik',
                'bio' => 'Peneliti bidang sistem informasi geografis dan analitik data perkotaan.',
                'bidang_penelitian' => 'GIS, Data Analytics',
                'keterangan' => 'Rekan penulis dan pencipta, menerima notifikasi HKI',
            ],
            [
                'email' => 'dosenC@smartcity.ac.id',
                'fullname' => 'Dosen C Tanpa Konten',
                'nip' => '198501012015011003',
                'prodi' => 'Teknik Elektro',
                'fakultas' => 'Fakultas Teknik',
                'keterangan' => 'Belum punya publikasi dan HKI (TC-5.11-03)',
            ],
            [
                'email' => 'dosen.pending@smartcity.ac.id',
                'fullname' => 'Dosen Pending',
                'nip' => '198001012010011001',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'registration_status' => User::STATUS_PENDING,
                'keterangan' => 'Untuk diuji Terima atau Tolak oleh Admin',
            ],
            [
                'email' => 'dosen.pending2@smartcity.ac.id',
                'fullname' => 'Dosen Pending Dua',
                'nip' => '198001012010011003',
                'prodi' => 'Sistem Informasi',
                'fakultas' => 'Fakultas Teknik',
                'registration_status' => User::STATUS_PENDING,
                'keterangan' => 'Untuk diuji Terima atau Tolak oleh Admin',
            ],
            [
                'email' => 'dosen.pending3@smartcity.ac.id',
                'fullname' => 'Dosen Pending Tiga',
                'nip' => '198001012010011004',
                'prodi' => 'Teknik Elektro',
                'fakultas' => 'Fakultas Teknik',
                'registration_status' => User::STATUS_PENDING,
                'keterangan' => 'Untuk diuji Terima atau Tolak oleh Admin',
            ],
            [
                'email' => 'dosen.rejected@smartcity.ac.id',
                'fullname' => 'Dosen Rejected',
                'nip' => '198001012010011002',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'registration_status' => User::STATUS_REJECTED,
                'rejection_reason' => 'NIP yang dimasukkan tidak sesuai dengan data kepegawaian. Silakan perbaiki NIP Anda.',
                'keterangan' => 'Registrasi ditolak beserta alasan',
            ],
            [
                'email' => 'dosen.lama@smartcity.ac.id',
                'fullname' => 'Dosen Akun Lama',
                'nip' => '198001012010011099',
                'prodi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknik',
                'last_login_at' => now()->subDays($hari + 30),
                'keterangan' => "Tidak login lebih dari {$hari} hari (TC-5.9-04)",
            ],
            [
                'email' => 'dosen.nonaktif@smartcity.ac.id',
                'fullname' => 'Dosen Akun Nonaktif',
                'nip' => '198001012010011098',
                'prodi' => 'Sistem Informasi',
                'fakultas' => 'Fakultas Teknik',
                'is_active' => false,
                'keterangan' => 'Dinonaktifkan Admin, tidak bisa login',
            ],
        ];
    }

    public static function simpan(array $akun): User
    {
        $nip = $akun['nip'] ?? null;

        $user = User::query()
            ->where(User::emailCredential($akun['email']))
            ->when($nip, fn ($query) => $query->orWhere('nip', $nip))
            ->first() ?? new User;

        $user->forceFill(array_merge([
            'nip' => null,
            'prodi' => null,
            'fakultas' => null,
            'bio' => null,
            'bidang_penelitian' => null,
            'role' => 'dosen',
            'registration_status' => User::STATUS_APPROVED,
            'rejection_reason' => null,
            'is_active' => true,
            'locked_until' => null,
            'last_login_at' => now(),
        ], array_diff_key($akun, ['keterangan' => true]), [
            'password' => self::PASSWORD,
        ]))->save();

        if ($user->registration_status !== User::STATUS_APPROVED) {
            $user->notifications()->delete();
        }

        return $user;
    }
}
