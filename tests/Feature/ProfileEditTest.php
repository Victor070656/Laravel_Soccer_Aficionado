<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profiles.edit');
        $response->assertViewHas('user', $user);
    }

    public function test_unauthenticated_user_cannot_view_profile_edit_page(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_update_all_profile_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'johndoe',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'username' => 'janedoe',
            'bio' => 'I love football!',
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'timezone' => 'Africa/Lagos',
            'favorite_coach' => 'Carlo Ancelotti',
            'football_personality' => 'Tactical Analyst',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'username' => 'janedoe',
            'bio' => 'I love football!',
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'timezone' => 'Africa/Lagos',
            'favorite_coach' => 'Carlo Ancelotti',
            'football_personality' => 'Tactical Analyst',
        ]);

        $response->assertRedirect(route('profiles.show', $user));
    }

    public function test_user_cannot_use_duplicate_username(): void
    {
        $user1 = User::factory()->create(['username' => 'john']);
        $user2 = User::factory()->create(['username' => 'jane']);

        $response = $this->actingAs($user2)->put(route('profile.update'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'username' => 'john', // Already taken by user1
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_profile_edit_page_displays_current_values(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'bio' => 'Football enthusiast',
            'country' => 'Nigeria',
            'favorite_coach' => 'Pep Guardiola',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertSee('John Doe');
        $response->assertSee('Football enthusiast');
        $response->assertSee('Nigeria');
        $response->assertSee('Pep Guardiola');
    }
}
