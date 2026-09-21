<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentManagerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_creator_approved_ditolak_mengakses_publikasi_cms(): void
    {
        $creator = User::factory()->contentCreator()->approved()->create();

        $this->actingAs($creator);

        $this->get(route('admin.publications.index'))->assertForbidden();
        $this->get(route('admin.publications.create'))->assertForbidden();
    }

    public function test_content_creator_approved_ditolak_mengakses_tim_cms(): void
    {
        $creator = User::factory()->contentCreator()->approved()->create();

        $this->actingAs($creator);

        $this->get(route('admin.teams.index'))->assertForbidden();
        $this->get(route('admin.teams.create'))->assertForbidden();
    }

    public function test_admin_tetap_bisa_mengakses_publikasi_cms(): void
    {
        $admin = User::factory()->admin()->approved()->create();

        $this->actingAs($admin);

        $this->get(route('admin.publications.index'))->assertOk();
    }

    public function test_admin_tetap_bisa_mengakses_tim_cms(): void
    {
        $admin = User::factory()->admin()->approved()->create();

        $this->actingAs($admin);

        $this->get(route('admin.teams.index'))->assertOk();
    }

    public function test_content_creator_approved_tetap_bisa_mengakses_modul_yang_diizinkan(): void
    {
        $creator = User::factory()->contentCreator()->approved()->create();

        $this->actingAs($creator);

        $this->get(route('admin.programs.index'))->assertOk();
        $this->get(route('admin.projects.index'))->assertOk();
        $this->get(route('admin.news.index'))->assertOk();
        $this->get(route('admin.partners.index'))->assertOk();
    }
}