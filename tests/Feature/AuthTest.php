<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_registrasi_bisa_diakses(): void
    {
        $response = $this->get(route('registrasi'));

        $response->assertStatus(200);
    }

    public function test_halaman_login_bisa_diakses(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_dosen_bisa_registrasi_dengan_data_valid(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Budi Dosen',
            'email'                 => 'budi.dosen@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'dosen',
            'nip'                   => '198501012015011099',
            'prodi'                 => 'Teknik Informatika',
            'fakultas'              => 'Fakultas Informatika',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email'               => 'budi.dosen@example.test',
            'role'                => 'dosen',
            'registration_status' => User::STATUS_PENDING,
        ]);

        $user = User::where('email', 'budi.dosen@example.test')->first();

        // Password harus ter-hash, bukan plain text.
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_content_creator_bisa_registrasi_tanpa_nip(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Citra Kreator',
            'email'                 => 'citra.creator@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'content_creator',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email'               => 'citra.creator@example.test',
            'role'                => 'content_creator',
            'registration_status' => User::STATUS_PENDING,
        ]);
    }

    public function test_registrasi_dengan_foto_tersimpan_di_disk_public(): void
    {
        // Sengaja pakai create(), BUKAN image(), karena image() butuh
        // ekstensi PHP GD untuk benar-benar men-generate file gambar.
        // Banyak setup PHP (termasuk XAMPP/Windows default) tidak
        // mengaktifkan GD, sehingga image() akan melempar
        // "GD extension is not installed." create() tidak butuh GD sama
        // sekali, tapi tetap lolos validasi 'image' di server karena
        // MIME type-nya di-set eksplisit ke 'image/jpeg'.
        $foto = UploadedFile::fake()->create('foto.jpg', 500, 'image/jpeg');

        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Dian Dosen',
            'email'                 => 'dian.dosen@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'dosen',
            'nip'                   => '198501012015011088',
            'foto'                  => $foto,
        ]);

        $response->assertRedirect('/login');

        $user = User::where('email', 'dian.dosen@example.test')->first();

        $this->assertNotNull($user->foto);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_registrasi_menolak_field_wajib_yang_kosong(): void
    {
        $response = $this->post(route('registrasi'), []);

        $response->assertSessionHasErrors(['fullname', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_registrasi_dosen_wajib_mengisi_nip(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Eko Dosen',
            'email'                 => 'eko.dosen@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'dosen',
            // 'nip' sengaja tidak diisi
        ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_registrasi_menolak_nip_yang_bukan_angka(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Fajar Dosen',
            'email'                 => 'fajar.dosen@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'dosen',
            'nip'                   => 'ABC123XYZ',
        ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_registrasi_menolak_password_kurang_dari_6_karakter(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Gita Kreator',
            'email'                 => 'gita.creator@example.test',
            'password'              => '123',
            'password_confirmation' => '123',
            'role'                  => 'content_creator',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registrasi_menolak_konfirmasi_password_yang_tidak_cocok(): void
    {
        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Hana Kreator',
            'email'                 => 'hana.creator@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'beda-sekali',
            'role'                  => 'content_creator',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registrasi_menolak_email_yang_sudah_terdaftar(): void
    {
        User::factory()->approved()->create(['email' => 'sudah.ada@example.test']);

        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Ivan Kreator',
            'email'                 => 'sudah.ada@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'content_creator',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registrasi_ulang_diperbolehkan_jika_akun_sebelumnya_ditolak(): void
    {
        $rejected = User::factory()->contentCreator()->rejected()->create([
            'email' => 'ditolak@example.test',
        ]);

        $response = $this->post(route('registrasi'), [
            'fullname'              => 'Joko Kreator Baru',
            'email'                 => 'ditolak@example.test',
            'password'              => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role'                  => 'content_creator',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasNoErrors();

        $rejected->refresh();
        $this->assertSame('Joko Kreator Baru', $rejected->fullname);
        $this->assertSame(User::STATUS_PENDING, $rejected->registration_status);
    }

    public function test_admin_yang_disetujui_bisa_login_dan_diarahkan_ke_dashboard_admin(): void
    {
        User::factory()->admin()->approved()->create([
            'email'    => 'admin@example.test',
            'password' => 'rahasia123',
        ]);

        $response = $this->post(route('login.masuk'), [
            'email'    => 'admin@example.test',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect('/beranda-admin');
        $this->assertAuthenticated();
    }

    public function test_dosen_yang_masih_pending_diarahkan_ke_halaman_status(): void
    {
        User::factory()->dosen()->pending()->create([
            'email'    => 'dosen.pending@example.test',
            'password' => 'rahasia123',
        ]);

        $response = $this->post(route('login.masuk'), [
            'email'    => 'dosen.pending@example.test',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('dosen.status'));
        $this->assertAuthenticated();
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->approved()->create([
            'email'    => 'salah.password@example.test',
            'password' => 'rahasia123',
        ]);

        $response = $this->post(route('login.masuk'), [
            'email'    => 'salah.password@example.test',
            'password' => 'password-yang-salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_gagal_untuk_email_yang_tidak_terdaftar(): void
    {
        $response = $this->post(route('login.masuk'), [
            'email'    => 'tidak.ada@example.test',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_menolak_field_kosong(): void
    {
        $response = $this->post(route('login.masuk'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_yang_login_bisa_logout(): void
    {
        $user = User::factory()->approved()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}