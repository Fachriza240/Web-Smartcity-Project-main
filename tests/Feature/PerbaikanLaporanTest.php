<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerbaikanLaporanTest extends TestCase
{
    use RefreshDatabase;

    private function registrasi(array $override = [])
    {
        return $this->post('/registrasi', array_merge([
            'role' => 'dosen',
            'fullname' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nip' => '1987654321',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ], $override));
    }

    private function user(string $role, string $status = User::STATUS_APPROVED): User
    {
        return User::create([
            'fullname' => 'Pengguna Uji',
            'email' => $role.$status.'@example.com',
            'nip' => (string) random_int(100000, 999999),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => $status,
        ]);
    }

    public function test_registrasi_valid_berhasil_dan_menunggu_validasi(): void
    {
        $this->registrasi()->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'registration_status' => User::STATUS_PENDING,
        ]);
    }

    public function test_registrasi_menolak_input_tidak_valid(): void
    {
        $kasus = [
            'nama dua karakter' => ['fullname' => 'Ab'],
            'nama berisi emoji' => ['fullname' => "Budi \u{1F600}"],
            'nama berisi script' => ['fullname' => '<script>alert(1)</script>'],
            'nama berisi sql injection' => ['fullname' => "' OR '1'='1"],
            'password 65 karakter' => ['password' => str_repeat('a', 65), 'password_confirmation' => str_repeat('a', 65)],
            'password 5 karakter' => ['password' => 'abcde', 'password_confirmation' => 'abcde'],
        ];

        foreach ($kasus as $nama => $data) {
            $field = array_key_first($data);
            $this->registrasi($data)->assertSessionHasErrors($field === 'password_confirmation' ? 'password' : $field);
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_profil_dosen_menolak_emoji_dan_sql_injection(): void
    {
        $dosen = $this->user('dosen');

        $this->actingAs($dosen)
            ->put('/profil-dosen', [
                'fullname' => "Dosen \u{1F600}",
                'email' => $dosen->email,
                'prodi' => "x' OR '1'='1",
                'bio' => '<b>halo</b>',
            ])
            ->assertSessionHasErrors(['fullname', 'prodi', 'bio']);
    }

    public function test_logout_hanya_lewat_post_dan_kembali_ke_beranda(): void
    {
        $dosen = $this->user('dosen');

        $this->actingAs($dosen)->get('/logout')->assertStatus(405);
        $this->actingAs($dosen)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_dosen_pending_diarahkan_ke_halaman_status(): void
    {
        $dosen = $this->user('dosen', User::STATUS_PENDING);

        $this->actingAs($dosen)->get('/beranda-dosen')->assertRedirect(route('dosen.status'));
    }

    public function test_content_creator_tidak_bisa_membuka_validasi_registrasi(): void
    {
        $creator = $this->user('content_creator');

        $this->actingAs($creator)->get('/admin/validasi-registrasi')->assertForbidden();
    }

    public function test_halaman_publik_baru_dapat_dibuka(): void
    {
        $this->get('/kontak')->assertOk()->assertSee(config('smartcity.email'));
        $this->get('/contact')->assertRedirect('/kontak');
        $this->get('/team-user')->assertOk()->assertSee('Lorem Ipsum');
        $this->get('/biografi-user')->assertOk();
        $this->get('/cari?q=kota')->assertOk();
    }
}
