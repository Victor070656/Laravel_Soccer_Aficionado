<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_the_feed_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('feed'));

        $response->assertOk();
        $response->assertSee('Football Feed');
        $response->assertSee('post-composer-feed');
    }
}
