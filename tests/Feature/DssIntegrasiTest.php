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
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DssIntegrasiTest extends TestCase
{
    use RefreshDatabase;

    private int $urut = 0;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        $this->seed(DssKriteriaSeeder::class);
    }

    private function buatUser(string $nama, string $role = 'dosen'): User
    {
        $this->urut++;

        return User::create([
            'fullname' => $nama,
            'email' => "integrasi{$this->urut}@kampus.ac.id",
            'nip' => $role === 'content_creator' ? null : (string) (850000 + $this->urut),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => User::STATUS_APPROVED,
        ]);
    }

    private function publikasi(User $user, string $judul): Publication
    {
        $this->urut++;

        return Publication::create([
            'user_id' => $user->id,
            'submission_type' => 'member',
            'judul' => $judul,
            'penulis' => $user->fullname,
            'tahun' => now()->year,
            'abstrak' => 'Abstrak uji.',
            'kategori' => 'Jurnal',
            'penerbit' => 'Penerbit Uji',
            'pdf_path' => 'uji/berkas.pdf',
        ]);
    }

    private function aktivitas(User $user, string $judul): AktivitasDosen
    {
        return AktivitasDosen::create([
            'user_id' => $user->id,
            'jenis' => 'abdimas',
            'judul' => $judul,
            'tahun' => now()->year,
            'skala' => 'nasional',
            'peran' => 'ketua',
            'status' => AktivitasDosen::STATUS_PUBLISH,
        ]);
    }

    private function hitung(): void
    {
        app(DssHitungService::class)->hitungSemua();
    }

    private function siapkan(): array
    {
        $alfa = $this->buatUser('Dosen Alfa Rahasia');
        $beta = $this->buatUser('Dosen Beta Rahasia');
        $kosong = $this->buatUser('Dosen Kosong');

        $this->publikasi($alfa, 'Pemantauan Lalu Lintas Kota');
        $this->publikasi($alfa, 'Model Banjir dan Lingkungan');
        $this->aktivitas($beta, 'Pelatihan Warga Desa');

        $this->hitung();

        return compact('alfa', 'beta', 'kosong');
    }

    public function test_dosen_melihat_kartu_analisis_miliknya_sendiri(): void
    {
        $d = $this->siapkan();

        $this->actingAs($d['alfa'])->get('/profil-dosen')
            ->assertOk()
            ->assertSee('Analisis Kompetensi')
            ->assertSee('Rincian per kriteria')
            ->assertSee('Gap analysis')
            ->assertSee('Smart Mobility')
            ->assertSee('Smart Environment')
            ->assertDontSee('Dosen Beta Rahasia')
            ->assertDontSee('Peringkat');
    }

    public function test_dosen_tanpa_data_melihat_skor_nol_tanpa_error(): void
    {
        $d = $this->siapkan();

        $this->actingAs($d['kosong'])->get('/profil-dosen')
            ->assertOk()
            ->assertSee('Analisis Kompetensi')
            ->assertSee('0,00')
            ->assertSee('Perlu Pengembangan');
    }

    public function test_dosen_yang_belum_dihitung_melihat_pesan_belum_tersedia(): void
    {
        $baru = $this->buatUser('Dosen Baru');

        $this->actingAs($baru)->get('/profil-dosen')
            ->assertOk()
            ->assertSee('Analisis kompetensi Anda belum tersedia');
    }

    public function test_kartu_analisis_tidak_muncul_untuk_content_creator(): void
    {
        $this->siapkan();
        $creator = $this->buatUser('Creator Uji', 'content_creator');

        $this->actingAs($creator)->get('/profil-dosen')
            ->assertOk()
            ->assertDontSee('Analisis Kompetensi');
    }

    public function test_dosen_tidak_bisa_membuka_halaman_dss_admin_atau_hasil_dosen_lain(): void
    {
        $d = $this->siapkan();

        foreach (['/admin/dss/hasil', '/admin/dss/hasil/' . $d['beta']->id, '/admin/dss/peta-kompetensi', '/admin/dss/hasil/ekspor'] as $url) {
            $this->actingAs($d['alfa'])->get($url)->assertForbidden();
        }
    }

    public function test_biografi_dosen_lain_hanya_menampilkan_badge_tanpa_skor(): void
    {
        $d = $this->siapkan();

        $this->actingAs($d['beta'])->get('/biografi-dosen/' . $d['alfa']->id)
            ->assertOk()
            ->assertSee('Peta Kompetensi Smart City')
            ->assertSee('Smart Mobility')
            ->assertDontSee('Analisis Kompetensi')
            ->assertDontSee('Skor akhir')
            ->assertDontSee('Gap analysis')
            ->assertDontSee('Perlu Pengembangan');
    }

    public function test_halaman_publik_hanya_menampilkan_badge_dimensi(): void
    {
        $d = $this->siapkan();
        $hasil = DssHasil::where('user_id', $d['alfa']->id)->firstOrFail();
        $skor = number_format($hasil->skor_akhir, 2, ',', '.');

        $this->get('/biografi-user/' . $d['alfa']->id)
            ->assertOk()
            ->assertSee('Peta Kompetensi Smart City')
            ->assertSee('Smart Mobility')
            ->assertSee('Smart Environment')
            ->assertDontSee('Analisis Kompetensi')
            ->assertDontSee('Skor akhir')
            ->assertDontSee('Gap analysis')
            ->assertDontSee('Rekomendasi peran')
            ->assertDontSee('Perlu Pengembangan')
            ->assertDontSee('Sangat Potensial')
            ->assertDontSee('Anggota Riset')
            ->assertDontSee($skor . ' / 100');
    }

    public function test_dosen_tanpa_dimensi_tidak_menampilkan_panel_peta(): void
    {
        $d = $this->siapkan();

        $this->get('/biografi-user/' . $d['kosong']->id)
            ->assertOk()
            ->assertDontSee('Peta Kompetensi Smart City');
    }

    public function test_pengunjung_tidak_bisa_mengakses_skor_lewat_url_apa_pun(): void
    {
        $d = $this->siapkan();
        $id = $d['alfa']->id;

        foreach (['/admin/dss/hasil', "/admin/dss/hasil/{$id}", '/admin/dss/peta-kompetensi', '/admin/dss/hasil/ekspor', '/admin/dss/bobot'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        foreach (['/dss/hasil', "/dss/hasil/{$id}", '/hasil-dss', '/profil-dosen/analisis'] as $url) {
            $this->get($url)->assertNotFound();
        }

        $this->get('/profil-dosen')->assertRedirect(route('login'));
    }

    public function test_kartu_dosen_kedaluwarsa_hanya_jika_data_miliknya_berubah(): void
    {
        $d = $this->siapkan();

        $this->actingAs($d['alfa'])->get('/profil-dosen')->assertDontSee('Hasil kedaluwarsa');

        $this->travel(5)->minutes();
        $this->aktivitas($d['beta'], 'Kegiatan Beta Baru');

        $this->actingAs($d['alfa'])->get('/profil-dosen')->assertDontSee('Hasil kedaluwarsa');

        $this->aktivitas($d['alfa'], 'Kegiatan Alfa Baru');

        $this->actingAs($d['alfa'])->get('/profil-dosen')->assertSee('Hasil kedaluwarsa');

        $this->hitung();

        $this->actingAs($d['alfa'])->get('/profil-dosen')->assertDontSee('Hasil kedaluwarsa');
    }

    public function test_perubahan_bobot_membuat_hasil_kedaluwarsa(): void
    {
        $d = $this->siapkan();

        $this->travel(5)->minutes();
        \App\Models\DssKriteria::where('kode', 'C1')->update(['bobot' => 0.35, 'updated_at' => now()]);
        \App\Models\DssKriteria::where('kode', 'C2')->update(['bobot' => 0.20, 'updated_at' => now()]);

        $this->actingAs($d['alfa'])->get('/profil-dosen')->assertSee('Hasil kedaluwarsa');
    }

    public function test_kegagalan_database_saat_hitung_ulang_memakai_pesan_khusus_dss(): void
    {
        $admin = $this->buatUser('Admin Uji', 'admin');
        $this->buatUser('Dosen Satu');

        Schema::drop('dss_hasil');

        $this->actingAs($admin)->from('/admin/dss/hasil')->post('/admin/dss/hasil/hitung')
            ->assertRedirect('/admin/dss/hasil')
            ->assertSessionHas('error', fn ($pesan) => str_contains($pesan, 'Perhitungan ulang DSS gagal') && ! str_contains($pesan, 'SQLSTATE'));
    }
}