<?php

namespace Tests\Feature;

use App\Models\AktivitasDosen;
use App\Models\DssAhp;
use App\Models\DssHasil;
use App\Models\DssKriteria;
use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use App\Services\DssHitungService;
use App\Services\RekomendasiService;
use Database\Seeders\DssKriteriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DssHitungTest extends TestCase
{
    use RefreshDatabase;

    private int $urut = 0;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        $this->seed(DssKriteriaSeeder::class);
    }

    private function buatUser(string $nama, string $role = 'dosen', string $status = User::STATUS_APPROVED): User
    {
        $this->urut++;

        return User::create([
            'fullname' => $nama,
            'email' => "hitung{$this->urut}@kampus.ac.id",
            'nip' => $role === 'content_creator' ? null : (string) (900000 + $this->urut),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => $status,
        ]);
    }

    private function publikasi(User $user, string $kategori = 'Jurnal', int $selisihTahun = 0, array $lain = []): Publication
    {
        $this->urut++;

        return Publication::create(array_merge([
            'user_id' => $user->id,
            'submission_type' => 'member',
            'judul' => 'Judul Uji ' . $this->urut,
            'penulis' => $user->fullname,
            'tahun' => now()->year - $selisihTahun,
            'abstrak' => 'Abstrak uji.',
            'kategori' => $kategori,
            'penerbit' => 'Penerbit Uji',
            'pdf_path' => 'uji/berkas.pdf',
        ], $lain));
    }

    private function hki(User $user, string $jenis = 'Hak Cipta', int $selisihTahun = 0): Hki
    {
        $this->urut++;

        return Hki::create([
            'nomor_sertifikat' => 'UJI-' . $this->urut,
            'tgl_terbit' => now()->subYears($selisihTahun)->toDateString(),
            'judul_sertifikat' => 'Judul HKI Uji ' . $this->urut,
            'jenis_sertifikat' => $jenis,
            'pencipta' => $user->fullname,
            'user_id' => $user->id,
            'submission_type' => 'member',
        ]);
    }

    private function aktivitas(User $user, string $jenis, array $lain = []): AktivitasDosen
    {
        $this->urut++;

        return AktivitasDosen::create(array_merge([
            'user_id' => $user->id,
            'jenis' => $jenis,
            'judul' => 'Kegiatan Uji ' . $this->urut,
            'tahun' => now()->year,
            'peran' => 'ketua',
            'status' => AktivitasDosen::STATUS_PUBLISH,
        ], $lain));
    }

    private function hitung(): array
    {
        return app(DssHitungService::class)->hitungSemua();
    }

    private function hasilDari(User $user): DssHasil
    {
        return DssHasil::where('user_id', $user->id)->firstOrFail();
    }

    public function test_hitung_semua_menyimpan_skor_peringkat_dan_rekomendasi(): void
    {
        $alfa = $this->buatUser('Dosen Uji Alfa');
        $beta = $this->buatUser('Dosen Uji Beta');
        $gamma = $this->buatUser('Dosen Uji Gamma');

        $this->publikasi($alfa, 'Jurnal');
        $this->publikasi($alfa, 'Jurnal');
        $this->aktivitas($alfa, 'penelitian', ['skala' => 'nasional']);
        $this->aktivitas($beta, 'abdimas', ['skala' => 'nasional']);

        $ringkasan = $this->hitung();

        $this->assertSame(3, $ringkasan['jumlah']);
        $this->assertSame(3, DssHasil::count());

        // Alfa: C1 = 10 + 10 + 8 = 28 (tertinggi), skor = 0,30 x 100
        $a = $this->hasilDari($alfa);
        $this->assertEqualsWithDelta(30.0, $a->skor_akhir, 0.001);
        $this->assertSame(1, $a->peringkat);
        $this->assertSame('Perlu Pengembangan', $a->kategori);
        $this->assertSame('Penelitian', $a->kekuatan_utama);
        $this->assertSame('Kuat Riset', $a->cluster);
        $this->assertSame('Anggota Riset', $a->rekomendasi_peran);
        $this->assertEqualsWithDelta(28.0, $a->poin_mentah['C1'], 0.001);
        $this->assertEqualsWithDelta(100.0, $a->skor_normal['C1'], 0.001);
        $this->assertCount(4, $a->gap);

        // Beta: C2 = 6 (tertinggi), skor = 0,25 x 100
        $b = $this->hasilDari($beta);
        $this->assertEqualsWithDelta(25.0, $b->skor_akhir, 0.001);
        $this->assertSame(2, $b->peringkat);
        $this->assertSame('Abdimas', $b->kekuatan_utama);

        // Gamma: tanpa data
        $g = $this->hasilDari($gamma);
        $this->assertEqualsWithDelta(0.0, $g->skor_akhir, 0.001);
        $this->assertSame(3, $g->peringkat);
        $this->assertNull($g->kekuatan_utama);
        $this->assertSame(RekomendasiService::PERAN_BELUM, $g->rekomendasi_peran);
        $this->assertCount(5, $g->gap);
        $this->assertNotNull($a->dihitung_pada);
    }

    public function test_data_draft_dan_di_luar_jendela_tahun_tidak_dihitung(): void
    {
        $dosen = $this->buatUser('Dosen Uji Delta');

        // Publikasi Draft (non member) atas nama dosen
        $this->publikasi($dosen, 'Jurnal', 0, [
            'user_id' => null,
            'submission_type' => 'non_member',
            'recommended_by' => 'Admin Uji',
            'status' => 'Draft',
        ]);
        // Publikasi dan HKI lama (di luar jendela 5 tahun)
        $this->publikasi($dosen, 'Jurnal', 8);
        $this->hki($dosen, 'Paten', 8);
        // Aktivitas Draft dan aktivitas lama
        $this->aktivitas($dosen, 'penelitian', ['skala' => 'internasional', 'status' => AktivitasDosen::STATUS_DRAFT]);
        $this->aktivitas($dosen, 'penelitian', ['skala' => 'internasional', 'tahun' => now()->year - 8]);
        // Satu aktivitas sah sebagai pembanding
        $this->aktivitas($dosen, 'abdimas', ['skala' => 'institusi']);

        $this->hitung();

        $hasil = $this->hasilDari($dosen);
        $this->assertEqualsWithDelta(0.0, $hasil->poin_mentah['C1'], 0.001);
        $this->assertEqualsWithDelta(3.0, $hasil->poin_mentah['C2'], 0.001);
        $this->assertEqualsWithDelta(0.0, $hasil->poin_mentah['C3'], 0.001);
    }

    public function test_hanya_dosen_disetujui_dan_aktif_yang_dinilai(): void
    {
        $this->buatUser('Dosen Uji Aktif');
        $this->buatUser('Dosen Uji Menunggu', 'dosen', User::STATUS_PENDING);
        $this->buatUser('Dosen Uji Ditolak', 'dosen', User::STATUS_REJECTED);
        $nonaktif = $this->buatUser('Dosen Uji Nonaktif');
        $nonaktif->forceFill(['is_active' => false])->save();
        $this->buatUser('Pembuat Konten Uji', 'content_creator');
        $this->buatUser('Admin Uji', 'admin');

        $ringkasan = $this->hitung();

        $this->assertSame(1, $ringkasan['jumlah']);
        $this->assertSame(1, DssHasil::count());
    }

    public function test_hitung_ulang_tidak_menggandakan_dan_membersihkan_dosen_yang_tidak_lagi_dinilai(): void
    {
        $satu = $this->buatUser('Dosen Uji Satu');
        $dua = $this->buatUser('Dosen Uji Dua');
        $this->publikasi($satu, 'Jurnal');

        $this->hitung();
        $skorPertama = $this->hasilDari($satu)->skor_akhir;

        $this->hitung();
        $this->assertSame(2, DssHasil::count());
        $this->assertEqualsWithDelta($skorPertama, $this->hasilDari($satu)->skor_akhir, 1e-9);

        $dua->forceFill(['is_active' => false])->save();
        $this->hitung();

        $this->assertSame(1, DssHasil::count());
        $this->assertSame(0, DssHasil::where('user_id', $dua->id)->count());
    }

    public function test_bobot_diambil_dari_database(): void
    {
        $alfa = $this->buatUser('Dosen Uji Alfa');
        $beta = $this->buatUser('Dosen Uji Beta');
        $this->publikasi($alfa, 'Jurnal');
        $this->aktivitas($beta, 'abdimas', ['skala' => 'nasional']);

        foreach (['C1' => 0.5, 'C2' => 0.5, 'C3' => 0.0, 'C4' => 0.0, 'C5' => 0.0] as $kode => $bobot) {
            DssKriteria::where('kode', $kode)->update(['bobot' => $bobot]);
        }

        $this->hitung();

        $this->assertEqualsWithDelta(50.0, $this->hasilDari($alfa)->skor_akhir, 0.001);
        $this->assertEqualsWithDelta(50.0, $this->hasilDari($beta)->skor_akhir, 0.001);
        $this->assertSame(1, $this->hasilDari($alfa)->peringkat);
        $this->assertSame(1, $this->hasilDari($beta)->peringkat);
    }

    public function test_publikasi_bersama_dihitung_untuk_semua_penulis(): void
    {
        $delta = $this->buatUser('Dosen Uji Delta');
        $epsilon = $this->buatUser('Dosen Uji Epsilon');

        $this->publikasi($delta, 'Jurnal', 0, [
            'user_id' => null,
            'submission_type' => 'non_member',
            'recommended_by' => 'Admin Uji',
            'penulis' => 'Dosen Uji Delta, Dosen Uji Epsilon',
            'status' => 'Publish',
        ]);

        $this->hitung();

        $this->assertEqualsWithDelta(10.0, $this->hasilDari($delta)->poin_mentah['C1'], 0.001);
        $this->assertEqualsWithDelta(10.0, $this->hasilDari($epsilon)->poin_mentah['C1'], 0.001);
        $this->assertSame(1, $this->hasilDari($delta)->peringkat);
        $this->assertSame(1, $this->hasilDari($epsilon)->peringkat);
    }

    public function test_kompetensi_smart_city_tersimpan_beserta_rincian(): void
    {
        $dosen = $this->buatUser('Dosen Uji Zeta');
        $this->publikasi($dosen, 'Jurnal', 0, ['judul' => 'Pemantauan Lalu Lintas Kota']);

        $this->hitung();

        $hasil = $this->hasilDari($dosen);
        $this->assertSame('smart_mobility', $hasil->kompetensi['dominan']);
        $this->assertSame('Smart Mobility', $hasil->kompetensi['dominan_label']);
        $this->assertSame(1, $hasil->kompetensi['dokumen_cocok']);
        $this->assertTrue(isset($hasil->kompetensi['rincian']['C1']));

        $this->assertEqualsWithDelta(5.0, $hasil->poin_mentah['C4'], 0.001);
        $this->assertSame('Anggota Riset (Smart Mobility)', $hasil->rekomendasi_peran);
    }

    public function test_hasil_mencatat_ahp_yang_sedang_aktif(): void
    {
        $dosen = $this->buatUser('Dosen Uji Eta');

        $this->hitung();
        $this->assertNull($this->hasilDari($dosen)->ahp_id);

        $ahp = DssAhp::create(['matriks' => [], 'bobot' => [], 'aktif' => false]);
        $ahp->jadikanAktif();

        $this->hitung();
        $this->assertSame($ahp->id, (int) $this->hasilDari($dosen)->ahp_id);
    }

    public function test_perintah_dss_hitung_berhasil(): void
    {
        $dosen = $this->buatUser('Dosen Uji Teta');
        $this->publikasi($dosen, 'Jurnal');

        $this->artisan('dss:hitung')->assertSuccessful();
        $this->assertSame(1, DssHasil::count());

        $this->artisan('dss:hitung', ['--tampilkan' => true])->assertSuccessful();
        $this->assertSame(1, DssHasil::count());
    }

    public function test_perintah_dss_hitung_gagal_jika_kriteria_belum_ada(): void
    {
        DssKriteria::query()->delete();

        $this->artisan('dss:hitung')
            ->expectsOutputToContain('Data kriteria belum tersedia')
            ->assertFailed();
    }
}