<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndSuperAdminSeeder::class);
    }

    /** @test */
    public function admin_cannot_create_short_url()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $this->actingAs($admin)
            ->get(route('short-urls.create'))
            ->assertForbidden();
    }

    /** @test */
    public function member_cannot_create_short_url()
    {
        $member = User::factory()->create(['role_id' => Role::where('name', 'Member')->first()->id]);
        $this->actingAs($member)
            ->post(route('short-urls.store'), ['original_url' => 'https://example.com'])
            ->assertForbidden();
    }

    /** @test */
    public function superadmin_cannot_create_short_url()
    {
        $super = User::where('email', 'super@example.com')->first();
        $this->actingAs($super)
            ->get(route('short-urls.create'))
            ->assertForbidden();
    }

    /** @test */
    public function admin_sees_only_short_urls_not_created_in_their_company()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id, 'company_id' => 1]);
        $sales = User::factory()->create(['role_id' => Role::where('name', 'Sales')->first()->id, 'company_id' => 1]);
        ShortUrl::factory()->create(['user_id' => $sales->id]);

        $response = $this->actingAs($admin)->get(route('short-urls.index'));
        $response->assertSee('No short URLs'); // because it's the same company
    }

    /** @test */
    public function member_sees_only_short_urls_not_created_by_themselves()
    {
        $member = User::factory()->create(['role_id' => Role::where('name', 'Member')->first()->id]);
        $other = User::factory()->create(['role_id' => Role::where('name', 'Sales')->first()->id]);
        $myUrl = ShortUrl::factory()->create(['user_id' => $member->id]);
        $otherUrl = ShortUrl::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($member)->get(route('short-urls.index'));
        $response->assertDontSee($myUrl->short_code);
        $response->assertSee($otherUrl->short_code);
    }

    /** @test */
    public function short_urls_are_not_publicly_resolvable()
    {
        $url = ShortUrl::factory()->create();
        $this->get(route('shorten.redirect', $url->short_code))
            ->assertRedirect('login');
    }

    /** @test */
    public function short_url_redirects_to_original_when_authenticated()
    {
        $user = User::factory()->create();
        $url = ShortUrl::factory()->create();
        $this->actingAs($user)
            ->get(route('shorten.redirect', $url->short_code))
            ->assertRedirect($url->original_url);
    }
}