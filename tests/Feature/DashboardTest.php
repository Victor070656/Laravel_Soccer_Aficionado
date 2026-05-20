<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $primaryClub = Club::create([
            'name' => 'Arsenal',
            'slug' => Str::slug('Arsenal'),
            'country' => 'England',
            'is_active' => true,
        ]);
        $secondaryClub = Club::create([
            'name' => 'Barcelona',
            'slug' => Str::slug('Barcelona'),
            'country' => 'Spain',
            'is_active' => true,
        ]);
        $user->favoriteClubs()->attach($primaryClub->id, ['is_primary' => true]);
        $user->favoriteClubs()->attach($secondaryClub->id, ['is_primary' => false]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('Arsenal');
        $response->assertSee('Barcelona');
    }

    public function test_usernames_are_automatically_generated_on_creation(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $this->assertNotNull($user->username);
        $this->assertEquals('john-doe', $user->username);
    }
}
