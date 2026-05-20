<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommunityPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_the_new_community_index_design(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('communities.index'));

        $response->assertOk();
        $response->assertSee('Club Communities');
        $response->assertSee('Create Community');
    }

    public function test_authenticated_users_can_view_the_new_community_show_design(): void
    {
        $user = User::factory()->create();
        $community = Community::create([
            'created_by' => $user->id,
            'name' => 'North London Supporters',
            'slug' => Str::slug('North London Supporters'),
            'description' => 'A community for supporters.',
            'members_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('communities.show', $community));

        $response->assertOk();
        $response->assertSee('Top Members');
        $response->assertSee('Community Stats');
    }
}
