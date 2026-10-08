<?php

namespace Tests\Feature;

use App\Models\Hki;
use App\Models\News;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use App\Notifications\RegistrationStatusNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestPlanComplianceTest extends TestCase
{
    use RefreshDatabase;

    private const SQLI = "' OR '1'='1  ;  DROP TABLE users; --";

    private const EMOJI = "\u{1F600}\u{1F525}\u{1F4C4} データ";

    private const XSS = "<script>alert('XSS')</script>";

    private array $failures = [];

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function makeUser(string $role, string $status = User::STATUS_APPROVED, array $extra = []): User
    {
        $this->seq++;

        return User::create(array_merge([
            'fullname' => 'Pengguna Uji',
            'email' => "pengguna{$this->seq}@example.com",
            'nip' => (string) (500000 + $this->seq),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => $status,
        ], $extra));
    }

    private function check(string $label, bool $ok): void
    {
        if (! $ok) {
            $this->failures[] = $label;
        }
    }

    private function assertNoFailures(): void
    {
        $this->assertSame([], $this->failures, "Kasus gagal:\n- ".implode("\n- ", $this->failures));
    }

    private function rejected($response, string $field): bool
    {
        return $response->status() === 302 && session()->get('errors')?->has($field);
    }

    private function accepted($response, array $fields): bool
    {
        if ($response->status() !== 302) {
            return false;
        }
        $errors = session()->get('errors');

        return ! $errors || ! collect($fields)->contains(fn ($f) => $errors->has($f));
    }

    private function str(int $n): string
    {
        return str_repeat('a', $n);
    }

    private function loginLengkap(string $email, string $password)
    {
        Notification::fake();

        $this->post('/login', ['email' => $email, 'password' => $password])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        $user = User::whereRaw('LOWER(email) = ?', [mb_strtolower($email)])->firstOrFail();
        $token = null;

        Notification::assertSentTo($user, LoginConfirmationNotification::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        return $this->post(route('login.konfirmasi.setujui', $token));
    }

    private function registerPayload(array $override = []): array
    {
        $this->seq++;

        return array_merge([
            'role' => 'dosen',
            'fullname' => 'Budi Santoso',
            'email' => "budi{$this->seq}@kampus.ac.id",
            'nip' => (string) (900000 + $this->seq),
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ], $override);
    }

    public function test_5_8_registrasi_bva_ep_neg(): void
    {
        foreach ([2 => false, 3 => true, 100 => true, 101 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->post('/registrasi', $this->registerPayload(['fullname' => $this->str($len)]));
            $this->check("5.8 nama {$len} karakter", $ok ? $this->accepted($r, ['fullname']) : $this->rejected($r, 'fullname'));
        }

        foreach (['contoh@kampus.ac.id' => true, 'contohkampus.ac.id' => false, 'contoh@' => false, 'cont@oh@kampus.ac.id' => false] as $email => $ok) {
            $this->flushSession();
            $r = $this->post('/registrasi', $this->registerPayload(['email' => $email]));
            $this->check("5.8 email {$email}", $ok ? $this->accepted($r, ['email']) : $this->rejected($r, 'email'));
        }

        foreach ([7 => false, 8 => true, 64 => true, 65 => false] as $len => $ok) {
            $this->flushSession();
            $pw = str_repeat('k', $len);
            $r = $this->post('/registrasi', $this->registerPayload(['password' => $pw, 'password_confirmation' => $pw]));
            $this->check("5.8 password {$len} karakter", $ok ? $this->accepted($r, ['password']) : $this->rejected($r, 'password'));
            if ($len === 7) {
                $this->check('5.8 pesan password minimal', session('errors')?->first('password') === 'Password minimal 8 karakter.');
            }
            if ($len === 65) {
                $this->check('5.8 pesan password maksimum', session('errors')?->first('password') === 'Password melebihi batas maksimum 64 karakter.');
            }
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $before = User::count();
            $r = $this->post('/registrasi', $this->registerPayload(['fullname' => $value]));
            $this->check("5.8 NEG nama {$name}", $this->rejected($r, 'fullname') && User::count() === $before);
        }

        $this->flushSession();
        $this->makeUser('dosen', User::STATUS_APPROVED, ['email' => 'terdaftar@kampus.ac.id']);
        $r = $this->post('/registrasi', $this->registerPayload(['email' => 'terdaftar@kampus.ac.id']));
        $this->check('5.8 email sudah terdaftar', $this->rejected($r, 'email') && session('errors')->first('email') === 'Email sudah terdaftar.');

        $this->assertNoFailures();
    }

    public function test_5_9_login_bva_ep_neg(): void
    {
        $this->makeUser('dosen', User::STATUS_APPROVED, ['email' => 'dosen@kampus.ac.id', 'password' => 'rahasia123']);

        foreach (['contohkampus.ac.id', 'contoh@', 'cont@oh@kampus.ac.id'] as $email) {
            $this->flushSession();
            $r = $this->post('/login', ['email' => $email, 'password' => 'rahasia123']);
            $this->check("5.9 email {$email}", $this->rejected($r, 'email'));
        }

        $this->flushSession();
        $r = $this->post('/login', ['email' => 'dosen@kampus.ac.id', 'password' => 'salah1234']);
        $this->check('5.9 password salah', $this->rejected($r, 'email') && session('errors')->first('email') === 'Email atau password salah.');

        $this->flushSession();
        $r = $this->post('/login', ['email' => 'dosen@kampus.ac.id', 'password' => str_repeat('k', 7)]);
        $this->check('5.9 password 7', $this->rejected($r, 'password') && session('errors')->first('password') === 'Password minimal 8 karakter.');

        $this->flushSession();
        $r = $this->post('/login', ['email' => 'dosen@kampus.ac.id', 'password' => str_repeat('k', 65)]);
        $this->check('5.9 password 65', $this->rejected($r, 'password') && session('errors')->first('password') === 'Password melebihi batas maksimum 64 karakter.');

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $r = $this->post('/login', ['email' => $value, 'password' => $value]);
            $this->check("5.9 NEG {$name}", $r->status() === 302 && session('errors')?->any() && ! auth()->check());
        }

        $this->flushSession();
        $r = $this->loginLengkap('dosen@kampus.ac.id', 'rahasia123');
        $this->check('5.9 login valid', $r->isRedirect(url('/beranda-dosen')));

        $this->assertNoFailures();
    }

    public function test_5_10_status_registrasi(): void
    {
        $pending = $this->makeUser('dosen', User::STATUS_PENDING);
        $this->actingAs($pending)->get('/dosen/status')->assertOk()->assertSee('Registrasi Anda masih menunggu validasi Admin');

        $approved = $this->makeUser('dosen', User::STATUS_APPROVED);
        $this->actingAs($approved)->get('/dosen/status')->assertOk()->assertSee('Akun Anda telah disetujui');

        $rejected = $this->makeUser('dosen', User::STATUS_REJECTED, ['rejection_reason' => 'NIP tidak sesuai dengan data kepegawaian.']);
        $this->actingAs($rejected)->get('/dosen/status')->assertOk()
            ->assertSee('NIP tidak sesuai dengan data kepegawaian.')
            ->assertSee(route('registrasi.perbaikan'), false);

        $this->actingAs($rejected)->get(route('registrasi.perbaikan'))->assertOk();
        $this->actingAs($rejected)->put(route('registrasi.perbaikan.update'), [
            'fullname' => 'Budi Santoso Perbaikan',
            'email' => $rejected->email,
            'nip' => '1234567890',
        ])->assertRedirect(route('dosen.status'));
        $this->assertSame(User::STATUS_PENDING, $rejected->fresh()->registration_status);
        $this->assertNull($rejected->fresh()->rejection_reason);
    }

    public function test_5_12_profil_bva_ep_neg(): void
    {
        $dosen = $this->makeUser('dosen');

        foreach ([2 => false, 3 => true, 100 => true, 101 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->put('/profil-dosen', ['fullname' => $this->str($len), 'email' => $dosen->email]);
            $this->check("5.12 nama {$len}", $ok ? $this->accepted($r, ['fullname']) : $this->rejected($r, 'fullname'));
        }

        foreach (['contoh@kampus.ac.id' => true, 'contohkampus.ac.id' => false, 'contoh@' => false, 'cont@oh@kampus.ac.id' => false] as $email => $ok) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->put('/profil-dosen', ['fullname' => 'Budi Santoso', 'email' => $email]);
            $this->check("5.12 email {$email}", $ok ? $this->accepted($r, ['email']) : $this->rejected($r, 'email'));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $payload = ['fullname' => $value, 'email' => $value ?: '', 'nip' => $value, 'prodi' => $value, 'fakultas' => $value, 'bio' => $value, 'bidang_penelitian' => $value];
            $r = $this->actingAs($dosen)->put('/profil-dosen', $payload);
            $this->check("5.12 NEG semua kolom {$name}", $this->rejected($r, 'fullname'));
            if (! in_array($name, ['kosong', 'spasi'], true)) {
                foreach (['email', 'nip', 'prodi', 'fakultas', 'bio', 'bidang_penelitian'] as $field) {
                    $this->check("5.12 NEG {$name} kolom {$field}", session('errors')?->has($field));
                }
            }
        }

        $this->assertNoFailures();
    }

    private function publikasiPayload(array $override = []): array
    {
        return array_merge([
            'judul' => 'Judul Publikasi Valid',
            'penulis' => 'Budi Santoso',
            'tahun' => 2024,
            'abstrak' => str_repeat('b', 30),
            'kategori' => 'Jurnal',
            'pdf' => UploadedFile::fake()->create('pub.pdf', 100, 'application/pdf'),
        ], $override);
    }

    public function test_5_13_publikasi_pribadi(): void
    {
        $dosen = $this->makeUser('dosen');

        foreach ([4 => false, 5 => true, 200 => true, 201 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/publikasi', $this->publikasiPayload(['judul' => $this->str($len)]));
            $this->check("5.13 judul {$len}", $ok ? $this->accepted($r, ['judul']) : $this->rejected($r, 'judul'));
        }

        foreach ([19 => false, 20 => true, 5000 => true, 5001 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/publikasi', $this->publikasiPayload(['abstrak' => $this->str($len)]));
            $this->check("5.13 abstrak {$len}", $ok ? $this->accepted($r, ['abstrak']) : $this->rejected($r, 'abstrak'));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/publikasi', $this->publikasiPayload(['judul' => $value]));
            $this->check("5.13 NEG judul {$name}", $this->rejected($r, 'judul'));
        }

        $this->assertNoFailures();
    }

    private function hkiPayload(array $override = []): array
    {
        $this->seq++;

        return array_merge([
            'nomor_sertifikat' => 'EC'.(20240000 + $this->seq),
            'tgl_terbit' => '2024-05-01',
            'judul_sertifikat' => 'Judul HKI Valid',
            'jenis_sertifikat' => 'Hak Cipta',
            'pencipta' => 'Budi Santoso',
        ], $override);
    }

    public function test_5_14_hki_pribadi(): void
    {
        $dosen = $this->makeUser('dosen');

        foreach ([4 => false, 5 => true, 200 => true, 201 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/hki', $this->hkiPayload(['judul_sertifikat' => $this->str($len)]));
            $this->check("5.14 judul {$len}", $ok ? $this->accepted($r, ['judul_sertifikat']) : $this->rejected($r, 'judul_sertifikat'));
        }

        $files = [
            'pdf 2MB' => [UploadedFile::fake()->create('dokumen.pdf', 2048, 'application/pdf'), true],
            'docx' => [UploadedFile::fake()->create('dokumen.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'), true],
            'doc' => [UploadedFile::fake()->create('dokumen.doc', 100, 'application/msword'), true],
            'exe' => [UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload'), false],
            'zip' => [UploadedFile::fake()->create('arsip.zip', 10, 'application/zip'), false],
            'pdf 15MB' => [UploadedFile::fake()->create('dokumen.pdf', 15360, 'application/pdf'), false],
        ];
        foreach ($files as $name => [$file, $ok]) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/hki', $this->hkiPayload(['file_sertifikat' => $file]));
            $this->check("5.14 file {$name}", $ok ? $this->accepted($r, ['file_sertifikat']) : $this->rejected($r, 'file_sertifikat'));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $r = $this->actingAs($dosen)->post('/dosen/hki', $this->hkiPayload(['judul_sertifikat' => $value]));
            $this->check("5.14 NEG judul {$name}", $this->rejected($r, 'judul_sertifikat'));
        }

        $this->assertNoFailures();
    }

    public function test_5_15_dan_5_17_hak_akses(): void
    {
        $dosen = $this->makeUser('dosen');
        $this->actingAs($dosen)->get('/beranda-admin')->assertForbidden()->assertSee('Anda tidak memiliki hak akses ke halaman ini.')->assertSee(url('/beranda-dosen'), false);

        $creator = $this->makeUser('content_creator');
        $this->actingAs($creator)->get('/admin/validasi-registrasi')->assertForbidden()->assertSee('Anda tidak memiliki hak akses')->assertSee(url('/beranda-creator'), false);
    }

    public function test_5_11_5_16_5_17_sesi_berakhir(): void
    {
        $this->get('/beranda-dosen')->assertRedirect(route('login'));
        $this->get(route('login'))->assertSee('Sesi Anda telah berakhir, silakan login kembali.');

        $this->flushSession();
        $this->get('/beranda-creator')->assertRedirect(route('login'));

        $this->flushSession();
        $this->post('/logout')->assertRedirect(route('login'));
    }

    private function entityCases(string $label, string $uri, string $field, array $base, string $model, ?string $nameRule = 'safe'): void
    {
        $admin = $this->makeUser('admin');

        foreach ([2 => false, 3 => true, 100 => true, 101 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post($uri, array_merge($base, [$field => $this->str($len)]));
            $this->check("{$label} {$field} {$len}", $ok ? $this->accepted($r, [$field]) : $this->rejected($r, $field));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $before = $model::count();
            $r = $this->actingAs($admin)->post($uri, array_merge($base, [$field => $value]));
            $this->check("{$label} NEG {$name}", $this->rejected($r, $field) && $model::count() === $before);
        }
    }

    public function test_5_19_program(): void
    {
        $this->entityCases('5.19', '/admin/programs', 'judul', [
            'deskripsi' => str_repeat('d', 25),
            'thumbnail' => UploadedFile::fake()->image('t.jpg'),
            'status' => 'Draft',
        ], Program::class);
        $this->assertNoFailures();
    }

    public function test_5_20_project(): void
    {
        $this->entityCases('5.20', '/admin/projects', 'judul', [
            'deskripsi' => str_repeat('d', 25),
            'thumbnail' => UploadedFile::fake()->image('t.jpg'),
            'status' => 'Draft',
        ], Project::class);
        $this->assertNoFailures();
    }

    public function test_5_21_berita(): void
    {
        $this->entityCases('5.21', '/admin/news', 'judul', [
            'konten' => str_repeat('k', 25),
            'thumbnail' => UploadedFile::fake()->image('t.jpg'),
            'status' => 'Draft',
        ], News::class);
        $this->assertNoFailures();
    }

    public function test_5_22_mitra(): void
    {
        $this->entityCases('5.22', '/admin/partners', 'nama', [
            'status' => 'Draft',
        ], Partner::class);
        $this->assertNoFailures();
    }

    public function test_5_23_tim(): void
    {
        $this->entityCases('5.23', '/admin/teams', 'nama', [
            'jabatan' => 'Peneliti',
            'tipe' => 'Staff',
            'status' => 'Draft',
            'foto' => UploadedFile::fake()->image('f.jpg'),
        ], Team::class);
        $this->assertNoFailures();
    }

    public function test_deskripsi_konten_20_sampai_5000(): void
    {
        $admin = $this->makeUser('admin');
        $cases = [
            ['/admin/programs', 'deskripsi', ['judul' => 'Program Valid', 'thumbnail' => UploadedFile::fake()->image('t.jpg'), 'status' => 'Draft']],
            ['/admin/projects', 'deskripsi', ['judul' => 'Proyek Valid', 'thumbnail' => UploadedFile::fake()->image('t.jpg'), 'status' => 'Draft']],
            ['/admin/news', 'konten', ['judul' => 'Berita Valid', 'thumbnail' => UploadedFile::fake()->image('t.jpg'), 'status' => 'Draft']],
            ['/admin/partners', 'deskripsi', ['nama' => 'Mitra Valid', 'status' => 'Draft']],
        ];
        foreach ($cases as [$uri, $field, $base]) {
            foreach ([19 => false, 20 => true, 5000 => true, 5001 => false] as $len => $ok) {
                $this->flushSession();
                $r = $this->actingAs($admin)->post($uri, array_merge($base, [$field => $this->str($len)]));
                $this->check("{$uri} {$field} {$len}", $ok ? $this->accepted($r, [$field]) : $this->rejected($r, $field));
            }
        }
        $this->assertNoFailures();
    }

    public function test_5_24_hki_admin(): void
    {
        $admin = $this->makeUser('admin');
        $base = fn (array $o = []) => $this->hkiPayload(array_merge(['submission_type' => 'non_member', 'status' => 'Draft'], $o));

        foreach ([4 => false, 5 => true, 200 => true, 201 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/hki', $base(['judul_sertifikat' => $this->str($len)]));
            $this->check("5.24 judul {$len}", $ok ? $this->accepted($r, ['judul_sertifikat']) : $this->rejected($r, 'judul_sertifikat'));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/hki', $base(['judul_sertifikat' => $value]));
            $this->check("5.24 NEG {$name}", $this->rejected($r, 'judul_sertifikat'));
        }

        $files = [
            'pdf 2MB' => [UploadedFile::fake()->create('dokumen.pdf', 2048, 'application/pdf'), true],
            'docx' => [UploadedFile::fake()->create('dokumen.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'), true],
            'exe' => [UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload'), false],
            'pdf 15MB' => [UploadedFile::fake()->create('dokumen.pdf', 15360, 'application/pdf'), false],
        ];
        foreach ($files as $name => [$file, $ok]) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/hki', $base(['file_sertifikat' => $file]));
            $this->check("5.24 file {$name}", $ok ? $this->accepted($r, ['file_sertifikat']) : $this->rejected($r, 'file_sertifikat'));
        }

        $this->flushSession();
        $r = $this->actingAs($admin)->post('/admin/hki', $base(['submission_type' => 'member']));
        $this->check('5.24 member tanpa dosen ditolak', $this->rejected($r, 'user_id'));

        $this->assertNoFailures();
    }

    public function test_5_25_publikasi_admin(): void
    {
        $admin = $this->makeUser('admin');
        $base = fn (array $o = []) => $this->publikasiPayload(array_merge(['submission_type' => 'non_member', 'status' => 'Draft'], $o));

        foreach ([4 => false, 5 => true, 200 => true, 201 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/publications', $base(['judul' => $this->str($len)]));
            $this->check("5.25 judul {$len}", $ok ? $this->accepted($r, ['judul']) : $this->rejected($r, 'judul'));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/publications', $base(['judul' => $value]));
            $this->check("5.25 NEG {$name}", $this->rejected($r, 'judul'));
        }

        foreach ([19 => false, 20 => true, 5000 => true, 5001 => false] as $len => $ok) {
            $this->flushSession();
            $r = $this->actingAs($admin)->post('/admin/publications', $base(['abstrak' => $this->str($len)]));
            $this->check("5.25 abstrak {$len}", $ok ? $this->accepted($r, ['abstrak']) : $this->rejected($r, 'abstrak'));
        }

        $this->assertNoFailures();
    }

    public function test_5_26_validasi_registrasi(): void
    {
        $admin = $this->makeUser('admin');

        $calon = $this->makeUser('dosen', User::STATUS_PENDING);
        $this->actingAs($admin)->post(route('admin.validasi.approve', $calon->id))->assertRedirect(route('admin.validasi.index'));
        $this->check('5.26 approve status', $calon->fresh()->registration_status === User::STATUS_APPROVED);
        $this->check('5.26 approve notifikasi', $calon->notifications()->count() === 1);

        foreach ([9 => false, 10 => true, 500 => true, 501 => false] as $len => $ok) {
            $this->flushSession();
            $user = $this->makeUser('dosen', User::STATUS_PENDING);
            $r = $this->actingAs($admin)->post(route('admin.validasi.reject', $user->id), ['alasan' => $this->str($len)]);
            $status = $user->fresh()->registration_status;
            $this->check("5.26 alasan {$len}", $ok
                ? ($status === User::STATUS_REJECTED && $user->fresh()->rejection_reason === $this->str($len))
                : ($this->rejected($r, 'alasan') && $status === User::STATUS_PENDING));
        }

        foreach (['kosong' => '', 'spasi' => '   ', 'sqli' => self::SQLI, 'emoji' => self::EMOJI, 'xss' => self::XSS] as $name => $value) {
            $this->flushSession();
            $user = $this->makeUser('dosen', User::STATUS_PENDING);
            $r = $this->actingAs($admin)->post(route('admin.validasi.reject', $user->id), ['alasan' => $value]);
            $this->check("5.26 NEG alasan {$name}", $this->rejected($r, 'alasan') && $user->fresh()->registration_status === User::STATUS_PENDING);
        }

        $ditolak = $this->makeUser('dosen', User::STATUS_PENDING);
        $this->actingAs($admin)->post(route('admin.validasi.reject', $ditolak->id), ['alasan' => 'Data NIP tidak valid.']);
        $notif = $ditolak->notifications()->first();
        $this->check('5.26 reject notifikasi beserta alasan', $notif && ($notif->data['alasan'] ?? null) === 'Data NIP tidak valid.');

        $this->assertNoFailures();
    }

    public function test_5_9_lupa_password_sampai_login_dengan_password_baru(): void
    {
        Notification::fake();
        $user = $this->makeUser('dosen', User::STATUS_APPROVED, ['email' => 'lupa@kampus.ac.id']);

        $this->get(route('login'))->assertSee(route('password.request'), false);
        $this->get(route('password.request'))->assertOk()->assertSee('Lupa password');
        $this->post(route('password.email'), ['email' => 'lupa@kampus.ac.id'])->assertSessionHas('success');

        $token = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use (&$token, $user) {
            $token = $notification->token;
            $mail = $notification->toMail($user);

            return str_contains($mail->subject, 'Reset Password') && str_contains($mail->actionUrl, $token);
        });

        $this->get(route('password.reset', ['token' => $token, 'email' => 'lupa@kampus.ac.id']))->assertOk();
        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'lupa@kampus.ac.id',
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBaru123',
        ])->assertRedirect(route('login'));

        $this->loginLengkap('lupa@kampus.ac.id', 'PasswordBaru123')->assertRedirect(url('/beranda-dosen'));
    }

    public function test_email_tidak_terdaftar_tetap_mendapat_pesan_umum(): void
    {
        Notification::fake();
        $this->post(route('password.email'), ['email' => 'tidakada@kampus.ac.id'])->assertSessionHas('success');
        Notification::assertNothingSent();
    }

    public function test_gagal_simpan_menampilkan_pesan_dan_coba_lagi(): void
    {
        $dosen = $this->makeUser('dosen');
        Schema::drop('publications');

        $this->actingAs($dosen)
            ->from('/dosen/publikasi/create')
            ->post('/dosen/publikasi', $this->publikasiPayload())
            ->assertRedirect('/dosen/publikasi/create')
            ->assertSessionHas('error', 'Publikasi gagal disimpan. Silakan coba lagi.')
            ->assertSessionHas('retry', true);

        $this->actingAs($dosen)->get('/dosen/publikasi/create')->assertSee('Coba Lagi')->assertSee('Judul Publikasi Valid');
    }

    public function test_gagal_simpan_profil_menampilkan_pesan_khusus(): void
    {
        $dosen = $this->makeUser('dosen');
        Route::put('/uji-gagal-profil', fn () => throw new \PDOException('koneksi terputus'))->middleware('web')->name('profil.uji');

        $this->actingAs($dosen)
            ->from('/profil-dosen')
            ->put('/uji-gagal-profil', ['fullname' => 'Budi Santoso'])
            ->assertRedirect('/profil-dosen')
            ->assertSessionHas('error', 'Perubahan profil gagal disimpan. Silakan coba lagi.');
    }

    public function test_sesi_kedaluwarsa_saat_mengirim_form_diarahkan_ke_login(): void
    {
        Route::post('/uji-sesi-habis', fn () => throw new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.'))->middleware('web');

        $this->post('/uji-sesi-habis')
            ->assertRedirect(route('login'))
            ->assertSessionHas('warning', 'Sesi Anda telah berakhir, silakan login kembali.');
    }

    public function test_login_setelah_disetujui_menampilkan_notifikasi(): void
    {
        $admin = $this->makeUser('admin');
        $calon = $this->makeUser('dosen', User::STATUS_PENDING, ['email' => 'calon@kampus.ac.id']);

        $this->actingAs($admin)->post(route('admin.validasi.approve', $calon->id));
        Auth::logout();

        $this->loginLengkap('calon@kampus.ac.id', 'rahasia123')
            ->assertRedirect(url('/beranda-dosen'))
            ->assertSessionHas('success', 'Selamat, registrasi akun Anda telah disetujui oleh Admin.');

        $this->assertNotNull($calon->notifications()->first()->read_at);
    }

    public function test_notifikasi_registrasi_tampil_dan_membuka_status(): void
    {
        $dosen = $this->makeUser('dosen');
        $dosen->notify(new RegistrationStatusNotification(User::STATUS_APPROVED));

        $this->actingAs($dosen)->get(route('dosen.notifications.index'))->assertOk()->assertSee('Registrasi akun Anda telah disetujui oleh Admin.');
        $this->actingAs($dosen)->get(url('/beranda-dosen'))->assertOk()->assertSee('Registrasi akun Anda telah disetujui oleh Admin.');

        $id = $dosen->notifications()->first()->id;
        $this->actingAs($dosen)->get(route('dosen.notifications.read', $id))->assertRedirect(route('dosen.status'));
    }

    public function test_perbaikan_registrasi_content_creator(): void
    {
        $creator = $this->makeUser('content_creator', User::STATUS_REJECTED, ['nip' => null, 'rejection_reason' => 'Nama tidak sesuai identitas resmi.']);

        $this->actingAs($creator)->get(route('registrasi.perbaikan'))->assertOk()->assertSee('Nama tidak sesuai identitas resmi.')->assertDontSee('name="nip"', false);
        $this->actingAs($creator)->put(route('registrasi.perbaikan.update'), [
            'fullname' => 'Creator Diperbaiki',
            'email' => $creator->email,
        ])->assertRedirect(route('dosen.status'));

        $this->assertSame(User::STATUS_PENDING, $creator->fresh()->registration_status);

        $approved = $this->makeUser('dosen');
        $this->actingAs($approved)->get(route('registrasi.perbaikan'))->assertRedirect(route('dosen.status'));
    }

    public function test_halaman_gagal_dimuat_dengan_tombol_muat_ulang(): void
    {
        config(['app.debug' => false]);
        Route::get('/uji-halaman-gagal', fn () => throw new \RuntimeException('gangguan'))->middleware('web');

        $this->get('/uji-halaman-gagal')->assertStatus(500)->assertSee('Halaman gagal dimuat')->assertSee('Muat Ulang');
    }

    public function test_dashboard_menampilkan_ringkasan_dan_pesan_data_kosong(): void
    {
        $dosen = $this->makeUser('dosen');
        $this->actingAs($dosen)->get('/beranda-dosen')->assertOk()
            ->assertSee('Status Registrasi')
            ->assertSee('Notifikasi')
            ->assertSee('Belum ada data publikasi dan HKI')
            ->assertSee('Kelola Publikasi')
            ->assertSee('Kelola HKI')
            ->assertSee('sc-btn sc-btn--primary sc-btn--sm', false);

        $creator = $this->makeUser('content_creator');
        $this->actingAs($creator)->get('/beranda-creator')->assertOk()->assertSee('Belum terdapat data.');

        $admin = $this->makeUser('admin');
        $this->actingAs($admin)->get('/beranda-admin')->assertOk()->assertSee('Registrasi Menunggu');
    }

    public function test_5_15_02_halaman_tanpa_data_menampilkan_data_belum_tersedia(): void
    {
        $dosen = $this->makeUser('dosen');

        foreach (['/program-dosen', '/project-dosen', '/news-dosen', '/mitra-dosen', '/team-dosen'] as $page) {
            $this->actingAs($dosen)->get($page)->assertOk()->assertSee('Data belum tersedia');
        }

        Auth::logout();

        foreach (['/program-user', '/project-user', '/news-user', '/publication-user', '/mitra-user', '/team-user'] as $page) {
            $this->get($page)->assertOk()->assertSee('Data belum tersedia');
        }
    }

    public function test_semua_form_menampilkan_validasi_dan_konfirmasi_simpan(): void
    {
        $admin = $this->makeUser('admin');
        $dosen = $this->makeUser('dosen');

        $program = Program::create(['judul' => 'Program Uji', 'deskripsi' => str_repeat('d', 30), 'status' => 'Draft']);
        $project = Project::create(['judul' => 'Proyek Uji', 'deskripsi' => str_repeat('d', 30), 'status' => 'Draft']);
        $news = News::create(['judul' => 'Berita Uji', 'konten' => str_repeat('k', 30), 'status' => 'Draft']);
        $partner = Partner::create(['nama' => 'Mitra Uji', 'status' => 'Draft']);
        $team = Team::create(['nama' => 'Anggota Uji', 'jabatan' => 'Peneliti', 'tipe' => 'Staff', 'status' => 'Draft']);
        $hki = Hki::create($this->hkiPayload(['submission_type' => 'non_member', 'status' => 'Draft']));
        $publication = Publication::create([
            'judul' => 'Publikasi Uji', 'penulis' => 'Budi Santoso', 'tahun' => 2024, 'abstrak' => str_repeat('a', 30),
            'kategori' => 'Jurnal', 'pdf_path' => 'publications/x.pdf', 'status' => 'Draft', 'submission_type' => 'non_member',
        ]);

        $pages = [
            '/admin/programs/create', "/admin/programs/{$program->id}/edit",
            '/admin/projects/create', "/admin/projects/{$project->id}/edit",
            '/admin/news/create', "/admin/news/{$news->id}/edit",
            '/admin/partners/create', "/admin/partners/{$partner->id}/edit",
            '/admin/teams/create', "/admin/teams/{$team->id}/edit",
            '/admin/hki/create', "/admin/hki/{$hki->id}/edit",
            '/admin/publications/create', "/admin/publications/{$publication->id}/edit",
        ];
        foreach ($pages as $page) {
            $this->actingAs($admin)->get($page)->assertOk()->assertSee('data-confirm-submit', false)->assertSee('data-rules', false);
        }

        foreach (['/admin/hki/create', '/admin/publications/create'] as $page) {
            $this->actingAs($admin)->get($page)->assertSee('Apakah data yang diinputkan sudah benar?')->assertSee('Apakah Anda yakin ingin menyimpan perubahan?');
        }

        foreach (['/dosen/publikasi/create', '/dosen/hki/create', '/profil-dosen'] as $page) {
            $this->actingAs($dosen)->get($page)->assertOk()->assertSee('data-confirm-submit', false);
        }
    }

    public function test_halaman_validasi_menampilkan_form_alasan_penolakan(): void
    {
        $admin = $this->makeUser('admin');
        $calon = $this->makeUser('dosen', User::STATUS_PENDING);

        $this->actingAs($admin)->get('/admin/validasi-registrasi')->assertOk()
            ->assertSee('id="tolak-'.$calon->id.'"', false)
            ->assertSee(route('admin.validasi.reject', $calon->id), false);

        $this->actingAs($admin)->from('/admin/validasi-registrasi')
            ->post(route('admin.validasi.reject', $calon->id), ['alasan' => 'pendek'])
            ->assertRedirect('/admin/validasi-registrasi');

        $this->actingAs($admin)->get('/admin/validasi-registrasi')->assertSee('data-show-on-load', false)->assertSee('Alasan penolakan minimal 10 karakter.');
    }

    public function test_login_dan_reset_password_tidak_peka_huruf_besar_kecil(): void
    {
        Notification::fake();
        $user = $this->makeUser('dosen', User::STATUS_APPROVED, ['email' => 'dosenA@smartcity.ac.id']);

        $this->loginLengkap('dosenA@smartcity.ac.id', 'rahasia123')->assertRedirect(url('/beranda-dosen'));
        Auth::logout();
        $this->loginLengkap('DOSENA@smartcity.ac.id', 'rahasia123')->assertRedirect(url('/beranda-dosen'));
        Auth::logout();

        $this->post(route('password.email'), ['email' => 'dosena@smartcity.ac.id'])->assertSessionHas('success');
        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_halaman_error_berbahasa_indonesia(): void
    {
        $this->get('/halaman-yang-tidak-ada')->assertNotFound()->assertSee('Halaman tidak ditemukan');
    }
}
