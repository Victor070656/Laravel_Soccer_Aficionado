<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrendingTest extends TestCase
{
    use RefreshDatabase;

    public function test_trending_page_loads_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('trending'));

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_trending(): void
    {
        $response = $this->get(route('trending'));

        $response->assertRedirect(route('login'));
    }
}
