<?php

namespace Tests\Feature;

use App\Models\DssAhp;
use App\Models\DssKriteria;
use App\Models\User;
use Database\Seeders\DssKriteriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DssBobotTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DssKriteriaSeeder::class);
    }

    private function makeUser(string $role): User
    {
        $this->seq++;

        return User::create([
            'fullname' => 'Pengguna Uji',
            'email' => "dss{$this->seq}@kampus.ac.id",
            'nip' => $role === 'content_creator' ? null : (string) (800000 + $this->seq),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => User::STATUS_APPROVED,
        ]);
    }

    private function perbandinganValid(): array
    {
        return [
            'C1' => ['C2' => '2', 'C3' => '2', 'C4' => '3', 'C5' => '4'],
            'C2' => ['C3' => '2', 'C4' => '2', 'C5' => '3'],
            'C3' => ['C4' => '2', 'C5' => '2'],
            'C4' => ['C5' => '2'],
        ];
    }

    private function perbandinganAcak(): array
    {
        return [
            'C1' => ['C2' => '9', 'C3' => '1/9', 'C4' => '3', 'C5' => '5'],
            'C2' => ['C3' => '9', 'C4' => '1/3', 'C5' => '1'],
            'C3' => ['C4' => '7', 'C5' => '1/5'],
            'C4' => ['C5' => '9'],
        ];
    }

    public function test_admin_dapat_membuka_halaman_pembobotan(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->get('/admin/dss/bobot')
            ->assertOk()
            ->assertSee('Pembobotan AHP')
            ->assertSee('Penelitian')
            ->assertSee('Kesesuaian Smart City');
    }

    public function test_bobot_awal_berjumlah_satu(): void
    {
        $this->assertSame(5, DssKriteria::count());
        $this->assertEqualsWithDelta(1.0, DssKriteria::totalBobot(), 1e-9);
        $this->assertEqualsWithDelta(0.30, DssKriteria::bobotSaatIni()['C1'], 1e-9);
    }

    public function test_hitung_menampilkan_hasil_tanpa_menyimpan(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->post('/admin/dss/bobot/hitung', ['perbandingan' => $this->perbandinganValid()])
            ->assertOk()
            ->assertSee('Hasil Perhitungan')
            ->assertSee('Konsisten')
            ->assertSee('0,3736');

        $this->assertSame(0, DssAhp::count());
        $this->assertEqualsWithDelta(0.30, DssKriteria::bobotSaatIni()['C1'], 1e-9);
    }

    public function test_simpan_matriks_konsisten_memperbarui_bobot_kriteria(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->post('/admin/dss/bobot/simpan', [
                'perbandingan' => $this->perbandinganValid(),
                'catatan' => 'Penilaian uji',
            ])
            ->assertRedirect(url('/admin/dss/bobot'));

        $this->assertSame(1, DssAhp::count());

        $ahp = DssAhp::terpakai();
        $this->assertNotNull($ahp);
        $this->assertTrue($ahp->aktif);
        $this->assertTrue($ahp->konsisten);
        $this->assertSame('Penilaian uji', $ahp->catatan);
        $this->assertSame($admin->id, (int) $ahp->dibuat_oleh);
        $this->assertEqualsWithDelta(0.018960, $ahp->cr, 0.0001);

        $bobot = DssKriteria::bobotSaatIni();
        $this->assertEqualsWithDelta(0.373649, $bobot['C1'], 0.00001);
        $this->assertEqualsWithDelta(0.248589, $bobot['C2'], 0.00001);
        $this->assertEqualsWithDelta(0.175512, $bobot['C3'], 0.00001);
        $this->assertEqualsWithDelta(0.122413, $bobot['C4'], 0.00001);
        $this->assertEqualsWithDelta(0.079837, $bobot['C5'], 0.00001);
        $this->assertEqualsWithDelta(1.0, DssKriteria::totalBobot(), 1e-9);
    }

    public function test_simpan_matriks_tidak_konsisten_ditolak(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganAcak()])
            ->assertRedirect(url('/admin/dss/bobot'))
            ->assertSessionHasErrors('perbandingan');

        $this->assertSame(0, DssAhp::count());
        $this->assertEqualsWithDelta(0.30, DssKriteria::bobotSaatIni()['C1'], 1e-9);
    }

    public function test_hitung_matriks_acak_menampilkan_tidak_konsisten(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->post('/admin/dss/bobot/hitung', ['perbandingan' => $this->perbandinganAcak()])
            ->assertOk()
            ->assertSee('Tidak konsisten');
    }

    public function test_nilai_di_luar_skala_saaty_ditolak(): void
    {
        $data = $this->perbandinganValid();
        $data['C1']['C2'] = '10';

        $this->actingAs($this->makeUser('admin'))
            ->post('/admin/dss/bobot/simpan', ['perbandingan' => $data])
            ->assertSessionHasErrors('perbandingan.C1.C2');

        $this->assertSame(0, DssAhp::count());
    }

    public function test_perbandingan_yang_kosong_ditolak(): void
    {
        $data = $this->perbandinganValid();
        unset($data['C4']['C5']);

        $this->actingAs($this->makeUser('admin'))
            ->post('/admin/dss/bobot/hitung', ['perbandingan' => $data])
            ->assertSessionHasErrors('perbandingan.C4.C5');
    }

    public function test_catatan_berisi_pola_berbahaya_ditolak(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->post('/admin/dss/bobot/simpan', [
                'perbandingan' => $this->perbandinganValid(),
                'catatan' => '<script>alert(1)</script>',
            ])
            ->assertSessionHasErrors('catatan');

        $this->assertSame(0, DssAhp::count());
    }

    public function test_simpan_dua_kali_hanya_satu_yang_aktif(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()]);
        $this->actingAs($admin)->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()]);

        $this->assertSame(2, DssAhp::count());
        $this->assertSame(1, DssAhp::where('aktif', true)->count());
    }

    public function test_reset_mengembalikan_bobot_awal(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()]);
        $this->assertEqualsWithDelta(0.373649, DssKriteria::bobotSaatIni()['C1'], 0.00001);

        $this->actingAs($admin)
            ->post('/admin/dss/bobot/reset')
            ->assertRedirect(url('/admin/dss/bobot'));

        $this->assertEqualsWithDelta(0.30, DssKriteria::bobotSaatIni()['C1'], 1e-9);
        $this->assertEqualsWithDelta(1.0, DssKriteria::totalBobot(), 1e-9);
        $this->assertSame(0, DssAhp::where('aktif', true)->count());
        $this->assertNull(DssAhp::terpakai());
    }

    public function test_dosen_ditolak_mengakses_pembobotan(): void
    {
        $dosen = $this->makeUser('dosen');

        $this->actingAs($dosen)->get('/admin/dss/bobot')->assertForbidden();
        $this->actingAs($dosen)
            ->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()])
            ->assertForbidden();

        $this->assertSame(0, DssAhp::count());
    }

    public function test_content_creator_ditolak_mengakses_pembobotan(): void
    {
        $creator = $this->makeUser('content_creator');

        $this->actingAs($creator)->get('/creator/dss/bobot')->assertForbidden();
        $this->actingAs($creator)
            ->post('/creator/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()])
            ->assertForbidden();

        $this->assertSame(0, DssAhp::count());
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin/dss/bobot')->assertRedirect(route('login'));
        $this->post('/admin/dss/bobot/simpan', ['perbandingan' => $this->perbandinganValid()])
            ->assertRedirect(route('login'));
    }

    public function test_menu_pembobotan_hanya_tampil_untuk_admin(): void
    {
        $this->actingAs($this->makeUser('admin'))
            ->get('/beranda-admin')
            ->assertOk()
            ->assertSee(url('/admin/dss/bobot'), false);

        $this->actingAs($this->makeUser('content_creator'))
            ->get('/beranda-creator')
            ->assertOk()
            ->assertDontSee('Pembobotan AHP')
            ->assertDontSee(url('/admin/'), false);
    }
}