<?php

namespace Tests\Feature;

use App\Models\LoginConfirmation;
use App\Models\TrustedDevice;
use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PerangkatTepercayaTest extends TestCase
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
            'email' => "perangkat{$this->seq}@kampus.ac.id",
            'nip' => (string) (800000 + $this->seq),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => User::STATUS_APPROVED,
        ], $extra));
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

    private function loginPertamaDanKonfirmasi(User $user, string $tujuan): string
    {
        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));
        $this->assertGuest();

        $this->get(route('login.konfirmasi.menunggu'))->assertOk()
            ->assertSee('login untuk pertama kali atau dari perangkat/browser yang belum dikenali');

        $response = $this->post(route('login.konfirmasi.setujui', $this->tokenTerakhir($user)))
            ->assertRedirect(url($tujuan));
        $this->assertAuthenticatedAs($user);

        $cookie = $response->getCookie(TrustedDevice::COOKIE);
        $this->assertNotNull($cookie);

        return $cookie->getValue();
    }

    private function logout(): void
    {
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        Notification::fake();
    }

    public function test_login_pertama_wajib_konfirmasi_lalu_perangkat_yang_sama_tidak_diminta_lagi(): void
    {
        $user = $this->makeUser();

        $perangkat = $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->assertDatabaseCount('trusted_devices', 1);

        $this->logout();

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(url('/beranda-dosen'));

        $this->assertAuthenticatedAs($user);
        Notification::assertNotSentTo($user, LoginConfirmationNotification::class);
        $this->assertSame(1, LoginConfirmation::where('user_id', $user->id)->count());
    }

    public function test_perangkat_baru_tetap_wajib_konfirmasi(): void
    {
        $user = $this->makeUser();
        $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        $this->withCookie(TrustedDevice::COOKIE, str_repeat('b', 64))
            ->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        $this->assertGuest();
        Notification::assertSentTo($user, LoginConfirmationNotification::class);
    }

    public function test_tanpa_cookie_perangkat_tetap_wajib_konfirmasi(): void
    {
        $user = $this->makeUser();
        $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        Notification::assertSentTo($user, LoginConfirmationNotification::class);
    }

    public function test_perangkat_dikenali_per_akun(): void
    {
        $a = $this->makeUser();
        $b = $this->makeUser();

        $perangkat = $this->loginPertamaDanKonfirmasi($a, '/beranda-dosen');
        $this->logout();

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post('/login', ['email' => $b->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        Notification::assertSentTo($b, LoginConfirmationNotification::class);
    }

    public function test_perangkat_yang_lama_tidak_dipakai_wajib_konfirmasi_ulang(): void
    {
        $user = $this->makeUser();
        $perangkat = $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        TrustedDevice::query()->update(['last_used_at' => now()->subDays(31)]);

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));
    }

    public function test_akun_pending_dan_admin_mengikuti_aturan_yang_sama(): void
    {
        $pending = $this->makeUser('dosen', ['registration_status' => User::STATUS_PENDING]);
        $perangkat = $this->loginPertamaDanKonfirmasi($pending, '/dosen/status');
        $this->logout();

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post('/login', ['email' => $pending->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('dosen.status'));
        Notification::assertNotSentTo($pending, LoginConfirmationNotification::class);
        $this->post('/logout');

        $admin = $this->makeUser('admin');
        $this->post('/login', ['email' => $admin->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.konfirmasi.menunggu'));
        Notification::assertSentTo($admin, LoginConfirmationNotification::class);
    }

    public function test_password_salah_tetap_ditolak_walau_perangkat_dikenali(): void
    {
        $user = $this->makeUser();
        $perangkat = $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->from('/login')
            ->post('/login', ['email' => $user->email, 'password' => 'salahsalah'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Email atau password salah.']);

        $this->assertGuest();
    }

    public function test_akun_lama_tidak_aktif_tetap_wajib_konfirmasi_email(): void
    {
        $user = $this->makeUser();
        $perangkat = $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        $user->forceFill(['last_login_at' => now()->subDays(120)])->save();
        TrustedDevice::query()->update(['last_used_at' => now()]);

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('login.keaktifan.tampil'));

        $this->withCookie(TrustedDevice::COOKIE, $perangkat)
            ->post(route('login.keaktifan.konfirmasi'))
            ->assertRedirect(route('login.konfirmasi.menunggu'));

        Notification::assertSentTo($user, LoginConfirmationNotification::class);
    }

    public function test_bukan_saya_mencabut_semua_perangkat_tepercaya(): void
    {
        $user = $this->makeUser();
        $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();
        $this->assertDatabaseCount('trusted_devices', 1);

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123']);
        $this->flushSession();
        $this->post(route('login.konfirmasi.tolak', $this->tokenTerakhir($user)))->assertOk();

        $this->assertDatabaseCount('trusted_devices', 0);
    }

    public function test_reset_password_mencabut_semua_perangkat_tepercaya(): void
    {
        $user = $this->makeUser();
        $this->loginPertamaDanKonfirmasi($user, '/beranda-dosen');
        $this->logout();

        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('trusted_devices', 0);
    }

    public function test_masa_berlaku_nol_membuat_setiap_login_wajib_konfirmasi(): void
    {
        config(['login.trusted_device_days' => 0]);

        $user = $this->makeUser();
        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123']);
        $this->post(route('login.konfirmasi.setujui', $this->tokenTerakhir($user)))->assertRedirect(url('/beranda-dosen'));

        $this->assertDatabaseCount('trusted_devices', 0);
    }
}