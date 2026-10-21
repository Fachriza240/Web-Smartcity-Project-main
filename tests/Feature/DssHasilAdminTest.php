<?php

namespace Tests\Feature;

use App\Models\AktivitasDosen;
use App\Models\DssHasil;
use App\Models\Publication;
use App\Models\User;
use App\Services\DssHitungService;
use Database\Seeders\DssKriteriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DssHasilAdminTest extends TestCase
{
    use RefreshDatabase;

    private int $urut = 0;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        $this->seed(DssKriteriaSeeder::class);
    }

    private function buatUser(string $nama, string $role = 'dosen', ?string $prodi = null): User
    {
        $this->urut++;

        return User::create([
            'fullname' => $nama,
            'email' => "hasil{$this->urut}@kampus.ac.id",
            'nip' => $role === 'content_creator' ? null : (string) (800000 + $this->urut),
            'prodi' => $prodi,
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => User::STATUS_APPROVED,
        ]);
    }

    private function publikasi(User $user, string $judul = 'Pemantauan Lalu Lintas Kota', string $kategori = 'Jurnal'): Publication
    {
        $this->urut++;

        return Publication::create([
            'user_id' => $user->id,
            'submission_type' => 'member',
            'judul' => $judul,
            'penulis' => $user->fullname,
            'tahun' => now()->year,
            'abstrak' => 'Abstrak uji.',
            'kategori' => $kategori,
            'penerbit' => 'Penerbit Uji',
            'pdf_path' => 'uji/berkas.pdf',
        ]);
    }

    private function hitung(): void
    {
        app(DssHitungService::class)->hitungSemua();
    }

    /** @return array{admin: User, alfa: User, beta: User, kosong: User} */
    private function siapkanData(): array
    {
        $admin = $this->buatUser('Admin Uji', 'admin');
        $alfa = $this->buatUser('Dosen Alfa', 'dosen', 'Sistem Informasi');
        $beta = $this->buatUser('Dosen Beta', 'dosen', 'Teknik Informatika');
        $kosong = $this->buatUser('Dosen Kosong', 'dosen', 'Sistem Informasi');

        $this->publikasi($alfa);
        $this->publikasi($alfa, 'Model Banjir dan Lingkungan');
        $this->urut++;
        AktivitasDosen::create([
            'user_id' => $beta->id,
            'jenis' => 'abdimas',
            'judul' => 'Pelatihan Warga',
            'tahun' => now()->year,
            'skala' => 'nasional',
            'peran' => 'ketua',
            'status' => AktivitasDosen::STATUS_PUBLISH,
        ]);

        $this->hitung();

        return compact('admin', 'alfa', 'beta', 'kosong');
    }

    public function test_admin_melihat_peringkat_detail_dan_peta(): void
    {
        $d = $this->siapkanData();

        $this->actingAs($d['admin'])->get('/admin/dss/hasil')
            ->assertOk()
            ->assertSee('Dosen Alfa')
            ->assertSee('Dosen Beta')
            ->assertSee('Ekspor CSV')
            ->assertSee('Hitung Ulang');

        $this->actingAs($d['admin'])->get('/admin/dss/hasil/' . $d['alfa']->id)
            ->assertOk()
            ->assertSee('Dosen Alfa')
            ->assertSee('Gap Analysis')
            ->assertSee('dssRadar', false);

        $this->actingAs($d['admin'])->get('/admin/dss/peta-kompetensi')
            ->assertOk()
            ->assertSee('Smart Mobility')
            ->assertSee('Smart Environment')
            ->assertSee('dssPeta', false);
    }

    public function test_dosen_tanpa_data_tampil_skor_nol_tanpa_error(): void
    {
        $d = $this->siapkanData();

        $hasil = DssHasil::where('user_id', $d['kosong']->id)->firstOrFail();
        $this->assertEqualsWithDelta(0.0, $hasil->skor_akhir, 0.001);

        $this->actingAs($d['admin'])->get('/admin/dss/hasil')
            ->assertOk()
            ->assertSee('Dosen Kosong')
            ->assertSee('0,00')
            ->assertSee('Perlu Pengembangan');

        $this->actingAs($d['admin'])->get('/admin/dss/hasil/' . $d['kosong']->id)
            ->assertOk()
            ->assertSee('Perlu Pengembangan')
            ->assertSee('Belum dapat direkomendasikan');
    }

    public function test_semua_dosen_nol_tidak_menyebabkan_error(): void
    {
        $admin = $this->buatUser('Admin Uji', 'admin');
        $satu = $this->buatUser('Dosen Satu');
        $this->buatUser('Dosen Dua');
        $this->hitung();

        $this->actingAs($admin)->get('/admin/dss/hasil')->assertOk();
        $this->actingAs($admin)->get('/admin/dss/hasil/' . $satu->id)->assertOk();
        $this->actingAs($admin)->get('/admin/dss/peta-kompetensi')->assertOk();
        $this->actingAs($admin)->get('/admin/dss/hasil/ekspor')->assertOk();
    }

    public function test_halaman_kosong_sebelum_ada_perhitungan(): void
    {
        $admin = $this->buatUser('Admin Uji', 'admin');

        $this->actingAs($admin)->get('/admin/dss/hasil')
            ->assertOk()
            ->assertSee('Belum ada hasil perhitungan');
        $this->actingAs($admin)->get('/admin/dss/peta-kompetensi')->assertOk();
    }

    public function test_filter_prodi_dan_kategori(): void
    {
        $d = $this->siapkanData();

        $this->actingAs($d['admin'])->get('/admin/dss/hasil?prodi=Teknik+Informatika')
            ->assertOk()
            ->assertSee('Dosen Beta')
            ->assertViewHas('hasil', fn ($hasil) => $hasil->count() === 1 && $hasil->first()->user_id === $d['beta']->id);

        $this->actingAs($d['admin'])->get('/admin/dss/hasil?prodi=Sistem+Informasi&kategori=Perlu+Pengembangan')
            ->assertOk()
            ->assertViewHas('hasil', fn ($hasil) => $hasil->count() === 2);

        $this->actingAs($d['admin'])->get('/admin/dss/hasil?kategori=Sangat+Potensial')
            ->assertOk()
            ->assertViewHas('hasil', fn ($hasil) => $hasil->count() === 0);

        // nilai filter yang tidak dikenal diabaikan, tidak menyebabkan error
        $this->actingAs($d['admin'])->get('/admin/dss/hasil?prodi=%27+OR+1%3D1&kategori=xyz')
            ->assertOk()
            ->assertSee('Dosen Alfa')
            ->assertSee('Dosen Beta');
    }

    public function test_hanya_admin_yang_boleh_membuka_halaman_dss(): void
    {
        $d = $this->siapkanData();
        $creator = $this->buatUser('Creator Uji', 'content_creator');

        $get = ['/admin/dss/hasil', '/admin/dss/hasil/' . $d['alfa']->id, '/admin/dss/peta-kompetensi', '/admin/dss/hasil/ekspor'];

        foreach ($get as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
        $this->post('/admin/dss/hasil/hitung')->assertRedirect(route('login'));

        foreach ($get as $url) {
            $this->actingAs($d['alfa'])->get($url)->assertForbidden();
            $this->actingAs($creator)->get($url)->assertForbidden();
        }

        $this->actingAs($d['alfa'])->post('/admin/dss/hasil/hitung')->assertForbidden();
        $this->actingAs($creator)->post('/creator/dss/hasil/hitung')->assertForbidden();
        $this->actingAs($creator)->get('/creator/dss/hasil')->assertForbidden();
    }

    public function test_detail_hanya_untuk_dosen_yang_punya_hasil(): void
    {
        $d = $this->siapkanData();

        $this->actingAs($d['admin'])->get('/admin/dss/hasil/' . $d['admin']->id)->assertNotFound();
        $this->actingAs($d['admin'])->get('/admin/dss/hasil/999999')->assertNotFound();

        $baru = $this->buatUser('Dosen Baru');
        $this->actingAs($d['admin'])->get('/admin/dss/hasil/' . $baru->id)
            ->assertRedirect(route('admin.dss.hasil.index'));
    }

    public function test_tombol_hitung_ulang_memperbarui_hasil(): void
    {
        $d = $this->siapkanData();
        $awal = DssHasil::where('user_id', $d['alfa']->id)->firstOrFail();

        $this->travel(5)->minutes();
        $this->publikasi($d['alfa'], 'Publikasi Tambahan', 'Buku');

        $this->actingAs($d['admin'])->post('/admin/dss/hasil/hitung')
            ->assertRedirect(route('admin.dss.hasil.index'))
            ->assertSessionHas('success');

        $baru = DssHasil::where('user_id', $d['alfa']->id)->firstOrFail();
        $this->assertGreaterThan($awal->poin_mentah['C1'], $baru->poin_mentah['C1']);
        $this->assertTrue($baru->dihitung_pada->gt($awal->dihitung_pada));
    }

    public function test_hitung_ulang_dua_kali_berturut_turut_menghasilkan_skor_sama(): void
    {
        $d = $this->siapkanData();

        $this->actingAs($d['admin'])->post('/admin/dss/hasil/hitung');
        $pertama = DssHasil::orderBy('user_id')->pluck('skor_akhir', 'user_id')->all();

        $this->actingAs($d['admin'])->post('/admin/dss/hasil/hitung');
        $kedua = DssHasil::orderBy('user_id')->pluck('skor_akhir', 'user_id')->all();

        $this->assertEquals($pertama, $kedua);
    }

    public function test_hasil_kedaluwarsa_muncul_setelah_data_berubah(): void
    {
        $d = $this->siapkanData();

        $this->actingAs($d['admin'])->get('/admin/dss/hasil')
            ->assertOk()
            ->assertDontSee('Hasil kedaluwarsa');

        $this->travel(5)->minutes();
        $this->publikasi($d['beta'], 'Publikasi Susulan');

        $this->actingAs($d['admin'])->get('/admin/dss/hasil')
            ->assertOk()
            ->assertSee('Hasil kedaluwarsa');

        $this->actingAs($d['admin'])->get('/admin/dss/hasil/' . $d['alfa']->id)
            ->assertSee('Hasil kedaluwarsa');

        $this->actingAs($d['admin'])->post('/admin/dss/hasil/hitung');
        $this->actingAs($d['admin'])->get('/admin/dss/hasil')
            ->assertDontSee('Hasil kedaluwarsa');
    }

    public function test_hasil_kedaluwarsa_saat_ada_dosen_baru_disetujui(): void
    {
        $d = $this->siapkanData();

        $this->buatUser('Dosen Pendatang');

        $this->actingAs($d['admin'])->get('/admin/dss/hasil')->assertSee('Hasil kedaluwarsa');
    }

    public function test_ekspor_csv_berisi_data_dan_aman_dari_formula(): void
    {
        $d = $this->siapkanData();
        $d['alfa']->forceFill(['fullname' => '=HYPERLINK("x")'])->save();
        $this->hitung();

        $respon = $this->actingAs($d['admin'])->get('/admin/dss/hasil/ekspor?prodi=Sistem+Informasi');
        $respon->assertOk();
        $this->assertStringContainsString('text/csv', $respon->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $respon->headers->get('Content-Disposition'));

        $isi = $respon->streamedContent();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $isi);
        $this->assertStringContainsString('Peringkat;"Nama Dosen";"Program Studi"', $isi);
        $this->assertStringContainsString("'=HYPERLINK", $isi);
        $this->assertStringContainsString('Dosen Kosong', $isi);
        $this->assertStringNotContainsString('Dosen Beta', $isi);
    }
}