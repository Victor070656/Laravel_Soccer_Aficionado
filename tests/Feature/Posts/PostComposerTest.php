<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PostComposerTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_composer_creates_a_post_with_media_and_dispatches_refresh_event(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test('posts.composer', ['mode' => 'feed'])
            ->set('body', 'Great match tonight')
            ->set('type', 'meme')
            ->set('media', [UploadedFile::fake()->image('kit.jpg')])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertDispatched('post-created');

        $post = Post::latest('id')->first();

        $this->assertNotNull($post);
        $this->assertSame($user->id, $post->user_id);
        $this->assertSame('Great match tonight', $post->body);
        $this->assertSame('meme', $post->type);

        $mediaPaths = json_decode((string) $post->getRawOriginal('media'), true);

        $this->assertIsArray($mediaPaths);
        $this->assertNotEmpty($mediaPaths);
        Storage::disk('public')->assertExists($mediaPaths[0]);
    }

    public function test_dashboard_composer_redirects_to_the_post_page_after_submit(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test('posts.composer', [
            'mode' => 'dashboard',
            'redirectAfterSubmit' => true,
        ])
            ->set('body', 'Dashboard post')
            ->call('submit');

        $component->assertHasNoErrors();

        $post = Post::latest('id')->first();

        $this->assertNotNull($post);
        $component->assertRedirect(route('posts.show', $post));
    }
}
