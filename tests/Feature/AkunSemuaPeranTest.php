<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AkunSemuaPeranTest extends TestCase
{
    use RefreshDatabase;

    public static function peran(): array
    {
        return [
            'admin' => ['admin', '/admin/akun', '/admin/akun/password'],
            'creator' => ['content_creator', '/creator/akun', '/creator/akun/password'],
            'dosen' => ['dosen', '/profil-dosen', '/profil-dosen/password'],
        ];
    }

    #[DataProvider('peran')]
    public function test_cookie_ingat_saya_dan_penggantian_password(string $role, string $akun, string $ubah): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => $role, 'registration_status' => User::STATUS_APPROVED]);
        $cookie = Auth::guard()->getRecallerName();
        $login = ['email' => $user->email, 'password' => 'password'];

        $response = $this->masuk($user, $login);
        $response->assertCookieMissing($cookie);
        $this->hapusSesiUji();
        $this->get($akun)->assertRedirect(route('login'));

        $response = $this->masuk($user, $login + ['remember' => '1']);
        $nilaiCookie = $response->getCookie($cookie)->getValue();
        $this->hapusSesiUji();
        $this->withCookie($cookie, $nilaiCookie)->get($akun)->assertOk()->assertSee('current_password');
        $this->assertTrue(Auth::viaRemember());

        $this->post('/logout')->assertCookieExpired($cookie);
        $this->hapusSesiUji();
        $this->get($akun)->assertRedirect(route('login'));

        $response = $this->masuk($user, $login + ['remember' => '1']);
        $nilaiCookie = $response->getCookie($cookie)->getValue();
        $baru = ['password' => 'SandiUjiBaru123', 'password_confirmation' => 'SandiUjiBaru123'];
        $this->put($ubah, $baru + ['current_password' => 'salah'])
            ->assertSessionHasErrors('current_password', null, $role === 'dosen' ? 'dosenPassword' : 'akunPassword');
        $this->put($ubah, $baru + ['current_password' => 'password'])->assertSessionHasNoErrors();

        $this->hapusSesiUji();
        $this->withCookie($cookie, $nilaiCookie)->get($akun)->assertRedirect(route('login'));
        $this->post('/login', $login)->assertSessionHasErrors('email');
        $this->masuk($user, array_replace($login, ['password' => $baru['password']]));
    }

    #[DataProvider('peran')]
    public function test_reset_password_memvalidasi_token(string $role, string $akun, string $ubah): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => $role, 'registration_status' => User::STATUS_APPROVED]);
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('success');
        $token = Notification::sent($user, ResetPasswordNotification::class)->sole()->token;
        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))->assertOk();
        $data = ['email' => $user->email, 'password' => 'ResetUji123', 'password_confirmation' => 'ResetUji123'];
        $this->post(route('password.update'), $data + ['token' => 'salah'])->assertSessionHasErrors('email');
        $this->travel((int) config('auth.passwords.users.expire') + 1)->minutes();
        $this->post(route('password.update'), $data + ['token' => $token])->assertSessionHasErrors('email');
        $token = Password::createToken($user);
        $this->post(route('password.update'), $data + ['token' => $token])->assertRedirect(route('login'));
        $this->assertTrue(Hash::check($data['password'], $user->fresh()->password));
        $this->post(route('password.update'), $data + ['token' => $token])->assertSessionHasErrors('email');
        $this->masuk($user, ['email' => $user->email, 'password' => $data['password']]);
        $this->get($akun)->assertOk();
    }

    private function masuk(User $user, array $data)
    {
        $response = $this->post('/login', $data);
        if ($user->role === 'dosen') {
            $response->assertRedirect(route('login.konfirmasi.menunggu'));
            $this->assertGuest();
            $token = Notification::sent($user, LoginConfirmationNotification::class)->last()->token;
            $response = $this->post(route('login.konfirmasi.setujui', $token));
        }

        $this->assertAuthenticatedAs($user);

        return $response;
    }

    private function hapusSesiUji(): void
    {
        session()->invalidate();
        Auth::forgetGuards();
    }
}
