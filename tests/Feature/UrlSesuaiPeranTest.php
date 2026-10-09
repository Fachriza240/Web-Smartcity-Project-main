<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UrlSesuaiPeranTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Storage::fake('public');
    }

    private function makeUser(string $role, string $status = User::STATUS_APPROVED): User
    {
        $this->seq++;

        return User::create([
            'fullname' => 'Pengguna Uji',
            'email' => "peran{$this->seq}@kampus.ac.id",
            'nip' => $role === 'content_creator' ? null : (string) (700000 + $this->seq),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => $status,
        ]);
    }

    public function test_menu_admin_memakai_url_admin(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->get('/beranda-admin')->assertOk()
            ->assertSee(url('/admin/validasi-registrasi'), false)
            ->assertSee(url('/admin/programs'), false)
            ->assertSee(url('/admin/news'), false)
            ->assertSee(url('/admin/settings'), false)
            ->assertDontSee(url('/creator/'), false);

        foreach (['programs', 'projects', 'publications', 'hki', 'news', 'teams', 'partners', 'settings', 'validasi-registrasi'] as $menu) {
            $this->actingAs($admin)->get("/admin/{$menu}")->assertOk();
        }
    }

    public function test_menu_content_creator_memakai_url_creator(): void
    {
        $creator = $this->makeUser('content_creator');

        $this->actingAs($creator)->get('/beranda-creator')->assertOk()
            ->assertSee(url('/creator/programs'), false)
            ->assertSee(url('/creator/projects'), false)
            ->assertSee(url('/creator/news'), false)
            ->assertSee(url('/creator/partners'), false)
            ->assertSee(url('/creator/settings'), false)
            ->assertDontSee(url('/admin/'), false);

        foreach (['programs', 'projects', 'news', 'partners', 'settings'] as $menu) {
            $this->actingAs($creator)->get("/creator/{$menu}")->assertOk()->assertDontSee(url('/admin/'), false);
        }

        $this->actingAs($creator)->get('/creator/news/create')->assertOk()
            ->assertSee('action="'.url('/creator/news').'"', false);
    }

    public function test_content_creator_menyimpan_dan_mengubah_data_lewat_url_creator(): void
    {
        $creator = $this->makeUser('content_creator');

        $this->actingAs($creator)->post('/creator/news', [
            'judul' => 'Berita Dari Creator',
            'konten' => str_repeat('k', 30),
            'thumbnail' => UploadedFile::fake()->image('t.jpg'),
            'status' => 'Draft',
        ])->assertRedirect(url('/creator/news'));

        $news = News::firstOrFail();

        $this->actingAs($creator)->get("/creator/news/{$news->id}/edit")->assertOk()
            ->assertSee(url("/creator/news/{$news->id}"), false);

        $this->actingAs($creator)->delete("/creator/news/{$news->id}")->assertRedirect(url('/creator/news'));
        $this->assertDatabaseCount('news', 0);
    }

    public function test_url_peran_lain_ditolak(): void
    {
        $admin = $this->makeUser('admin');
        $creator = $this->makeUser('content_creator');
        $dosen = $this->makeUser('dosen');

        foreach (['/admin/news', '/admin/programs', '/admin/settings', '/admin/validasi-registrasi'] as $url) {
            $this->actingAs($creator)->get($url)->assertForbidden()->assertSee('Anda tidak memiliki hak akses');
        }

        foreach (['/creator/news', '/creator/programs', '/creator/settings'] as $url) {
            $this->actingAs($admin)->get($url)->assertForbidden();
            $this->actingAs($dosen)->get($url)->assertForbidden();
        }

        $this->actingAs($dosen)->get('/admin/news')->assertForbidden();

        foreach (['/creator/validasi-registrasi', '/creator/publications', '/creator/hki', '/creator/teams'] as $url) {
            $this->actingAs($creator)->get($url)->assertForbidden();
        }

        $this->actingAs($creator)->post('/admin/news', ['judul' => 'Coba'])->assertForbidden();
        $this->assertDatabaseCount('news', 0);
    }

    public function test_tamu_yang_membuka_url_panel_diarahkan_ke_login(): void
    {
        foreach (['/admin/news', '/creator/news', '/dosen/status', '/creator/status'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_url_status_registrasi_sesuai_peran(): void
    {
        $creator = $this->makeUser('content_creator', User::STATUS_PENDING);
        $dosen = $this->makeUser('dosen', User::STATUS_PENDING);

        $this->actingAs($creator)->get('/creator/status')->assertOk();
        $this->actingAs($creator)->get('/dosen/status')->assertRedirect(url('/creator/status'));
        $this->actingAs($creator)->get('/beranda-creator')->assertRedirect(url('/creator/status'));
        $this->actingAs($creator)->get('/creator/news')->assertRedirect(url('/creator/status'));

        $this->actingAs($dosen)->get('/dosen/status')->assertOk();
        $this->actingAs($dosen)->get('/creator/status')->assertRedirect(url('/dosen/status'));
        $this->actingAs($dosen)->get('/beranda-dosen')->assertRedirect(url('/dosen/status'));
    }

    public function test_content_creator_pending_login_diarahkan_ke_status_creator(): void
    {
        $creator = $this->makeUser('content_creator', User::STATUS_PENDING);

        $this->post('/login', ['email' => $creator->email, 'password' => 'rahasia123'])
            ->assertRedirect(url('/creator/status'));
    }

    public function test_url_lama_diarahkan_sesuai_peran(): void
    {
        $admin = $this->makeUser('admin');
        $creator = $this->makeUser('content_creator');

        $this->actingAs($admin)->get('/news-admin')->assertRedirect(url('/admin/news'));
        $this->actingAs($admin)->get('/program-admin')->assertRedirect(url('/admin/programs'));
        $this->actingAs($admin)->get('/research-team-admin')->assertRedirect(url('/admin/teams'));

        $this->actingAs($creator)->get('/news-admin')->assertRedirect(url('/creator/news'));
        $this->actingAs($creator)->get('/program-admin')->assertRedirect(url('/creator/programs'));
    }

    public function test_aksi_validasi_registrasi_tetap_berjalan_di_url_admin(): void
    {
        $admin = $this->makeUser('admin');
        $pending = $this->makeUser('content_creator', User::STATUS_PENDING);

        $this->actingAs($admin)->post("/admin/validasi-registrasi/{$pending->id}/approve")->assertRedirect();
        $this->assertSame(User::STATUS_APPROVED, $pending->fresh()->registration_status);
    }
}