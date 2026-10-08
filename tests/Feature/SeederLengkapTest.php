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
use Database\Seeders\AkunSeeder;
use Database\Seeders\AkunUjiLoginSeeder;
use Database\Seeders\KontenSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SeederLengkapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function akun(string $email): User
    {
        return User::query()->where(User::emailCredential($email))->firstOrFail();
    }

    private function jumlahKonten(): array
    {
        return [
            'program' => [Program::count(), Program::published()->count()],
            'proyek' => [Project::count(), Project::published()->count()],
            'berita' => [News::count(), News::published()->count()],
            'mitra' => [Partner::count(), Partner::published()->count()],
            'tim' => [Team::count(), Team::published()->count()],
            'publikasi' => [Publication::count(), Publication::published()->count()],
            'hki' => [Hki::count(), Hki::published()->count()],
        ];
    }

    public function test_seeder_membuat_semua_akun_uji_dengan_kondisi_yang_benar(): void
    {
        $this->seed();

        $this->assertSame(14, User::count());
        $this->assertSame(5, User::where('registration_status', User::STATUS_PENDING)->count());
        $this->assertSame(2, User::where('registration_status', User::STATUS_REJECTED)->whereNotNull('rejection_reason')->count());
        $this->assertSame('admin', $this->akun('admin@smartcity.ac.id')->role);
        $this->assertSame('content_creator', $this->akun('creator@smartcity.ac.id')->role);

        foreach (User::all() as $user) {
            $this->assertTrue(Hash::check(AkunSeeder::PASSWORD, $user->password), $user->email);
            $this->assertFalse($user->isLocked(), $user->email);
        }

        $this->assertTrue($this->akun('dosen.lama@smartcity.ac.id')->isDormant());
        $this->assertFalse($this->akun('dosen.nonaktif@smartcity.ac.id')->isActive());

        foreach (User::whereNotIn('email', ['dosen.lama@smartcity.ac.id', 'dosen.nonaktif@smartcity.ac.id'])->get() as $user) {
            $this->assertFalse($user->isDormant(), $user->email);
            $this->assertTrue($user->isActive(), $user->email);
        }
    }

    public function test_seeder_membuat_konten_lengkap_beserta_file(): void
    {
        $this->seed();

        $this->assertSame([
            'program' => [5, 4],
            'proyek' => [6, 5],
            'berita' => [11, 10],
            'mitra' => [6, 5],
            'tim' => [8, 7],
            'publikasi' => [12, 10],
            'hki' => [8, 6],
        ], $this->jumlahKonten());

        $disk = Storage::disk('public');

        foreach (Publication::all() as $publikasi) {
            $disk->assertExists([$publikasi->pdf_path, $publikasi->thumbnail_path]);
            $this->assertStringStartsWith('%PDF-1.4', $disk->get($publikasi->pdf_path));
            $this->assertStringEndsWith("%%EOF\n", $disk->get($publikasi->pdf_path));
        }

        foreach (Hki::all() as $hki) {
            $disk->assertExists($hki->file_sertifikat);
        }

        foreach (Project::all() as $proyek) {
            $disk->assertExists(array_filter([$proyek->thumbnail_path, $proyek->dokumen_path, ...($proyek->gallery_paths ?? [])]));
        }

        $disk->assertExists(Program::pluck('thumbnail_path')->all());
        $disk->assertExists(News::pluck('thumbnail_path')->all());
        $disk->assertExists(Partner::pluck('logo_path')->all());

        $this->assertSame([2026, 2025, 2024], News::published()->pluck('published_at')->map(fn ($tanggal) => (int) $tanggal->format('Y'))->unique()->sortDesc()->values()->all());

        $dosenA = $this->akun('dosenA@smartcity.ac.id');
        $dosenB = $this->akun('dosenB@smartcity.ac.id');
        $dosenC = $this->akun('dosenC@smartcity.ac.id');

        $this->assertSame([1, 2, 0], [$dosenA->notifications()->count(), $dosenB->notifications()->count(), $dosenC->notifications()->count()]);
        $this->assertSame([5, 4], [Publication::forDosen($dosenA)->count(), Hki::forDosen($dosenA)->count()]);
        $this->assertSame([6, 5], [Publication::forDosen($dosenB)->count(), Hki::forDosen($dosenB)->count()]);
        $this->assertSame([0, 0], [Publication::forDosen($dosenC)->count(), Hki::forDosen($dosenC)->count()]);
    }

    public function test_seeder_bisa_dijalankan_ulang_dan_mengembalikan_data_uji(): void
    {
        $this->seed();
        $sebelum = $this->jumlahKonten();

        $this->akun('dosenB@smartcity.ac.id')->forceFill([
            'email' => 'dosenb.baru@kampus.ac.id',
            'password' => 'PasswordBaru123',
            'locked_until' => now()->addMinutes(30),
            'is_active' => false,
        ])->save();
        $this->akun('dosenA@smartcity.ac.id')->forceFill(['email' => 'dosena@smartcity.ac.id'])->save();
        $this->akun('dosen.pending@smartcity.ac.id')->forceFill(['registration_status' => User::STATUS_APPROVED])->save();
        $this->akun('dosen.lama@smartcity.ac.id')->forceFill(['last_login_at' => now()])->save();
        Program::where('judul', 'Smart Campus Living Lab')->update(['deskripsi' => 'Deskripsi diubah oleh penguji saat menguji edit program.']);

        $this->seed();

        $this->assertSame(14, User::count());
        $this->assertSame($sebelum, $this->jumlahKonten());

        $dosenB = User::where('nip', '198501012015011002')->firstOrFail();
        $this->assertSame('dosenB@smartcity.ac.id', $dosenB->email);
        $this->assertTrue(Hash::check(AkunSeeder::PASSWORD, $dosenB->password));
        $this->assertTrue($dosenB->isActive());
        $this->assertFalse($dosenB->isLocked());

        $this->assertSame('dosenA@smartcity.ac.id', User::where('nip', '198501012015011001')->value('email'));
        $this->assertSame(User::STATUS_PENDING, $this->akun('dosen.pending@smartcity.ac.id')->registration_status);
        $this->assertTrue($this->akun('dosen.lama@smartcity.ac.id')->isDormant());
        $this->assertStringStartsWith('Lingkungan kampus dimanfaatkan', Program::where('judul', 'Smart Campus Living Lab')->value('deskripsi'));
        $this->assertSame([1, 2], [$this->akun('dosenA@smartcity.ac.id')->notifications()->count(), $dosenB->notifications()->count()]);
    }

    public function test_akun_seeder_saja_untuk_menguji_halaman_tanpa_data(): void
    {
        $this->seed(AkunSeeder::class);

        $this->assertSame(14, User::count());
        $this->assertSame(0, array_sum(array_map(fn (array $jumlah) => $jumlah[0], $this->jumlahKonten())));

        foreach (['/program-user', '/project-user', '/news-user', '/publication-user', '/mitra-user', '/team-user'] as $halaman) {
            $this->get($halaman)->assertOk()->assertSee('Data belum tersedia');
        }

        $this->actingAs($this->akun('creator@smartcity.ac.id'))->get('/beranda-creator')->assertOk()->assertSee('Belum terdapat data.');

        $this->seed(KontenSeeder::class);

        $this->assertSame(12, Publication::count());
        $this->assertSame(14, User::count());
    }

    public function test_akun_uji_login_bisa_dijalankan_terpisah(): void
    {
        $this->seed(AkunUjiLoginSeeder::class);

        $this->assertSame(2, User::count());
        $this->assertTrue($this->akun('dosen.lama@smartcity.ac.id')->isDormant());
        $this->assertFalse($this->akun('dosen.nonaktif@smartcity.ac.id')->isActive());
    }

    public function test_konten_seeder_membuat_akun_bila_belum_ada(): void
    {
        $this->seed(KontenSeeder::class);

        $this->assertSame(14, User::count());
        $this->assertSame(12, Publication::count());
    }

    public function test_akun_seeder_bisa_langsung_dipakai_login(): void
    {
        Notification::fake();
        $this->seed();

        $this->post('/login', ['email' => 'dosen.lama@smartcity.ac.id', 'password' => 'password'])
            ->assertRedirect(route('login.keaktifan.tampil'));

        $this->flushSession();
        $this->from(route('login'))->post('/login', ['email' => 'dosen.nonaktif@smartcity.ac.id', 'password' => 'password'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => User::INACTIVE_MESSAGE]);

        foreach (['admin@smartcity.ac.id', 'creator@smartcity.ac.id', 'dosenA@smartcity.ac.id', 'dosenC@smartcity.ac.id', 'dosen.pending@smartcity.ac.id', 'dosen.rejected@smartcity.ac.id'] as $email) {
            $this->flushSession();
            $this->post('/login', ['email' => $email, 'password' => 'password'])
                ->assertRedirect(route('login.konfirmasi.menunggu'));
        }
    }

    public function test_halaman_publik_menampilkan_konten_seeder_dan_pdf_bisa_diunduh(): void
    {
        $this->seed();

        $this->get('/')->assertOk()->assertSee('Smart Campus Living Lab')->assertSee('Sistem Monitoring Lalu Lintas Cerdas');
        $this->get('/program-user')->assertOk()->assertSee('Inkubasi Startup Teknologi Perkotaan')->assertDontSee('Program Riset Kolaboratif Smart Mobility');
        $this->get('/project-user')->assertOk()->assertSee('Dasbor Kualitas Udara Kota')->assertDontSee('Prototipe Smart Waste Management');
        $this->get('/news-user')->assertOk()->assertSee('Peluncuran Portal Smart City Kota')->assertSee('page=2', false)->assertDontSee('Draft Berita: Rencana Kerja Sama Riset 2026');
        $this->get('/publication-user')->assertOk()->assertSee('Model Prediksi Kemacetan Menggunakan Data Sensor Lalu Lintas')->assertSee('page=2', false);
        $this->get('/mitra-user')->assertOk()->assertSee('Komunitas Inovator Kota Cerdas')->assertDontSee('Universitas Mitra Riset (Draft)');
        $this->get('/team-user')->assertOk()->assertSee('Nadia Putri')->assertSee('Dosen A Pengirim')->assertDontSee('Rian Pratama');
        $this->get('/cari?q=kota')->assertOk()->assertSee('Peluncuran Portal Smart City Kota');

        $berita = News::published()->firstOrFail();
        $this->get(route('news.show', $berita))->assertOk()->assertSee($berita->judul);

        $publikasi = Publication::published()->where('judul', 'Pengembangan Sistem Smart City Berbasis IoT')->firstOrFail();
        $this->get(route('publications.show', $publikasi))->assertOk()->assertSee('10.1234/jti.2024.001');
        $this->get(route('publications.download', $publikasi))->assertOk()->assertDownload(Str::slug($publikasi->judul).'.pdf');

        $draft = Publication::where('status', Publication::STATUS_DRAFT)->firstOrFail();
        $this->get(route('publications.download', $draft))->assertNotFound();

        $dosenA = $this->akun('dosenA@smartcity.ac.id');
        $this->get(route('biografi.user', $dosenA->id))->assertOk()->assertSee('Hak Cipta Perangkat Lunak SmartCity Monitoring');
    }

    public function test_dashboard_akun_seeder_menampilkan_data_sesuai_kondisi(): void
    {
        $this->seed();

        $this->actingAs($this->akun('dosenA@smartcity.ac.id'))->get('/beranda-dosen')->assertOk()
            ->assertSee('5 entri')->assertSee('4 entri')->assertSee('1 notifikasi belum dibaca');

        $this->actingAs($this->akun('dosenB@smartcity.ac.id'))->get('/beranda-dosen')->assertOk()
            ->assertSee('6 entri')->assertSee('5 entri')->assertSee('2 notifikasi belum dibaca');

        $this->actingAs($this->akun('dosenC@smartcity.ac.id'))->get('/beranda-dosen')->assertOk()
            ->assertSee('Belum ada data publikasi dan HKI')->assertSee('Kelola Publikasi');

        $this->actingAs($this->akun('dosen.pending@smartcity.ac.id'))->get('/dosen/status')->assertOk()
            ->assertSee('Registrasi Anda masih menunggu validasi Admin');

        $this->actingAs($this->akun('dosen.rejected@smartcity.ac.id'))->get('/dosen/status')->assertOk()
            ->assertSee('NIP yang dimasukkan tidak sesuai dengan data kepegawaian.');

        $this->actingAs($this->akun('creator@smartcity.ac.id'))->get('/beranda-creator')->assertOk()
            ->assertDontSee('Belum terdapat data.');

        $admin = $this->akun('admin@smartcity.ac.id');
        $this->actingAs($admin)->get('/beranda-admin')->assertOk()->assertSee('Registrasi Menunggu');
        $this->actingAs($admin)->get('/admin/validasi-registrasi?status=pending')->assertOk()
            ->assertSee('dosen.pending3@smartcity.ac.id')->assertSee('creator.pending2@smartcity.ac.id');
        $this->actingAs($admin)->get('/admin/validasi-registrasi?status=inactive')->assertOk()
            ->assertSee('dosen.nonaktif@smartcity.ac.id')->assertSee('Aktifkan');
    }

    public function test_data_seeder_lolos_validasi_saat_disimpan_ulang_dari_form_edit(): void
    {
        $this->seed();

        $this->actingAs($this->akun('admin@smartcity.ac.id'));

        foreach (Program::all() as $item) {
            $this->put("/admin/programs/{$item->id}", $item->only(['judul', 'deskripsi', 'urutan', 'status']))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (Project::all() as $item) {
            $this->put("/admin/projects/{$item->id}", $item->only(['judul', 'deskripsi', 'kategori', 'partner', 'tahun', 'status']))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (News::all() as $item) {
            $this->put("/admin/news/{$item->id}", $item->only(['judul', 'kategori', 'konten', 'status']))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (Partner::all() as $item) {
            $this->put("/admin/partners/{$item->id}", array_filter($item->only(['nama', 'deskripsi', 'website', 'status', 'urutan']), fn ($nilai) => $nilai !== null))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (Team::all() as $item) {
            $this->put("/admin/teams/{$item->id}", array_filter($item->only(['nama', 'jabatan', 'bidang', 'email', 'tipe', 'status', 'urutan']), fn ($nilai) => $nilai !== null))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (Hki::all() as $item) {
            $this->put("/admin/hki/{$item->id}", array_filter([
                'nomor_sertifikat' => $item->nomor_sertifikat,
                'tgl_terbit' => $item->tgl_terbit->format('Y-m-d'),
                'judul_sertifikat' => $item->judul_sertifikat,
                'jenis_sertifikat' => $item->jenis_sertifikat,
                'pencipta' => $item->pencipta,
                'submission_type' => $item->submission_type,
                'user_id' => $item->user_id,
                'recommended_by' => $item->recommended_by,
                'status' => $item->status,
            ], fn ($nilai) => $nilai !== null))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (Publication::all() as $item) {
            $this->put("/admin/publications/{$item->id}", array_filter($item->only([
                'submission_type', 'user_id', 'recommended_by', 'judul', 'penulis', 'tahun', 'abstrak', 'kategori', 'penerbit', 'doi', 'status',
            ]), fn ($nilai) => $nilai !== null))->assertSessionHasNoErrors()->assertSessionHas('success');
        }

        foreach (['dosenA@smartcity.ac.id', 'dosenB@smartcity.ac.id', 'dosenC@smartcity.ac.id'] as $email) {
            $dosen = $this->akun($email);

            $this->actingAs($dosen)->put('/profil-dosen', array_filter(
                $dosen->only(['fullname', 'email', 'nip', 'prodi', 'fakultas', 'bio', 'bidang_penelitian']),
                fn ($nilai) => $nilai !== null
            ))->assertSessionHasNoErrors()->assertSessionHas('success');
        }
    }
}
