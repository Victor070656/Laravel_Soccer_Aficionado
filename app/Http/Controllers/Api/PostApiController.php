<?php

namespace App\Http\Controllers\Api;

use App\Concerns\AppendsPostFlags;
use App\Concerns\ExtractsMentions;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Services\GamificationService;
use App\Services\NotificationService;
use App\Services\ReactionService;
use Illuminate\Http\Request;

class PostApiController extends BaseApiController
{
    use AppendsPostFlags, ExtractsMentions;

    public function __construct(
        protected GamificationService $gamification,
        protected NotificationService $notifications,
        protected ReactionService $reactions,
    ) {
    }

    public function index(Request $request)
    {
        $posts = Post::with(['user', 'community'])
            ->withCount(['reactions as likes_count', 'comments', 'shares'])
            ->approved()
            ->latest()
            ->paginate(20);

        $this->appendPostFlags($posts);

        return $this->success($posts);
    }

    public function feed(Request $request)
    {
        $posts = Post::with(['user', 'community'])
            ->withCount(['reactions as likes_count', 'comments', 'shares'])
            ->feed($request->user())
            ->paginate(20);

        $this->appendPostFlags($posts, $request->user());

        return $this->success($posts);
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'community',
            'comments' => fn($q) => $q->with(['user', 'replies.user'])->where('is_approved', true)->latest(),
        ]);
        $post->loadCount(['reactions as likes_count', 'comments', 'shares']);

        $this->appendPostFlags($post);

        return $this->success($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
            'community_id' => 'nullable|exists:communities,id',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:10240',
        ]);

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('posts', 'public');
            }
        }

        $post = $request->user()->posts()->create([
            'body' => $validated['body'],
            'community_id' => $validated['community_id'] ?? null,
            'type' => !empty($mediaPaths) ? 'image' : 'text',
            'media' => !empty($mediaPaths) ? $mediaPaths : null,
        ]);

        $this->extractAndSaveMentions($validated['body'], $post);

        $this->gamification->awardPoints($request->user(), 'post_created', $post);
        $this->gamification->recordActivity($request->user(), 'post_created', $post);

        $post->load('user');

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
        $summary = $this->reactions->toggle(
            $request->user(),
            'post',
            $post->id,
            $request->string('emoji', Reaction::DEFAULT_EMOJI)->toString(),
        );

        return $this->success($summary, 'Reaction updated.');
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $this->extractAndSaveMentions($validated['body'], $comment);

        $post->increment('comments_count');

        $this->gamification->awardPoints($request->user(), 'comment_created', $comment);
        $this->gamification->recordActivity($request->user(), 'comment_created', $comment);
        $this->notifications->notifyComment($request->user(), $post);

        return $this->success($comment->load('user'), 'Comment added.', 201);
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return $this->error('Unauthorized.', 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $post->update(['body' => $validated['body']]);

        // Re-extract mentions
        $post->mentions()->delete();
        $this->extractAndSaveMentions($validated['body'], $post);

        $post->load(['user', 'community']);
        $post->loadCount(['reactions as likes_count', 'comments', 'shares']);

        return $this->success($post, 'Post updated.');
    }

    public function share(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        $share = $post->shares()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'] ?? null,
        ]);

        $post->increment('shares_count');

        $this->gamification->awardPoints($request->user(), 'share_created', $post);

        return $this->success($share, 'Post shared.');
    }

    public function pin(Request $request, Post $post)
    {
        if (!$request->user()->isAdmin()) {
            return $this->error('Unauthorized.', 403);
        }

        $post->update(['is_pinned' => !$post->is_pinned]);

        $status = $post->is_pinned ? 'pinned' : 'unpinned';

        return $this->success(['is_pinned' => $post->is_pinned], "Post {$status}.");
    }

    public function deleteComment(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return $this->error('Unauthorized.', 403);
        }

        $comment->post->decrement('comments_count');
        $comment->delete();

        return $this->success(null, 'Comment deleted.');
    }
}
