<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PostApiController extends BaseApiController
{
    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
    ) {
    }

    public function index(Request $request)
    {
        $posts = Post::with(['user', 'community'])
            ->approved()
            ->latest()
            ->paginate(20);

        return $this->success($posts);
    }

    public function feed(Request $request)
    {
        $posts = Post::with(['user', 'community'])
            ->feed($request->user())
            ->paginate(20);

        return $this->success($posts);
    }

    public function show(Post $post)
    {
        $post->load(['user', 'community', 'comments.user']);

        return $this->success($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
            'community_id' => 'nullable|exists:communities,id',
        ]);

        $post = $request->user()->posts()->create($validated);

        $this->gamification->awardPoints($request->user(), 'post_created', $post);

        return $this->success($post, 'Post created.', 201);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return $this->error('Unauthorized.', 403);
        }

        $post->delete();

        return $this->success(null, 'Post deleted.');
    }

    public function like(Request $request, Post $post)
    {
        $user = $request->user();

        if ($user->hasLiked($post)) {
            $post->likes()->where('user_id', $user->id)->delete();
            $post->decrement('likes_count');

            return $this->success(null, 'Like removed.');
        }

        $post->likes()->create(['user_id' => $user->id]);
        $post->increment('likes_count');

        $this->notifications->notifyLike($user, $post);

        return $this->success(null, 'Post liked.');
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        $post->increment('comments_count');
        $this->notifications->notifyComment($request->user(), $post);

        return $this->success($comment->load('user'), 'Comment added.', 201);
    }
}
