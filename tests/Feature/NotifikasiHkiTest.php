<?php

namespace Tests\Feature;

use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotifikasiHkiTest extends TestCase
{
    use RefreshDatabase;

    private int $counter = 0;

    private function user(string $name, string $role = 'dosen', string $status = User::STATUS_APPROVED): User
    {
        $this->counter++;

        return User::create([
            'fullname' => $name,
            'email' => "user{$this->counter}@example.com",
            'nip' => (string) (100000 + $this->counter),
            'password' => 'rahasia123',
            'role' => $role,
            'registration_status' => $status,
        ]);
    }

    private function hki(array $override = []): array
    {
        return array_merge([
            'nomor_sertifikat' => 'EC00202400001',
            'tgl_terbit' => '2024-05-01',
            'judul_sertifikat' => 'Aplikasi Pemantau Banjir Kota',
            'jenis_sertifikat' => 'Hak Cipta',
            'pencipta' => 'Dosen A, Dosen B',
        ], $override);
    }

    private function adminHki(User $owner, array $override = []): array
    {
        return $this->hki(array_merge([
            'submission_type' => 'member',
            'user_id' => $owner->id,
            'status' => 'Draft',
        ], $override));
    }

    public function test_pencipta_lain_dapat_notifikasi_tetapi_penginput_tidak(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki())->assertRedirect(route('dosen.hki.index'));

        $this->assertSame(0, $a->notifications()->count());
        $this->assertSame(1, $b->notifications()->count());
        $this->assertSame('Dosen A', $b->notifications()->first()->data['oleh']);
    }

    public function test_pemilik_tidak_dapat_notifikasi_saat_admin_mempublish_hki_miliknya(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');
        $admin = $this->user('Admin Satu', 'admin');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $hki = Hki::firstOrFail();

        $this->actingAs($admin)->put("/admin/hki/{$hki->id}", $this->adminHki($a, ['status' => 'Publish']))
            ->assertRedirect(route('admin.hki.index'));

        $this->assertSame('Publish', $hki->fresh()->status);
        $this->assertSame(0, $a->notifications()->count());
        $this->assertSame(1, $b->notifications()->count());
    }

    public function test_hki_yang_diinput_admin_mengirim_notifikasi_ke_semua_pencipta(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');
        $admin = $this->user('Admin Satu', 'admin');

        $this->actingAs($admin)->post('/admin/hki', $this->adminHki($a))->assertRedirect(route('admin.hki.index'));

        $this->assertSame(1, $a->notifications()->count());
        $this->assertSame(1, $b->notifications()->count());
        $this->assertSame('Admin Satu', $b->notifications()->first()->data['oleh']);
    }

    public function test_nama_bergelar_yang_mengandung_koma_tetap_dapat_notifikasi(): void
    {
        $a = $this->user('Dosen A');
        $inne = $this->user('Dr. Inne Gartina Husein, S.Kom., M.T.');

        $this->actingAs($a)->post('/dosen/hki', $this->hki([
            'pencipta' => 'Dosen A, Dr. Inne Gartina Husein, S.Kom., M.T.',
        ]));

        $hki = Hki::firstOrFail();
        $this->assertTrue(Hki::forDosen($inne)->whereKey($hki->id)->exists());
        $this->assertSame(1, $inne->notifications()->count());
    }

    public function test_nama_yang_hanya_mirip_tidak_dapat_notifikasi(): void
    {
        $a = $this->user('Dosen A');
        $budi = $this->user('Budi');

        $this->actingAs($a)->post('/dosen/hki', $this->hki(['pencipta' => 'Dosen A, Budi Santoso']));

        $this->assertSame(0, $budi->notifications()->count());
    }

    public function test_edit_ulang_tidak_membuat_notifikasi_ganda_dan_judul_ikut_diperbarui(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $hki = Hki::firstOrFail();

        $this->actingAs($a)->put("/dosen/hki/{$hki->id}", $this->hki(['judul_sertifikat' => 'Judul Baru Sistem Banjir']));
        $this->actingAs($a)->put("/dosen/hki/{$hki->id}", $this->hki(['judul_sertifikat' => 'Judul Baru Sistem Banjir']));

        $data = $b->notifications()->first()->data;
        $this->assertSame(1, $b->notifications()->count());
        $this->assertSame('Judul Baru Sistem Banjir', $data['judul']);
        $this->assertStringEndsWith('Judul Baru Sistem Banjir', $data['message']);
    }

    public function test_nama_dikeluarkan_notifikasi_hilang_dan_dimasukkan_lagi_dapat_lagi(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $hki = Hki::firstOrFail();

        $this->actingAs($a)->put("/dosen/hki/{$hki->id}", $this->hki(['pencipta' => 'Dosen A']));
        $this->assertSame(0, $b->notifications()->count());

        $this->actingAs($a)->put("/dosen/hki/{$hki->id}", $this->hki());
        $this->assertSame(1, $b->notifications()->count());
    }

    public function test_hki_dihapus_maka_notifikasinya_ikut_terhapus(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');
        $admin = $this->user('Admin Satu', 'admin');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $this->actingAs($a)->delete('/dosen/hki/'.Hki::firstOrFail()->id);
        $this->assertSame(0, $b->notifications()->count());

        $this->actingAs($admin)->post('/admin/hki', $this->adminHki($a, ['nomor_sertifikat' => 'EC00202400002']));
        $this->assertSame(1, $b->notifications()->count());

        $this->actingAs($admin)->delete('/admin/hki/'.Hki::firstOrFail()->id);
        $this->assertSame(0, $b->notifications()->count());
    }

    public function test_akun_pending_dan_ditolak_tidak_dikirimi_notifikasi(): void
    {
        $a = $this->user('Dosen A');
        $pending = $this->user('Dosen B', 'dosen', User::STATUS_PENDING);
        $ditolak = $this->user('Dosen C', 'dosen', User::STATUS_REJECTED);

        $this->actingAs($a)->post('/dosen/hki', $this->hki(['pencipta' => 'Dosen A, Dosen B, Dosen C']));

        $this->assertSame(0, $pending->notifications()->count());
        $this->assertSame(0, $ditolak->notifications()->count());
    }

    public function test_klik_notifikasi_menandai_dibaca_dan_membuka_hki_saya(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $notif = $b->notifications()->first();

        $this->actingAs($b)->get(route('dosen.notifications.read', $notif->id))
            ->assertRedirect(route('dosen.hki.index'));

        $this->assertNotNull($notif->fresh()->read_at);
        $this->actingAs($b)->get(route('dosen.hki.index'))->assertSee('Aplikasi Pemantau Banjir Kota');
    }

    public function test_tidak_bisa_membuka_notifikasi_milik_dosen_lain(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');
        $c = $this->user('Dosen C');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $notif = $b->notifications()->first();

        $this->actingAs($c)->get(route('dosen.notifications.read', $notif->id))
            ->assertRedirect(route('dosen.notifications.index'));

        $this->assertNull($notif->fresh()->read_at);
    }

    public function test_tandai_semua_dibaca(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $this->actingAs($a)->post('/dosen/hki', $this->hki(['nomor_sertifikat' => 'EC00202400002']));
        $this->assertSame(2, $b->unreadNotifications()->count());

        $this->actingAs($b)->from('/dosen/notifications')->post(route('dosen.notifications.read-all'))
            ->assertRedirect('/dosen/notifications');

        $this->assertSame(0, $b->unreadNotifications()->count());
    }

    public function test_halaman_notifikasi_dan_lonceng_versi_hp_tampil(): void
    {
        $a = $this->user('Dosen A');
        $b = $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());

        $this->actingAs($b)->get(route('dosen.notifications.index'))
            ->assertOk()
            ->assertSee('Aplikasi Pemantau Banjir Kota')
            ->assertSee('Dosen A menambahkan Anda');

        $this->actingAs($b)->get('/beranda-dosen')
            ->assertOk()
            ->assertSee('data-notif-mobile', false)
            ->assertSee('Lihat semua notifikasi');
    }

    public function test_halaman_notifikasi_khusus_dosen(): void
    {
        $this->get(route('dosen.notifications.index'))->assertRedirect(route('login'));
        $this->actingAs($this->user('Admin Satu', 'admin'))->get(route('dosen.notifications.index'))->assertForbidden();
        $this->actingAs($this->user('Kreator', 'content_creator'))->get(route('dosen.notifications.index'))->assertForbidden();
    }

    public function test_input_dosen_langsung_publish_tanpa_draft(): void
    {
        Storage::fake('public');
        $a = $this->user('Dosen A');

        $this->actingAs($a)->post('/dosen/hki', $this->hki(['status' => 'Draft']))
            ->assertRedirect(route('dosen.hki.index'))
            ->assertSessionHas('success', 'HKI berhasil ditambahkan. HKI langsung dipublikasikan dan tampil di halaman publik.');

        $this->assertSame(Hki::STATUS_PUBLISH, Hki::firstOrFail()->status);

        $this->actingAs($a)->post('/dosen/publikasi', [
            'judul' => 'Sistem Parkir Cerdas Kota',
            'penulis' => 'Dosen A',
            'tahun' => 2024,
            'abstrak' => 'Ringkasan penelitian sistem parkir cerdas.',
            'kategori' => Publication::CATEGORY_JOURNAL,
            'status' => 'Draft',
            'pdf' => UploadedFile::fake()->create('makalah.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('dosen.publikasi.index'))
            ->assertSessionHas('success', 'Publikasi berhasil ditambahkan. Publikasi langsung dipublikasikan dan tampil di halaman publik.');

        $publication = Publication::firstOrFail();
        $this->assertSame(Publication::STATUS_PUBLISH, $publication->status);

        $this->get(route('publications.show', $publication))->assertOk()->assertSee('Sistem Parkir Cerdas Kota');
        $this->get(route('biografi.user', $a->id))->assertOk()->assertSee('Aplikasi Pemantau Banjir Kota');
    }

    public function test_edit_oleh_dosen_tetap_publish(): void
    {
        $a = $this->user('Dosen A');
        $this->user('Dosen B');

        $this->actingAs($a)->post('/dosen/hki', $this->hki());
        $hki = Hki::firstOrFail();

        $this->actingAs($a)->put("/dosen/hki/{$hki->id}", $this->hki(['status' => 'Draft']))
            ->assertRedirect(route('dosen.hki.index'));

        $this->assertSame(Hki::STATUS_PUBLISH, $hki->fresh()->status);
    }

    public function test_admin_tidak_bisa_menjadikan_konten_dosen_draft(): void
    {
        Storage::fake('public');
        $a = $this->user('Dosen A');
        $admin = $this->user('Admin Satu', 'admin');

        $this->actingAs($admin)->post('/admin/hki', $this->adminHki($a, ['status' => 'Draft']))
            ->assertRedirect(route('admin.hki.index'));
        $hki = Hki::firstOrFail();
        $this->assertSame(Hki::STATUS_PUBLISH, $hki->status);

        $this->actingAs($admin)->put("/admin/hki/{$hki->id}", $this->adminHki($a, ['status' => 'Draft']))
            ->assertRedirect(route('admin.hki.index'));
        $this->assertSame(Hki::STATUS_PUBLISH, $hki->fresh()->status);

        $this->actingAs($admin)->post('/admin/hki', $this->adminHki($a, ['nomor_sertifikat' => 'EC00202400002']))
            ->assertSessionHasNoErrors();
        $this->assertSame(0, Hki::where('status', Hki::STATUS_DRAFT)->count());

        $payload = [
            'submission_type' => 'member',
            'user_id' => $a->id,
            'judul' => 'Sistem Parkir Cerdas Kota',
            'penulis' => 'Dosen A',
            'tahun' => 2024,
            'abstrak' => 'Ringkasan penelitian sistem parkir cerdas.',
            'kategori' => Publication::CATEGORY_JOURNAL,
            'status' => 'Draft',
            'pdf' => UploadedFile::fake()->create('makalah.pdf', 20, 'application/pdf'),
        ];

        $this->actingAs($admin)->post('/admin/publications', $payload)
            ->assertRedirect(route('admin.publications.index'));
        $publication = Publication::firstOrFail();
        $this->assertSame(Publication::STATUS_PUBLISH, $publication->status);

        unset($payload['pdf'], $payload['status']);
        $this->actingAs($admin)->put("/admin/publications/{$publication->id}", $payload)
            ->assertSessionHasNoErrors();
        $this->assertSame(Publication::STATUS_PUBLISH, $publication->fresh()->status);

        $publication->forceFill(['status' => Publication::STATUS_DRAFT])->save();
        $this->assertSame(Publication::STATUS_PUBLISH, $publication->fresh()->status);

        $this->actingAs($admin)->post('/admin/hki', $this->hki([
            'nomor_sertifikat' => 'EC00202400003',
            'submission_type' => 'non_member',
            'recommended_by' => 'Pengusul Luar',
            'status' => 'Draft',
        ]))->assertSessionHasNoErrors();
        $this->assertSame(Hki::STATUS_DRAFT, Hki::where('nomor_sertifikat', 'EC00202400003')->value('status'));

        $this->actingAs($admin)->post('/admin/hki', $this->hki([
            'nomor_sertifikat' => 'EC00202400004',
            'submission_type' => 'non_member',
            'recommended_by' => 'Pengusul Luar',
        ]))->assertSessionHasErrors('status');
    }

    public function test_form_dosen_dan_admin_tidak_menawarkan_draft_untuk_konten_dosen(): void
    {
        $a = $this->user('Dosen A');
        $admin = $this->user('Admin Satu', 'admin');

        $this->actingAs($a)->get(route('dosen.publikasi.create'))->assertOk()->assertDontSee('value="Draft"', false);
        $this->actingAs($a)->get(route('dosen.hki.create'))->assertOk()->assertDontSee('value="Draft"', false);

        $this->actingAs($admin)->get(route('admin.hki.create'))->assertOk()
            ->assertSee('HKI milik dosen langsung dipublikasikan tanpa status Draft.');
        $this->actingAs($admin)->get(route('admin.publications.create'))->assertOk()
            ->assertSee('Publikasi milik dosen langsung dipublikasikan tanpa status Draft.');
    }
}