<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Attachment;
use App\Models\UserAccessRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed');
    }

    public function test_deactivated_user_cannot_access_any_pages()
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);
        $user->assignRole('Viewer');

        $response = $this->actingAs($user)->get('/home');
        $response->assertRedirect(route('login'));
        $this->assertFalse(auth()->check());
    }

    public function test_viewer_with_categories_access_mode_only_sees_assigned_categories()
    {
        $viewer = User::factory()->create([
            'access_mode' => 'categories',
        ]);
        $viewer->assignRole('Viewer');

        // Create categories
        $cat1 = Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);
        $cat2 = Category::create(['name' => 'Cat B', 'slug' => 'cat-b']);

        // Give viewer access to Cat 1 only
        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $cat1->id,
            'accessible_type' => Category::class
        ]);

        $this->actingAs($viewer);

        $this->assertTrue(\Illuminate\Support\Facades\Gate::allows('view-category', $cat1));
        $this->assertFalse(\Illuminate\Support\Facades\Gate::allows('view-category', $cat2));
    }

    public function test_secure_pdf_streaming_authorization()
    {
        $viewer = User::factory()->create([
            'access_mode' => 'custom',
        ]);
        $viewer->assignRole('Viewer');

        $author = User::factory()->create();
        $cat = Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);
        
        $info = Information::create([
            'title' => 'Secure Info',
            'slug' => 'secure-info',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        $version = InformationVersion::create([
            'information_id' => $info->id,
            'month' => 5,
            'year' => 2026,
            'rich_content' => '<p>Secret</p>'
        ]);

        $attachment = Attachment::create([
            'information_version_id' => $version->id,
            'file_name' => 'test.pdf',
            'file_path' => 'attachments/test.pdf',
            'file_size' => 1234
        ]);

        // Access to stream PDF without permission should be 403 Forbidden
        $response = $this->actingAs($viewer)->get(route('attachments.stream', $attachment->id));
        $response->assertStatus(403);

        // Map version access
        UserAccessRule::create([
            'user_id' => $viewer->id,
            'accessible_id' => $version->id,
            'accessible_type' => InformationVersion::class
        ]);

        // Should now allow access
        $response = $this->actingAs($viewer)->get(route('attachments.stream', $attachment->id));
        // Note: will look for file or fail with 404 which indicates permission check passed (instead of 403)
        $this->assertTrue(in_array($response->status(), [404, 200]));
    }

    public function test_newly_created_viewer_has_zero_access_by_default_until_granted_by_superadmin()
    {
        // Create fresh viewer without assigning any access
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $this->assertEquals('custom', $viewer->access_mode);
        $this->assertFalse($viewer->hasFullAccess());
        $this->assertEmpty($viewer->getAllowedCategoryIds());
        $this->assertEmpty($viewer->getAllowedInformationIds());
        $this->assertEmpty($viewer->getAllowedVersionIds());

        // Create published document
        $cat = Category::create(['name' => 'Kategori Internal', 'slug' => 'kategori-internal']);
        $author = User::factory()->create();
        $info = Information::create([
            'title' => 'Dokumen Terkunci',
            'slug' => 'dokumen-terkunci',
            'category_id' => $cat->id,
            'author_id' => $author->id,
            'status' => 'published'
        ]);

        // Home should not reveal the document
        $response = $this->actingAs($viewer)->get('/home');
        $response->assertStatus(200);
        $response->assertDontSee('Dokumen Terkunci');

        // Direct access to detail page should be blocked with 403 Forbidden
        $detailResponse = $this->actingAs($viewer)->get(route('info.show', $info->slug));
        $detailResponse->assertStatus(403);
    }
}
