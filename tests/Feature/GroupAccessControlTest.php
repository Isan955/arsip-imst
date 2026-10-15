<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupAccessRule;
use App\Models\UserAccessRule;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Attachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class GroupAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_user_inherits_group_access_rules()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $group = Group::create([
            'name' => 'Kesekretariatan',
            'access_mode' => 'custom',
        ]);
        $group->users()->attach($viewer->id);

        $cat = Category::create(['name' => 'Kategori Kendaraan', 'slug' => 'kategori-kendaraan']);
        $author = User::factory()->create();

        $info = Information::create([
            'title' => 'Data Kendaraan',
            'slug' => 'data-kendaraan',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $versionJan = InformationVersion::create([
            'information_id' => $info->id,
            'month' => 1,
            'year' => 2026,
            'rich_content' => '<p>Laporan Januari 2026</p>'
        ]);

        $versionFeb = InformationVersion::create([
            'information_id' => $info->id,
            'month' => 2,
            'year' => 2026,
            'rich_content' => '<p>Laporan Februari 2026</p>'
        ]);

        $versionMar = InformationVersion::create([
            'information_id' => $info->id,
            'month' => 3,
            'year' => 2026,
            'rich_content' => '<p>Laporan Maret 2026</p>'
        ]);

        // Group has access to versionJan and versionFeb
        GroupAccessRule::create([
            'group_id' => $group->id,
            'accessible_id' => $versionJan->id,
            'accessible_type' => InformationVersion::class
        ]);
        GroupAccessRule::create([
            'group_id' => $group->id,
            'accessible_id' => $versionFeb->id,
            'accessible_type' => InformationVersion::class
        ]);

        $this->actingAs($viewer);

        // Viewer can access Jan and Feb via group
        $this->assertTrue(Gate::allows('view-version', $versionJan));
        $this->assertTrue(Gate::allows('view-version', $versionFeb));
        // Viewer cannot access Mar
        $this->assertFalse(Gate::allows('view-version', $versionMar));
    }

    public function test_effective_access_is_union_of_group_and_individual()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $group = Group::create([
            'name' => 'Divisi Umum',
            'access_mode' => 'custom',
        ]);
        $group->users()->attach($viewer->id);

        $cat = Category::create(['name' => 'Umum', 'slug' => 'umum']);
        $author = User::factory()->create();

        $infoA = Information::create([
            'title' => 'Dokumen A',
            'slug' => 'dokumen-a',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $infoB = Information::create([
            'title' => 'Dokumen B',
            'slug' => 'dokumen-b',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        // Group gives access to infoA
        GroupAccessRule::create([
            'group_id' => $group->id,
            'accessible_id' => $infoA->id,
            'accessible_type' => Information::class
        ]);

        // User gets individual access to infoB
        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $infoB->id,
            'accessible_type' => Information::class
        ]);

        $this->actingAs($viewer);

        // Effective access is Union: both infoA and infoB allowed
        $this->assertTrue(Gate::allows('view-information', $infoA));
        $this->assertTrue(Gate::allows('view-information', $infoB));
    }

    public function test_removing_user_from_group_revokes_group_access_but_preserves_individual_access()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $group = Group::create([
            'name' => 'Keuangan',
            'access_mode' => 'custom',
        ]);
        $group->users()->attach($viewer->id);

        $cat = Category::create(['name' => 'Keuangan', 'slug' => 'keuangan']);
        $author = User::factory()->create();

        $infoGroup = Information::create([
            'title' => 'Dokumen Group',
            'slug' => 'dokumen-group',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $infoIndividual = Information::create([
            'title' => 'Dokumen Pribadi',
            'slug' => 'dokumen-pribadi',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        GroupAccessRule::create([
            'group_id' => $group->id,
            'accessible_id' => $infoGroup->id,
            'accessible_type' => Information::class
        ]);

        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $infoIndividual->id,
            'accessible_type' => Information::class
        ]);

        $this->actingAs($viewer);
        $this->assertTrue(Gate::allows('view-information', $infoGroup));
        $this->assertTrue(Gate::allows('view-information', $infoIndividual));

        // Remove user from group
        $group->users()->detach($viewer->id);

        // Group access is revoked, individual access remains
        $this->assertFalse(Gate::allows('view-information', $infoGroup));
        $this->assertTrue(Gate::allows('view-information', $infoIndividual));
    }

    public function test_attachment_stream_enforces_group_permissions()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $group = Group::create([
            'name' => 'Audit',
            'access_mode' => 'custom',
        ]);
        $group->users()->attach($viewer->id);

        $cat = Category::create(['name' => 'Audit Internal', 'slug' => 'audit-internal']);
        $author = User::factory()->create();

        $info = Information::create([
            'title' => 'Laporan Audit',
            'slug' => 'laporan-audit',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $version = InformationVersion::create([
            'information_id' => $info->id,
            'month' => 3,
            'year' => 2026,
            'rich_content' => '<p>Audit Content</p>'
        ]);

        $attachment = Attachment::create([
            'information_version_id' => $version->id,
            'file_name' => 'audit.pdf',
            'file_path' => 'attachments/audit.pdf',
            'file_size' => 1024
        ]);

        // Before grant: forbidden (403)
        $response = $this->actingAs($viewer)->get(route('attachments.stream', $attachment->id));
        $response->assertStatus(403);

        // Grant version to group
        GroupAccessRule::create([
            'group_id' => $group->id,
            'accessible_id' => $version->id,
            'accessible_type' => InformationVersion::class
        ]);

        // After grant: allowed to stream (200 or 404 for mock storage path)
        $response = $this->actingAs($viewer)->get(route('attachments.stream', $attachment->id));
        $this->assertTrue(in_array($response->status(), [200, 404]));
    }

    public function test_home_page_only_displays_accessible_data_and_counts_for_user()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $catAllowed = Category::create(['name' => 'Kategori Diizinkan', 'slug' => 'kategori-diizinkan']);
        $catForbidden = Category::create(['name' => 'Kategori Rahasia', 'slug' => 'kategori-rahasia']);
        $author = User::factory()->create();

        $infoAllowed = Information::create([
            'title' => 'Dokumen Publik Saya',
            'slug' => 'dokumen-publik-saya',
            'category_id' => $catAllowed->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $infoForbidden = Information::create([
            'title' => 'Dokumen Sangat Rahasia',
            'slug' => 'dokumen-sangat-rahasia',
            'category_id' => $catForbidden->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        // Grant access only to $infoAllowed
        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $infoAllowed->id,
            'accessible_type' => Information::class
        ]);

        $response = $this->actingAs($viewer)->get('/home');
        $response->assertStatus(200);
        $response->assertSee('Dokumen Publik Saya');
        $response->assertDontSee('Dokumen Sangat Rahasia');

        // Search for forbidden document should return 0 results
        $searchResponse = $this->actingAs($viewer)->get('/home?search=Rahasia');
        $searchResponse->assertStatus(200);
        $searchResponse->assertDontSee('Dokumen Sangat Rahasia');
    }

    public function test_bookmarks_page_only_displays_accessible_bookmarked_items()
    {
        $viewer = User::factory()->create(['access_mode' => 'custom']);
        $viewer->assignRole('Viewer');

        $cat = Category::create(['name' => 'Kategori Umum', 'slug' => 'kategori-umum']);
        $author = User::factory()->create();

        $infoAllowed = Information::create([
            'title' => 'Bookmark Boleh',
            'slug' => 'bookmark-boleh',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $infoForbidden = Information::create([
            'title' => 'Bookmark Tidak Boleh',
            'slug' => 'bookmark-tidak-boleh',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        // User bookmarks both
        \App\Models\Bookmark::create(['user_id' => $viewer->id, 'information_id' => $infoAllowed->id]);
        \App\Models\Bookmark::create(['user_id' => $viewer->id, 'information_id' => $infoForbidden->id]);

        // Grant access only to $infoAllowed
        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $infoAllowed->id,
            'accessible_type' => Information::class
        ]);

        $response = $this->actingAs($viewer)->get('/bookmarks');
        $response->assertStatus(200);
        $response->assertSee('Bookmark Boleh');
        $response->assertDontSee('Bookmark Tidak Boleh');
    }
}
