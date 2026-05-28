<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class PostReactionBarTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_index_renders_the_shared_reaction_bar(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Index page post',
            'type' => 'text',
            'is_approved' => true,
        ]);

        $this->actingAs($user);
        view()->share('errors', new ViewErrorBag());

        $html = view('posts.index', [
            'posts' => Post::with(['user', 'community'])
                ->withCount(['likes', 'comments', 'shares'])
                ->approved()
                ->latest()
                ->paginate(20),
        ])->render();

        $this->assertStringContainsString('Index page post', $html);
        $this->assertStringContainsString('toggleReaction(', $html);
    }

    public function test_post_show_renders_the_shared_reaction_bar(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Show page post',
            'type' => 'text',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Show page post');
        $response->assertSee('toggleReaction(');
    }
}
