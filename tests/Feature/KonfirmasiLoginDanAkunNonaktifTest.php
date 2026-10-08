<?php

namespace Tests\Feature;

use App\Models\LoginConfirmation;
use App\Models\SuspiciousLoginReport;
use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\SuspiciousLoginNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class KonfirmasiLoginDanAkunNonaktifTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    private function makeUser(string $role = 'dosen', array $extra = []): User
    {
        $this->seq++;

        return User::create(array_merge([
            'fullname' => 'Pengguna Uji',
            'email' => "pengguna{$this->seq}@kampus.ac.id",
            'nip' => (string) (700000 + $this->seq),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => User::STATUS_APPROVED,
        ], $extra));
    }

    private function mulaiLogin(User $user, string $password = 'rahasia123'): string
    {
        $this->post('/login', ['email' => $user->email, 'password' => $password])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        $this->assertGuest();

        return $this->tokenTerakhir($user);
    }

    private function tokenTerakhir(User $user): string
    {
        $token = null;

        Notification::assertSentTo($user, LoginConfirmationNotification::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        return $token;
    }

    public function test_5_9_01_login_valid_mengirim_email_konfirmasi_lalu_masuk_setelah_ya(): void
    {
        $user = $this->makeUser('dosen', ['email' => 'budi@kampus.ac.id']);

        $token = $this->mulaiLogin($user);
        $id = session(LoginConfirmation::SESSION_KEY);

        $this->get(route('login.konfirmasi.menunggu'))->assertOk()
            ->assertSee('Konfirmasi Login Lewat Email')
            ->assertSee('bu***@kampus.ac.id')
            ->assertSee('Kirim Ulang Email');
        $this->getJson(route('login.konfirmasi.status'))->assertExactJson(['status' => 'pending']);

        $this->flushSession();

        $this->get(route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'ini-saya']))->assertOk()
            ->assertSee('Konfirmasi Login')
            ->assertSee('budi@kampus.ac.id')
            ->assertSee('Ya, Ini Saya');

        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Login Berhasil Dikonfirmasi');
        $this->assertGuest();

        $this->withSession([LoginConfirmation::SESSION_KEY => $id]);
        $this->getJson(route('login.konfirmasi.status'))->assertExactJson(['status' => 'approved']);
        $this->post(route('login.konfirmasi.lanjut'))->assertRedirect(url('/beranda-dosen'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame(LoginConfirmation::STATUS_USED, LoginConfirmation::findOrFail($id)->status);
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_konfirmasi_di_browser_yang_sama_langsung_masuk_dashboard(): void
    {
        $admin = $this->makeUser('admin');
        $token = $this->mulaiLogin($admin);

        $this->post(route('login.konfirmasi.setujui', $token))->assertRedirect(url('/beranda-admin'));
        $this->assertAuthenticatedAs($admin);
        $this->get(route('login.konfirmasi.menunggu'))->assertRedirect(url('/beranda-admin'));

        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Login Sudah Dikonfirmasi');
    }

    public function test_email_konfirmasi_berisi_pilihan_ya_ini_saya_dan_bukan_saya(): void
    {
        $user = $this->makeUser('dosen', ['fullname' => 'Budi Santoso']);
        $token = $this->mulaiLogin($user);

        Notification::assertSentTo($user, LoginConfirmationNotification::class, function ($notification) use ($user, $token) {
            $mail = $notification->toMail($user);
            $html = (string) $mail->render();

            return $mail->subject === 'Konfirmasi Login Akun CoE Smart City'
                && str_contains($html, 'Halo, Budi Santoso!')
                && str_contains($html, 'Ya, Ini Saya')
                && str_contains($html, 'Bukan Saya')
                && str_contains($html, route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'ini-saya']))
                && str_contains($html, route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'bukan-saya']));
        });
    }

    public function test_5_9_04_akun_lama_tidak_aktif_menampilkan_konfirmasi_keaktifan_lalu_login_berlanjut(): void
    {
        $user = $this->makeUser('dosen', ['email' => 'lama@kampus.ac.id']);
        $user->forceFill(['last_login_at' => now()->subDays(config('login.dormant_days') + 30)])->save();

        $this->post('/login', ['email' => 'lama@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login.keaktifan.tampil'));
        $this->assertGuest();
        Notification::assertNothingSent();

        $this->get(route('login.keaktifan.tampil'))->assertOk()
            ->assertSee('Konfirmasi Keaktifan Akun')
            ->assertSee('terdeteksi tidak aktif')
            ->assertSee('Ya, Lanjutkan Login');

        $this->post(route('login.keaktifan.konfirmasi'))->assertRedirect(route('login.konfirmasi.menunggu'));
        $token = $this->tokenTerakhir($user);

        $this->post(route('login.konfirmasi.setujui', $token))->assertRedirect(url('/beranda-dosen'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->fresh()->last_login_at->isToday());
        $this->assertFalse($user->fresh()->isDormant());

        $this->post(route('logout'));
        $this->flushSession();

        $this->post('/login', ['email' => 'lama@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));
    }

    public function test_akun_lama_tanpa_riwayat_login_dihitung_dari_tanggal_daftar(): void
    {
        $user = $this->makeUser();
        $user->forceFill(['created_at' => now()->subDays(config('login.dormant_days') + 1), 'last_login_at' => null])->save();

        $this->assertTrue($user->fresh()->isDormant());
        $this->assertFalse($this->makeUser()->isDormant());
    }

    public function test_konfirmasi_keaktifan_bisa_dibatalkan_dan_kedaluwarsa(): void
    {
        $user = $this->makeUser();
        $user->forceFill(['last_login_at' => now()->subYear()])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.keaktifan.tampil'));
        $this->post(route('login.keaktifan.batal'))->assertRedirect(route('login'))->assertSessionHas('info', 'Login dibatalkan.');
        $this->get(route('login.keaktifan.tampil'))->assertRedirect(route('login'));

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123']);
        $this->travel(11)->minutes();
        $this->post(route('login.keaktifan.konfirmasi'))->assertRedirect(route('login'))
            ->assertSessionHas('warning', 'Waktu konfirmasi keaktifan akun telah habis. Silakan login kembali.');
        $this->assertGuest();
        Notification::assertNothingSent();
    }

    public function test_5_9_05_bukan_saya_membatalkan_login_mengunci_sesi_dan_melapor_ke_admin(): void
    {
        $admin = $this->makeUser('admin', ['email' => 'admin.uji@kampus.ac.id']);
        $user = $this->makeUser('dosen', ['email' => 'korban@kampus.ac.id']);
        $token = $this->mulaiLogin($user);
        $id = session(LoginConfirmation::SESSION_KEY);

        $this->flushSession();

        $this->get(route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'bukan-saya']))->assertOk()
            ->assertSee('Bukan Anda yang Login?')
            ->assertSee('Bukan Saya, Tolak Login');

        $this->post(route('login.konfirmasi.tolak', $token))->assertOk()
            ->assertSee('Login Berhasil Dibatalkan')
            ->assertSee('dikunci sementara')
            ->assertSee('Laporan aktivitas mencurigakan')
            ->assertSee(route('password.request'), false);

        $this->assertSame(LoginConfirmation::STATUS_REJECTED, LoginConfirmation::findOrFail($id)->status);
        $this->assertTrue($user->fresh()->isLocked());

        $report = SuspiciousLoginReport::firstOrFail();
        $this->assertSame('korban@kampus.ac.id', $report->email);
        $this->assertNotEmpty($report->device);
        $this->assertNotNull($report->login_at);

        Notification::assertSentTo($admin, SuspiciousLoginNotification::class, function ($notification) use ($admin) {
            $mail = $notification->toMail($admin);
            $html = (string) $mail->render();

            return $mail->subject === 'Laporan Aktivitas Login Mencurigakan'
                && str_contains($html, 'korban@kampus.ac.id')
                && str_contains($html, 'Perangkat:')
                && str_contains($html, 'Waktu:');
        });
        Notification::assertNotSentTo($user, SuspiciousLoginNotification::class);

        $this->withSession([LoginConfirmation::SESSION_KEY => $id]);
        $this->getJson(route('login.konfirmasi.status'))->assertExactJson(['status' => 'rejected']);
        $this->post(route('login.konfirmasi.lanjut'))->assertRedirect(route('login.konfirmasi.menunggu'));
        $this->get(route('login.konfirmasi.menunggu'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertSee('Proses login dibatalkan karena pemilik akun memilih');
        $this->assertGuest();

        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Login Sudah Ditolak');
        $this->assertGuest();

        $this->from(route('login'))->post('/login', ['email' => 'korban@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => $user->fresh()->lockMessage()]);
        $this->assertGuest();

        $this->actingAs($admin)->get('/beranda-admin')->assertOk()
            ->assertSee('Laporan Aktivitas Login Mencurigakan')
            ->assertSee('korban@kampus.ac.id')
            ->assertSee('Dikunci sampai');
    }

    public function test_bukan_saya_dari_browser_yang_sama_tidak_membuat_login(): void
    {
        $user = $this->makeUser();
        $token = $this->mulaiLogin($user);

        $this->post(route('login.konfirmasi.tolak', $token))->assertOk()->assertSee('Login Berhasil Dibatalkan');
        $this->assertGuest();
        $this->get(route('login.konfirmasi.menunggu'))->assertRedirect(route('login'));
    }

    public function test_kunci_berakhir_otomatis_dan_reset_password_membuka_kunci(): void
    {
        $user = $this->makeUser('dosen', ['email' => 'terkunci@kampus.ac.id']);
        $token = $this->mulaiLogin($user);
        $this->flushSession();
        $this->post(route('login.konfirmasi.tolak', $token));

        $this->assertTrue($user->fresh()->isLocked());

        $this->travel((int) config('login.lock_minutes') + 1)->minutes();
        $this->assertFalse($user->fresh()->isLocked());
        $this->post('/login', ['email' => 'terkunci@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        $this->travelBack();
        $this->flushSession();
        $user->forceFill(['locked_until' => now()->addMinutes(30)])->save();

        $this->post(route('password.email'), ['email' => 'terkunci@kampus.ac.id']);
        $reset = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use (&$reset) {
            $reset = $notification->token;

            return true;
        });

        $this->post(route('password.update'), [
            'token' => $reset,
            'email' => 'terkunci@kampus.ac.id',
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBaru123',
        ])->assertRedirect(route('login'));

        $this->assertFalse($user->fresh()->isLocked());
    }

    public function test_sesi_akun_yang_dikunci_langsung_berakhir(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/beranda-dosen')->assertOk();

        $user->forceFill(['locked_until' => now()->addMinutes(30)])->save();

        $this->get('/beranda-dosen')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route('login'))->assertSee('Akun Anda dikunci sementara');
    }

    public function test_tautan_kedaluwarsa_tidak_bisa_dipakai(): void
    {
        $user = $this->makeUser();
        $token = $this->mulaiLogin($user);

        $this->travel(LoginConfirmation::lifetime() + 1)->minutes();

        $this->get(route('login.konfirmasi.tinjau', ['token' => $token, 'aksi' => 'ini-saya']))->assertOk()->assertSee('Tautan Kedaluwarsa');
        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Tautan Kedaluwarsa');
        $this->getJson(route('login.konfirmasi.status'))->assertExactJson(['status' => 'expired']);
        $this->get(route('login.konfirmasi.menunggu'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertSee('Waktu konfirmasi login telah habis. Silakan login kembali.');
        $this->assertGuest();
    }

    public function test_tautan_tidak_valid_menampilkan_pesan(): void
    {
        $this->get(route('login.konfirmasi.tinjau', ['token' => str_repeat('a', 64), 'aksi' => 'ini-saya']))
            ->assertNotFound()
            ->assertSee('Tautan Tidak Valid');
    }

    public function test_kirim_ulang_mengganti_tautan_dan_dibatasi_waktunya(): void
    {
        $user = $this->makeUser();
        $tokenLama = $this->mulaiLogin($user);

        Notification::fake();

        $this->post(route('login.konfirmasi.kirim-ulang'))->assertRedirect(route('login.konfirmasi.menunggu'))
            ->assertSessionHas('success');

        $tokenBaru = $this->tokenTerakhir($user);
        $this->assertNotSame($tokenLama, $tokenBaru);

        $this->get(route('login.konfirmasi.tinjau', ['token' => $tokenLama, 'aksi' => 'ini-saya']))->assertNotFound();
        $this->get(route('login.konfirmasi.tinjau', ['token' => $tokenBaru, 'aksi' => 'ini-saya']))->assertOk();

        $this->post(route('login.konfirmasi.kirim-ulang'))->assertRedirect(route('login.konfirmasi.menunggu'))
            ->assertSessionHasErrors('konfirmasi');
    }

    public function test_belum_dikonfirmasi_tidak_bisa_lanjut_dan_bisa_dibatalkan(): void
    {
        $user = $this->makeUser();
        $token = $this->mulaiLogin($user);

        $this->post(route('login.konfirmasi.lanjut'))->assertRedirect(route('login.konfirmasi.menunggu'))
            ->assertSessionHasErrors('konfirmasi');
        $this->assertGuest();

        $this->post(route('login.konfirmasi.batal'))->assertRedirect(route('login'))->assertSessionHas('info', 'Login dibatalkan.');
        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Permintaan Login Tidak Berlaku');
        $this->assertGuest();
    }

    public function test_password_salah_tidak_mengirim_email_konfirmasi(): void
    {
        $user = $this->makeUser();

        $this->from(route('login'))->post('/login', ['email' => $user->email, 'password' => 'salah12345'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Email atau password salah.']);

        Notification::assertNothingSent();
        $this->assertSame(0, LoginConfirmation::count());
    }

    public function test_konfirmasi_email_bisa_dimatikan_lewat_konfigurasi(): void
    {
        config(['login.email_confirmation' => false]);
        $user = $this->makeUser('content_creator');

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])->assertRedirect(url('/beranda-creator'));
        $this->assertAuthenticatedAs($user);
        Notification::assertNothingSent();
    }

    public function test_akun_yang_dinonaktifkan_admin_tidak_bisa_login(): void
    {
        $admin = $this->makeUser('admin');
        $dosen = $this->makeUser('dosen', ['email' => 'nonaktif@kampus.ac.id']);

        $this->actingAs($admin)->from('/admin/validasi-registrasi')
            ->post(route('admin.validasi.deactivate', $dosen->id))
            ->assertRedirect('/admin/validasi-registrasi')
            ->assertSessionHas('success');
        $this->assertFalse($dosen->fresh()->is_active);

        $this->post(route('logout'));
        $this->flushSession();

        $this->from(route('login'))->post('/login', ['email' => 'nonaktif@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => User::INACTIVE_MESSAGE]);
        $this->assertGuest();
        Notification::assertNotSentTo($dosen, LoginConfirmationNotification::class);
        $this->get(route('login'))->assertSee('Akun Anda telah dinonaktifkan oleh Admin');

        $this->actingAs($admin)->from('/admin/validasi-registrasi')
            ->post(route('admin.validasi.activate', $dosen->id))
            ->assertRedirect('/admin/validasi-registrasi');
        $this->assertTrue($dosen->fresh()->is_active);

        $this->post(route('logout'));
        $this->flushSession();

        $this->post('/login', ['email' => 'nonaktif@kampus.ac.id', 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));
    }

    public function test_akun_yang_dinonaktifkan_saat_login_langsung_dikeluarkan(): void
    {
        $dosen = $this->makeUser();

        $this->actingAs($dosen)->get('/beranda-dosen')->assertOk();

        $dosen->forceFill(['is_active' => false])->save();

        $this->get('/beranda-dosen')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route('login'))->assertSee('Akun Anda telah dinonaktifkan oleh Admin');
    }

    public function test_menonaktifkan_akun_membatalkan_konfirmasi_login_yang_menunggu(): void
    {
        $admin = $this->makeUser('admin');
        $dosen = $this->makeUser();
        $token = $this->mulaiLogin($dosen);
        $this->flushSession();

        $this->actingAs($admin)->post(route('admin.validasi.deactivate', $dosen->id));
        $this->post(route('logout'));
        $this->flushSession();

        $this->post(route('login.konfirmasi.setujui', $token))->assertOk()->assertSee('Permintaan Login Tidak Berlaku');
        $this->assertGuest();
    }

    public function test_hanya_admin_yang_bisa_menonaktifkan_akun(): void
    {
        $creator = $this->makeUser('content_creator');
        $dosen = $this->makeUser();

        $this->actingAs($creator)->post(route('admin.validasi.deactivate', $dosen->id))->assertForbidden();
        $this->actingAs($dosen)->post(route('admin.validasi.activate', $creator->id))->assertForbidden();
        $this->assertTrue($dosen->fresh()->isActive());
    }

    public function test_halaman_validasi_menampilkan_aksi_dan_filter_nonaktif(): void
    {
        $admin = $this->makeUser('admin');
        $aktif = $this->makeUser('dosen', ['fullname' => 'Dosen Aktif']);
        $nonaktif = $this->makeUser('content_creator', ['fullname' => 'Creator Nonaktif']);
        $nonaktif->forceFill(['is_active' => false])->save();

        $this->actingAs($admin)->get('/admin/validasi-registrasi')->assertOk()
            ->assertSee(route('admin.validasi.deactivate', $aktif->id), false)
            ->assertSee(route('admin.validasi.activate', $nonaktif->id), false)
            ->assertSee('Nonaktifkan')
            ->assertSee('Aktifkan');

        $this->actingAs($admin)->get('/admin/validasi-registrasi?status=inactive')->assertOk()
            ->assertSee('Creator Nonaktif')
            ->assertDontSee('Dosen Aktif');
    }

    public function test_dashboard_admin_tanpa_laporan_menampilkan_keterangan(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->get('/beranda-admin')->assertOk()
            ->assertSee('Belum ada laporan aktivitas login mencurigakan.');
    }
}
